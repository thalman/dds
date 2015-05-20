<?php

include_once "config.php";
include_once "aducid/aducid.php";


$a = new AducidClient($GLOBALS["aim"]);
$result = $a->getPSLAttributes( AducidAttributeSetName::STATUS,$_GET["authId"],NULL,$_GET["bindingId"] );

header('Content-Type: text/plain');
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

var_export($result);

?>