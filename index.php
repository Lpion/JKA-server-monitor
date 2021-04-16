<?php
$debug = true;

$server_ip = 'udp://64.94.95.202';
$server_port = '29070';

//Check general connection
$connect = fsockopen($server_ip, $server_port, $errno, $errstr, 1);

if ( ! $connect ) {
   if($debug){
      echo "Error #$errno : $errstr <br>";
   } 
   echo 'Server is OFFLINE <br>';     
}
else { 
   echo 'Server is ONLINE <br>';
}

$send = "\xFF\xFF\xFF\xFFgetstatus\n"; 

fputs($connect, $send);
fwrite ($connect, $send);
$output = fread ($connect, 1);
if (! empty ($output)) {
   do {
     $status_pre = socket_get_status ($connect);
     $output = $output . fread ($connect, 1);
     $status_post = socket_get_status ($connect);
   } while ($status_pre["unread_bytes"] != $status_post["unread_bytes"]);
};


// Close the connection:
fclose($connect);

// // Select the variables from the $output array:
$output = explode ("\\", $output); 

print_r($output);

?>