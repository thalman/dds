var authId;
var bindingId;
var bindingKey;
var worker;

function setOpacity(element,opacity) {
    element.style.opacity = opacity / 100;
}

function fadeOutFunc(elementname,opacity) {
    e = document.getElementById(elementname);
    if(e) {
        setOpacity(e,opacity);
    }
}

function fade( elementname, from, to, step, time ) {
    var steps = (from - to) / step;
    var timeStep = time / Math.abs(steps);
    var time = 0;
    if( from < to ) {
        for(var i = from; i < to; i += step ) {
            setTimeout("fadeOutFunc(\"" + elementname + "\"," + i +")", time );
            time += timeStep;
        }
    } else {
        for(var i = from; i > to; i -= step ) {
            setTimeout("fadeOutFunc(\"" + elementname + "\"," + i +")", time );
            time += timeStep;
        }
    }
    setTimeout("fadeOutFunc(\"" + elementname + "\"," + to +")",time + timeStep);
}


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
        setTimeout("fade(\"aducidqrcode\",5,100,15,500)",1000);
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
    if( event.data[0] == "fadeout" ) {
        fade("aducidqrcode",100,1,2,3000);
    }
}

function aducid_start() {
    // if have worker
    worker = new Worker("responderJS.php?js=aducidWebWorker");
    worker.onmessage = onWorkerMessage;
}

window.onload = aducid_start;
