<?php

// Copyright 2015 Tomas Halman
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

include "phpqrcode.php";

function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);
    $r = 0;
    $g = 0;
    $b = 0;
    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    }
    if(strlen($hex) == 6) {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    return array ($r,$g,$b);
}


$text = isset($_REQUEST["qr"]) ? $_REQUEST["qr"] : "";
$GLOBALS["qrfgcolor"] = isset($_REQUEST["fgcolor"]) ? hex2rgb($_REQUEST["fgcolor"]) : array(0,0,0);
$GLOBALS["qrbgcolor"] = isset($_REQUEST["bgcolor"]) ? hex2rgb($_REQUEST["bgcolor"]) : array(255,255,255);
QRcode::png( $text, false,  0 /* QR_ECLEVEL_L */, 4 /* size */, 0 /* margin */,false );

?>