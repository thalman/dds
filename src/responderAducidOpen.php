<?php

include_once "config.php";
include_once "aducid/aducid.php";

session_name($GLOBALS["sessionname"]);
session_start();

$a = new AducidSessionClient($GLOBALS["aim"]);
$a->open();

header('Content-Type: text/plain');
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

print "authId=" . urlencode($a->authId) . "\n" . "authKey=" . urlencode($a->authKey) . "\nbindingId=" . urlencode($a->bindingId) . "\nbindingKey=" . urlencode($a->bindingKey) ."\n";

print "\n" . $GLOBALS["myurl"] . "aducidStatus.php?authId=". urlencode($a->authId) ."&bindingId=" . urlencode($a->bindingId) . "\n";

?>