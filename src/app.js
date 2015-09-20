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
