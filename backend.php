<?php

define('PRINTER_IP', '123.123.123.123'); // replace printer ip
define('PRINTER_PORT', 9100);

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed.';
    exit;
}

$zpl_data = file_get_contents('php://input');

if (empty($zpl_data)) {
    http_response_code(400);
    echo 'No ZPL data received.';
    exit;
}

if (!preg_match('/^\^XA.*\^XZ$/s', $zpl_data)) {
    http_response_code(400);
    echo 'Invalid ZPL data.';
    exit;
}

$socket = @fsockopen(PRINTER_IP, PRINTER_PORT, $errno, $errstr, 10);

if (!$socket) {
    http_response_code(500);
    echo 'Could not connect to printer: ' . $errstr . ' (' . $errno . ')';
    exit;
}

$zpl_data = mb_convert_encoding($zpl_data, "ISO-8859-1");
fwrite($socket, $zpl_data);
fclose($socket);

http_response_code(200);
echo 'Print job sent successfully.';
?>
