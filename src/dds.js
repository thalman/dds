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
"Já, " + name + ", zplnomocňuji Marii Černou." +
"</textarea><input type=\"submit\" class=\"btn btn-default\" value=\"podepsat\"><input type=\"hidden\" name=\"action\" value=\"signpoa\" /></form>";
}

function popupIfNeeded() {
    var dialog = document.getElementById('popupModal');
    if(dialog) {
	$('#popupMessage').modal('show');
    }
};

