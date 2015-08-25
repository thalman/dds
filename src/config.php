<?php

// URL of AIM server
$GLOBALS["aim"] = "http://aim.example.com/";

// URL of this application
$GLOBALS["myurl"] = "http://dds.example.com/dds/";

// Atributu set for reading/writing
$GLOBALS["attrset"] = "UIM";

// PHP session name
$GLOBALS["sessionname"] = "dds";

// password for reset identities
$GLOBALS["resetpassword"] = "secret";

// time [min], for how long application reset is protected with password
$GLOBALS["resettime"] = 10000000;

// after this timeout [s], new QR code is created and used
// 5s before this timeout QR code starts to fade out
// this parameter should be little bit shorter than startTimeout of AIM
$GLOBALS["qrcodetimeout"] = 25;

?>
