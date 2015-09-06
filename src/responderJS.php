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