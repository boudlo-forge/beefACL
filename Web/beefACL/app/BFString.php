<?php

namespace App;

class BFString
{
    public static function obfuscateEmail($emailString)
    {
		$prop=2;
		$domain = substr(strrchr($emailString, "@"), 1);
		$mailname=str_replace($domain,'',$emailString);
		$name_l=strlen($mailname);
		$domain_l=strlen($domain);
		$start='';
		$end='';
		
		for($i=0;$i<=$name_l/$prop-1;$i++)
		{
			$start.='*';
		}

		for($i=0;$i<=$domain_l/$prop-1;$i++)
		{
			$end.='*';
		}

		return substr_replace($mailname, $start, 2, $name_l/$prop).substr_replace($domain, $end, 2, $domain_l/$prop);
	}

	public static function validateEmail($emailString) 
	{
		$r = '/A\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b\z/i';
		preg_match($r, $emailString, $m);
		return count($m) == 1;
	}

	public static function validateBase32($base32String)
	{
		$r = '/\A[a-z2-7]{5,6}\z/i';
		preg_match($r, trim($base32String),$m);
		return count($m) == 1;
	}

	public static function validateMacAddress($macAddressString)
	{
		$r = "/\A^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$\z/i";
		preg_match($r, trim($macAddressString),$m);
		return count($m) == 1;
	}

	public static function breakFullNameDown($fullNameString)
	{
		$nameBits = explode(' ', ucwords(strtolower(trim($fullNameString))));

		$rtn = [
			'first' => '',
			'middle' => '',
			'last' => ''
		];

		$namePrefixes =[
			'Le',
			'De',
			'La',
			'Bin',
			'Von'
		];

        if(count($nameBits)) {
            $rtn['first'] = array_shift($nameBits);

            if(count($nameBits)) {
                $rtn['last'] = array_pop($nameBits);

                if(count($nameBits)) {
                    $rtn['last'] = count($nameBits) - 1;

                    if(in_array($nameBits[$lastIndex], $namePrefixes)) {
                    	$rtn['last'] = array_pop($nameBits) . ' ' . $rtn['last'];
                    }

                    if(count($nameBits)) {
                        $rtn['middle'] = implode(' ', $nameBits);
                    }
                }
            }
        }
	}
}