var authId;
var bindingId;
var bindingKey;

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
}

function check() {
    var xmlHttp = null;
    var response;
    
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
