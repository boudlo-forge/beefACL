<?php

const RESERVED_LOCKOUT = 1;
const RESERVED_UNLOCK = 2;
const DOOR_FRONT = 4;
const DOOR_STORES = 8;
const DOOR_ADMIN = 16;
const TOOL_LASER = 32;
const TOOL_FDMPRINTER = 64;
const TOOL_MILL = 128;
const TOOL_LATHE = 256;

const PSK = "marmoset";

$lists = [

        'nodes' => [
                '60:01:94:17:8E:6B' => [
                        'name' => 'Mill 1',
                        'access' => TOOL_MILL
                ],
        ],

        'users' => [
                '04073812524680' => [
                        'name' => 'Jim',
                        'access' => DOOR_FRONT + TOOL_LASER + TOOL_FDMPRINTER
                ],
                'C41103C5' => [
                        'name' => 'Marcel',
                        'access' => DOOR_FRONT + DOOR_ADMIN + TOOL_LATHE
                ],
                '7427C14D' => [
                        'name' => 'Adrian',
                        'access' => DOOR_FRONT + DOOR_STORES + TOOL_LATHE + TOOL_FDMPRINTER
                ],
                '565FCE93' => [
                        'name' => 'MAINTENANCE LOCKOUT',
                        'access' => RESERVED_LOCKOUT
                ],
                '34CEC34D' => [
                        'name' => 'MAINTENANCE UNLOCK',
                        'access' => RESERVED_UNLOCK
                ],
        ],
];

$list = 'users';

if( isset($_REQUEST['list']) && array_key_exists($_REQUEST['list'], $lists)) {
        $list = $_REQUEST['list'];
}

$lines = [];

foreach( $lists[$list] as $id => $details ) {
        $hash = sha1($id . $details['name'] . $details['access'] . PSK);
        $lines[] = $id . '|' . $details['name'] . '|' . $details['access'] . '|' . $hash;
}

echo strtoupper($list) . "_BOF\n";
echo implode($lines, "\n");
echo "\n" . strtoupper($list) . "_EOF\n";
