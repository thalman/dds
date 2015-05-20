var authId;
var bindingId;
var bindingKey;
var worker;

function set_new_qrcode() {
    var img = document.getElementById("aducidqrcode");
    if( img != null ) {
        img.src = "responderQRCode.php?bgcolor=fdb813&qr=" + escape("aducid://callback?authId=" + authId + "&bindingId=" + bindingId + "&bindingKey=" + bindingKey + "&r3Url=" + escape("@aim@AIM/services/R3") );
    }
}

function onWorkerMessage(event) {
    if( event.data[0] == "open" ) {
        authId = event.data[1];
        bindingId = event.data[2];
        bindingKey = event.data[3];
        set_new_qrcode();
    }
    if( event.data[0] == "error" ) {
	alert(event.data[1]);
    }
    if( event.data[0] == "login" ) {
        authId = event.data[1];
        bindingId = event.data[2];
        bindingKey = event.data[3];
        window.location = "index.php?action=logina&authId=" + authId + "&bindingId=" + bindingId + "&bindingKey=" + bindingKey;
    }
}

function aducid_start() {
    // if have worker
    worker = new Worker("responderJS.php?js=aducidWebWorker");
    worker.onmessage = onWorkerMessage;
}

window.onload = aducid_start;
