<?php
/*
This is a super basic example script showing the bitwise logic behind the access
controls. It spits out delimited data, (pipe delimited by default) to ESP or
similar IoT nodes in order to configure access to infrastructure defined by the
constants below.
*/

const RESERVED_LOCKOUT = 1;     // 0000000000000001
const RESERVED_UNLOCK = 2;      // 0000000000000010
const DOOR_FRONT = 4;           // 0000000000000100
const DOOR_STORES = 8;          // 0000000000001000
const DOOR_ADMIN = 16;          // 0000000000010000
const SAFETY_BASIC = 32;        // 0000000000100000
const SAFETY_LASER = 64;        // 0000000001000000
const SAFETY_MACHINING = 128;   // 0000000010000000
const TOOL_LASER = 256;         // 0000000100000000
const TOOL_FDMPRINTER = 512;    // 0000001000000000
const TOOL_MILL = 1024;         // 0000010000000000
const TOOL_LATHE = 2048;        // 0000100000000000

/*
The astute among you will notice that the numbers are doubling every line,
from the binary representations you can see that we are actually just shifting
the 1 along the bits. If we decide that each bit represents an action that
requires permission, we may define the access permissions for a user by using
just one integer. If a bit is a 1, they can do that thing, if it's 0, they can't.

Still not making sense? That's ok, keep following along, it's not rocket surgery
but it's also not immediately intuitive if you haven't dealt in binary before.

Say we have a user Joe, we decide that he allowed to open the door (DOOR_FRONT)
and use the laser cutter (TOOL_LASER). So the bits need to look like:

         Door access
             v
0000000000100100
          ^
     Laser cutter

Which is the binary representation of 36, in other words 4 + 32.
Simple maffs, innit?

In code this could be represented simply as

    $joesAccess = DOOR_FRONT + TOOL_LASER;

Joe can access the front door and the laser cutter. Tidy, huh?
This will work fine for adding bits if you are careful, however when defining
access you'll probably want to use the special bitwise OR operator | (a single
pipe). This is just in case you accidentally add the same bit twice. For example

    $joesAccess = DOOR_FRONT + TOOL_LASER + TOOL_LASER;

        -alternatively-

    $joesAccess = DOOR_FRONT + TOOL_LASER;
    $joesAccess += TOOL_LASER;

We'll get 68 which is
        Door access
             v
0000000001000100
         ^
     TOOL_FDMPRINTER

Which is obviously not right. Joe has no access to the laser and now he has
access to the 3D Printer. If we do the following however:

    $joesAccess = DOOR_FRONT | TOOL_LASER | TOOL_LASER;

        -alternatively-

    $joesAccess = DOOR_FRONT | TOOL_LASER;
    $joesAccess |= TOOL_LASER;

It will still give 36 as expected. This is also useful when you want to safely
group permissions and hand them around in variables.

So why use bitwise in the first place? why not manage this stuff instead of a
has-many relationship in a database? In a word, RESOURCES. You can store
multiple settings in a single integer variable. You're also talking on the
computers terms - in binary. This is the fastest kind of operation the computer
can do. This makes it very lightweight and perfect for interacting with IoT
nodes which may have both limited horsepower and restricted connectivity.
*/

// Config Flags
const CONFIG_LATCH_ON = 1;
const CONFIG_LOG_USAGE = 2;
const CONFIG_MESH_NETWORK = 4;

const PSK = "SOME RANDOM CHARS";
// Basically a salt to allow the node to verify that we're genuine.

// We'll just use some multi-dimensional arrays instead of a db for testing
$lists = [
    'users' => [
        [
            'card_uid' => '04073812524680',
            'name' => 'Jim',
            'access' => DOOR_FRONT | SAFETY_BASIC | SAFETY_LASER | TOOL_LASER
        ],
        [
            'card_uid' => 'C41103C5',
            'name' => 'Marcel',
            'access' => DOOR_FRONT | DOOR_ADMIN | SAFETY_BASIC | TOOL_FDMPRINTER
        ],
        [
            'card_uid' => '7427C14D',
            'name' => 'Adrian',
            'access' => DOOR_FRONT | DOOR_STORES | SAFETY_BASIC | SAFETY_MACHINING | TOOL_LATHE | TOOL_FDMPRINTER
        ],
        [
            'card_uid' => '565FCE93',
            'name' => 'MAINTENANCE LOCKOUT',
            'access' => RESERVED_LOCKOUT
        ],
        [
            'card_uid' => '34CEC34D',
            'name' => 'MAINTENANCE UNLOCK',
            'access' => RESERVED_UNLOCK
        ],
    ],

    // Work in progress, nodes can ask for config updates. Need more nodes
    // so I can develop this more properly
    'nodes' => [
        [
            'mac_addr' => '60:01:94:17:8E:6B',
            'name' => 'Mill 1 Men\'s Shed',
            'access' => SAFETY_MACHINING | TOOL_MILL
            // You need to have done the Machining Safety and have mill-access
        ],
    ],
];

// Default users list
$list = 'users';

if( isset($_REQUEST['list']) && array_key_exists($_REQUEST['list'], $lists)) {
        $list = $_REQUEST['list'];
}

$lines = [];

// helper function to remove pipes, just in case
function p(String $string) {
    return str_replace( '|', '', $string );
}

foreach( $lists[$list] as $id => $details ) {
    $details['hash'] = sha1(implode($details) . PSK);
    $lines[] = implode(array_map('p', $details), '|');
}

echo strtoupper($list) . "_BOF\n";
echo implode($lines, "\n");
echo "\n" . strtoupper($list) . "_EOF\n";
