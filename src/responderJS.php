<?php

include_once "config.php";

function printJS($file) {
    $handle = fopen($file, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $line = str_replace("@aim@",$GLOBALS["aim"],$line);
            $line = str_replace("@qrcodetimeout@",$GLOBALS["qrcodetimeout"],$line);
            echo "$line";
        }
    }
    fclose($handle);
}

$FILE = $_REQUEST["js"];

header('Content-Type: application/x-javascript');
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

switch($FILE) {
case "aducidWorker":
    printJS("aducidWorker.js");
    break;
case "aducidWebWorker":
    printJS("aducidWebWorker.js");
    break;
case "dds":
    printJS("dds.js");
    break;
}

?>