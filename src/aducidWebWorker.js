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

var authId;
var bindingId;
var bindingKey;
var starttime;
var fadeout = false;

function aducid_open() {
    var xmlHttp = null;
    var items;
    var a;
    var varitem;
    
    // FIXME vypnuto
    //return ;
    
    xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", "responderAducidOpen.php", false );
    xmlHttp.send( null );
    items = xmlHttp.responseText.split("\n");
    for(a=0; a<items.length; a++) {
        varitem = items[a].split("=",2);
        if(varitem.length == 2) {
            if(varitem[0] == "authId" ) { authId = varitem[1]; };
            if(varitem[0] == "bindingId" ) { bindingId = varitem[1]; };
            if(varitem[0] == "bindingKey" ) { bindingKey = varitem[1]; };
        }
    }
    postMessage(["open",authId,bindingId,bindingKey]);
    starttime = new Date().getTime();
    fadeout = false;
}

function check() {
    var xmlHttp = null;
    var response;
    var now = new Date().getTime();
    
    if( now - starttime >= (@qrcodetimeout@ - 5) * 1000 && ! fadeout ) {
        // send fadeout message QR code is about to expire
        postMessage(["fadeout"]);
        fadeout=true;
    }
    if( now - starttime >= ( @qrcodetimeout@ ) * 1000 ) {
        // create new image
        aducid_open();
    }
    // FIXME vypnuto
    //setTimeout(check,1000);
    //return ;
    
    xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", "responderAducidStatus.php?authId=" + authId + "&bindingId=" + bindingId + "&bindingKey=" + bindingKey, false );
    xmlHttp.send( null );

    response = xmlHttp.responseText.trim().toLowerCase();
    //postMessage([response]);
    if( response == "'starttimeout'" ) {
        // create new image
        aducid_open();
    }
    if( response == "'error'" ) {
        // create new image
        postMessage(["error","Chyba autentizace!"]);
        aducid_open();
    }
    if( response == "'finished'" ) {
        // create new image
        // alert("Autentizace dopadla úspěšně!");
        postMessage(["login",authId,bindingId,bindingKey]);
    }
    setTimeout(check,1000);
}

aducid_open();
check();
