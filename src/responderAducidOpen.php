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