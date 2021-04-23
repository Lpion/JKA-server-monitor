<?php
// Connection
$host = $_POST['host'];
$port = $_POST['port'];

// UDP Socket
$socket = socket_create (AF_INET, SOCK_DGRAM, SOL_UDP);

// jka getstatus request, thx Miha <3
$jka_request = str_repeat(chr(0xFF),4)."getstatus\n";

// Send UDP Packet
socket_sendto($socket, $jka_request, strlen($jka_request), 0x0, $host, $port);  

do
{
// 4 bytes for the header + 2000 bytes of data
socket_recvfrom($socket, $buffer, 2004, 0, $host, $port);

// append the data to the return variable
$ret .= substr($buffer, 4);
}
while(strlen($buffer) == 2000);  // the first non-full packet is the last

// all information is returned as single string, so we make an array
$retArray = explode("\\",$ret);
// all player infos are in a single string, so they get seperated into an array
$playerinfo = array_slice( explode("\n",end($retArray)), 1, -1);
// encode to cover special characters in names
$playerinfo = array_map('utf8_encode', $playerinfo);
// Create array that will be returned for AJAX call
$jkaData = array(
    "mod" => $retArray[array_search('gamename',$retArray) + 1],
    "map" => $retArray[array_search('mapname',$retArray) + 1],
    "players" => array()
);

// optimize player array
foreach ($playerinfo as $value) {
    // Seperate player names from ping and id 
    $player = preg_split('~"[^"]*"(*SKIP)(*F)|\s+~', $value);
    // Remove quotes surrounding player names
    $player[2] = trim($player[2], '"');
    // Remove first index, as its not needed
    array_splice($player, 0, 1);
    //add player infos to jkaData
    array_push($jkaData['players'],$player);
}

// return Data for Front End
echo json_encode($jkaData);
?>