<?php
//include_once "config.php";
include_once "database.php";

header('Content-Type: text/plain');
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$db = new DDSDatabase();
echo ( $db->isResetBlocked() ? "true" : "false" );

?>