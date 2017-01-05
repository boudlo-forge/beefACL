#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include "MFRC522.h"
#include "FS.h"
#include <Hash.h>

/* wiring the MFRC522 to NodeMCU ESP8266
RST     = GPIO16 / D0
SDA(SS) = GPIO4 / D2
MOSI    = GPIO13 / D7
MISO    = GPIO12 / D6
SCK     = GPIO14 / D5
GND     = GND
3.3V    = 3.3V
*/

#define RST_PIN	16 // D0 (GPIO16) Re-use the LED Pin
#define SS_PIN	4  // D2 (GPIO4)
#define RELAY_PIN 5 // D1 (GPIO5) 5 is safe for the relay as it is held low on boot / flash

const char* ssid = "Digital GH Guest";
const char* password = "dgguest1";
const char *hashPsk = "marmoset";
const int accessBit = 4; // DOOR_FRONT

const char *aclDelim = "|";

const char *usersBof = "USERS_BOF";
const char *usersEof = "USERS_EOF";

const char *nodesBof = "NODES_BOF";
const char *nodesEof = "NODES_EOF";

MFRC522 mfrc522(SS_PIN, RST_PIN);	// Create MFRC522 instance

int relayState = LOW;
bool authenticated = false;
char authedCard;

unsigned long previousAclMillis = 0;
unsigned long previousReadMillis = 0;
unsigned long previousRelayMillis = 0;

const long readInterval = 2500;
const long relayInterval = 15000;
const long aclInterval = 120000;

void setup() {
  // Switch Relay state to LOW
  pinMode(RELAY_PIN, OUTPUT);
  digitalWrite(RELAY_PIN, relayState);
  
  Serial.begin(9600);
  delay(250);
  Serial.println(F("Booting...."));
  
  SPI.begin();
  mfrc522.PCD_Init();
  
  // We start by connecting to a WiFi network
  Serial.print("Attempting to connect to network '");
  Serial.print(ssid);
  Serial.println("'.");
  
  WiFi.begin(ssid, password);
  int wifiRetries = 0;
  
  while (WiFi.status() != WL_CONNECTED && wifiRetries < 20) {
    delay(500);
    Serial.print(".");
    wifiRetries++;
  }

  if ( WiFi.status() != WL_CONNECTED ) {
    Serial.println("Unable to connect.");
    // TODO: Mesh Networking here?
  } else {
    Serial.println("WiFi connected");
    Serial.println("IP address: ");
    Serial.println(WiFi.localIP());
    Serial.println("MAC address: ");
    Serial.println(WiFi.macAddress());
  }
  
  Serial.println("Mounting File System...");

  if (!SPIFFS.begin()) {
    Serial.println("Failed to mount File System.");
  } else {
    Serial.println("File system mounted. Looking for existing ACL...");
  }

  if (!loadACL()) {
    Serial.println("No ACL Found. Attempting to download up-to-date file...");
    if(!getUsers()) {
      Serial.println("Failed to retrieve ACL file. Will retry in " + String(aclInterval / 1000) + " seconds");
    }
  } else {
    Serial.println("ACL loaded from flash memory. " + String(aclInterval / 1000) + " seconds until next update.");
  }
  
  Serial.println(F("Ready!"));
  Serial.println(F("======================================================"));
}

void loop() {
  
  unsigned long currentAclMillis = millis();
  
  // Time for an ACL Update, do we have WiFi?
  if((currentAclMillis - previousAclMillis >= aclInterval) && (WiFi.status() == WL_CONNECTED)) {
    previousAclMillis = currentAclMillis;
    
    Serial.println("UPDATING USERS");
    getUsers();
  }

  // TODO IF MAINTENANCE LOCKOUT / UNLOCK
  
  unsigned long currentReadMillis = millis();
  
  if(currentReadMillis - previousReadMillis >= readInterval) {
    previousReadMillis = currentReadMillis;
    
    //Serial.println("CARD CHECK");

    authenticated = false;
    relayState = LOW;

    // Look for new cards
    if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
      // Show some details of the PICC (that is: the tag/card)
      Serial.print("Card UID:");    
      char cardUID[64] = { 0 };
      
      for(uint16_t i = 0; i < mfrc522.uid.size; i++) {
          char hBuff[2] { 0 };
          sprintf(hBuff, "%02x", mfrc522.uid.uidByte[i]);
          strcat(cardUID, hBuff);
      }
      
      for( int i=0 ; i < strlen(cardUID) ; ++i ) cardUID[i] = toupper( cardUID[i] ) ;
      
      Serial.println(cardUID);
      
      if ( authCard(cardUID) ) {
        // if authed set HIGH
        relayState = HIGH;
        previousRelayMillis = millis(); // Reset the relay timeout to "de-bounce" the card detection.
      
        if (digitalRead(RELAY_PIN) != relayState) {
          digitalWrite(RELAY_PIN, relayState);
        }
      } else {
        relayState = LOW;
        digitalWrite(RELAY_PIN, relayState);
      }
      
      return;
    }
  }
  
  unsigned long currentRelayMillis = millis();
  
  // Time for an ACL Update, do we have WiFi?
  if((currentRelayMillis - previousRelayMillis >= relayInterval)) {
    previousRelayMillis = currentRelayMillis;
    
    Serial.println("RELAY TIME OUT");
    
    if (digitalRead(RELAY_PIN) != relayState) {
      digitalWrite(RELAY_PIN, relayState);
    }
  }
}

// Helper routine to dump a byte array as hex values to Serial
void dump_byte_array(byte *buffer, byte bufferSize) {
  for (byte i = 0; i < bufferSize; i++) {
    Serial.print(buffer[i] < 0x10 ? " 0" : " ");
    Serial.print(buffer[i], HEX);
  }
}

bool getUsers() {
  
  HTTPClient http;
  
  // configure server and url
  http.begin("http://boudlo.gg/access.php");
  
  int httpCode = http.GET();
  
  if(httpCode == HTTP_CODE_OK) {
    
    // Delete any failed files if they exist.
    if ( SPIFFS.exists("/new.acl") ) { SPIFFS.remove("/new.acl"); }
    if ( SPIFFS.exists("/authList.new") ) { SPIFFS.remove("/authList.new"); }

    // get tcp stream
    WiFiClient * stream = http.getStreamPtr();

    int fileQCStatus = 0;
    File newAcl = SPIFFS.open("/next.acl", "w");
    File newAuthList = SPIFFS.open("/authList.new", "w");
    
    // read all data from server
    while(http.connected()) {
      // get available data size
      size_t size = stream->available();

      // create buffer for read
      char buff[256] = { 0 };
      
      /* 
       * WARNING - Lines in the delimited file should not be longer 
       * than this, if they are, increase the size of buff.
       */
      
      char uid[21] = { 0 };
      char nickname[65] = { 0 };
      char accessBits[65] = { 0 };
      char theirHash[41] = { 0 };
      char toHash[128] { 0 };
      uint8_t hash[20];

      if(size) {
        // read up to the first newline char or 256 bytes
        int c = stream->readBytesUntil('\n', buff, ((size > sizeof(buff)) ? sizeof(buff) : size));
        
        if(strcmp(buff, usersBof) == 0) {
          fileQCStatus = 1;
          newAcl.println(usersBof);
          continue;
        }
        
        if( strcmp(buff, usersEof) == 0 && fileQCStatus == 1) {
          fileQCStatus = 2;
          newAcl.println(usersEof);
          break;
        }
        
        // Ignore shorter/empty lines
        if( c >= 7 ) {
          char * pch;
          pch = strchr(buff, '|');
          int lastpos = pch-buff+1;
          strncpy(uid, buff, pch-buff);
          
          pch = strchr(pch+1,'|');
          strncpy(nickname, pch-(pch-buff-lastpos), pch-buff-lastpos);
          lastpos = pch-buff+1;

          pch = strchr(pch+1,'|');
          strncpy(accessBits, pch-(pch-buff-lastpos), pch-buff-lastpos);
          
          lastpos = pch-buff+1;
          int sha1Len = strlen(pch-(pch-buff-lastpos));
          strncpy(theirHash, pch-(pch-buff-lastpos), sha1Len);
          
          strcat(toHash, uid);
          strcat(toHash, nickname);
          strcat(toHash, accessBits);
          strcat(toHash, hashPsk);

          sha1(toHash, &hash[0]);
          char ourHash[41] { 0 };
          
          for(uint16_t i = 0; i < 20; i++) {
              char hBuff[2] { 0 };
              sprintf(hBuff, "%02x", hash[i]);
              strcat(ourHash, hBuff);
          }

          if( strcmp(ourHash, theirHash) != 0 ) {
            Serial.println("Hash Failed.");
            break;
          }

          Serial.println("Hash Passed.");
          Serial.println(buff);
          newAcl.println(buff);

          int bint = atoi(accessBits); 
          int comp = bint & accessBit; // Access may be set by more than one bit
          
          if(comp == accessBit) {
            newAuthList.println(uid);
          }
        }
      }
    }

    if(fileQCStatus == 2) {
      // The ol' switcharoo
      SPIFFS.remove("/previous.acl");
      
      if ( SPIFFS.exists("/current.acl") ) {
        SPIFFS.rename("/current.acl", "/previous.acl");
      }
      
      SPIFFS.rename("/next.acl", "/current.acl");
      Serial.println("Validated and stored new ACL.");

      SPIFFS.remove("/authList.prev");
      
      if ( SPIFFS.exists("/authList") ) {
        SPIFFS.rename("/authList", "/authList.prev");
      }
      
      SPIFFS.rename("/authList.new", "/authList");
    }
    
    newAcl.close();
    newAuthList.close();
  } else {
    Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    return false;
  }
  
  http.end();
  return true;
}

bool loadACL() {
  File aclFile = SPIFFS.open("/current.acl", "r");
  if (!aclFile) {
    Serial.println("Failed to open ACL file.");
    return false;
  }

  size_t size = aclFile.size();
  if (size > 1024) {
    Serial.println("ACL file size is too large.");
    return false;
  }
  
  return true;
}

bool authCard(char* cardUID) {
  
  File authFile = SPIFFS.open("/authList", "r");
  if (!authFile) { Serial.println("Failed to open ACL file."); return false; }
  int len = authFile.size();

  while(len > 0) {
    // create buffer for read
    char buff[64] = { 0 };
    int c = authFile.readBytesUntil('\n', buff, ((len > sizeof(buff)) ? sizeof(buff) : len));
    buff[c-1] = '\0'; // Remove the carriage return

    if(strcmp(cardUID, buff) == 0) {
      Serial.println("Authorised.");
      authFile.close();
      return true;
    }
    
    len -= c+1; // add one for the newline.
  }

  authFile.close();
  Serial.println("Not Authorised.");
  return false;
}


