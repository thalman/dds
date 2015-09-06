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

function invokeResetDbModal(){
    xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", "responderDBBlocked.php", false );
    xmlHttp.send( null );
    if( xmlHttp.responseText.indexOf("false") >= 0 ) {
	$('#resetDbModal').modal('show');
    } else {
	$('#resetDbModalPassword').modal('show');
    }
    return false;
}

function invokeInfoDialog(){
    $('#modalInfoDialog').on('shown.bs.modal', function () { $('#modalX').focus(); });
    $('#modalInfoDialog').on('hidden.bs.modal', function () { $('#username').focus(); });
    $('#modalInfoDialog').modal('show');
    return false;
}


function createSignForm(name) {
    if( document.getElementById("poa") ) { return; };
    document.getElementById("signframe").innerHTML = "<form id=\"poa\" action=\"?\" method=\"post\"><textarea name=\"text\" class=\"form-control\" maxlength=\"79\" rows=\"4\" cols=\"40\">"+
"</textarea><input type=\"submit\" class=\"btn btn-default\" value=\"podepsat\"><input type=\"hidden\" name=\"action\" value=\"signpoa\" /></form>";
}

function popupIfNeeded() {
    var dialog = document.getElementById('popupModal');
    if(dialog) {
	$('#popupMessage').modal('show');
    }
};

