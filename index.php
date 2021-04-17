<?php
$debug = true;

// Connection
$host = "64.94.95.202";
$port = 29070;

// UDP Socket
$socket = socket_create (AF_INET, SOCK_DGRAM, SOL_UDP);

// jka getstatus request
$jka_request = str_repeat(chr(0xFF),4)."getstatus";

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

// match everything between "" including them
$pattern = '/"(.+?)"/';
preg_match_all($pattern, $ret, $names);

// Output all matches as a list
foreach($names[0] as $player) {   
  echo "$player\n<br>";
};
?>