<?php
error_reporting(E_ALL);

echo "TCP/IP Connection\n";

/* Get the port for the WWW service. */
// $service_port = getservbyname('www', 'tcp');
$service_port = 10000;

/* Get the IP address for the target host. */
// $address = gethostbyname('www.example.com');
$address = '127.0.0.1';

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "socket_create OK.\n";
}

echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

$in = "New task from Splunk! @ChengShQ @DH\n";
$out = '';

echo "Sending SMS request...";
socket_write($socket, $in, strlen($in));
echo "OK.\n";

echo "Reading response:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}

echo "Closing socket...";
socket_close($socket);

$log = 'sms.log';
file_put_contents($log, date('Y-m-d H:i:s ') . $in, FILE_APPEND | LOCK_EX);

echo "OK.\n\n";
?>