<?php

define('PRINTER_IP', '192.168.1.100'); // Replace IP with your ZPL Printer IP
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

$socket = @fsockopen(PRINTER_IP, PRINTER_PORT, $errno, $errstr, 10);

if (!$socket) {
    http_response_code(500);
    echo 'Could not connect to printer: ' . $errstr . ' (' . $errno . ')';
    exit;
}

fwrite($socket, $zpl_data);
fclose($socket);

http_response_code(200);
echo 'Print job sent successfully.';
?>
