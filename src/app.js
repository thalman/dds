/**
 * Copyright 2015 Tomas Halman
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

function onRadioClick(clickedId) {
    var id = Math.floor(clickedId / 100) * 100;
    var element = document.getElementById("radio" + id);
    while( element ) {
	if( id == clickedId ) {
	    element.src = "app-radio-selected.png";
	} else {
	    element.src = "app-radio.png";
	}
	id++;
	element = document.getElementById("radio" + id);
    }
}

var doaducid = 0;

function setLanguage(clickedId) {
    onRadioClick(clickedId);
    var element = document.getElementById("loginbutton");
    if( element ) {
	if( clickedId == 103 ) {
	    element.innerHTML = "Přihlásit<img src=\"app-login-arrow.png\">";
	    doaducid = 1;
	} else {
	    element.innerHTML = "Suivant<img src=\"app-login-arrow.png\">";
	    doaducid = 0;
	}	    
    }
}

function checkAction() {
    if( doaducid == 0 ) {
	$('#popupPassword').modal('show');
	return false;
    }
    return true;
}
