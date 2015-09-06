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
