<?php

include_once "aducid/aducid.php";
include_once "config.php";
include_once "database.php";

/**
 * hlavicka stranky
 */
function head() {
    $html =
        "<!DOCTYPE html>\n<html>\n".
        "<head>\n".
        "   <meta charset=\"utf-8\">\n".
        "   <meta http-equiv=\"X-UA-Compatible\" content=\"IE=9\" />\n".
        "   <title>Demo Police-on-web</title>\n".
        "   <link rel=\"stylesheet\" href=\"css/bootstrap.min.css\">\n".
        "   <link rel=\"stylesheet\" href=\"app.css\">\n".
        "</head>\n".
        "<body>\n".
        "<script src=\"js/jquery-1.10.1.min.js\"></script>\n".
        "<script src=\"js/bootstrap.js\"></script>\n".
        "<script src=\"app.js\"></script>\n".
        "<!-- =================================== head end -->\n";
    return $html;
}

/**
 * paticka stranky
 */
function foot() {
    return
        "\n<!-- =================================== foot -->\n".
        "</body>\n".
        "</html>\n";
}

/**
 * modalni dialog
 */
function popupMessageWithParams($image,$title,$text,$buttontext,$id = "popupModal") {
    return
        "\n<!-- =================================== popupMessage -->\n".
        "<div id=\"". $id ."\" class=\"modal fade\">\n".
        "    <div class=\"modal-dialog\">\n".
        "        <div class=\"modal-content\">\n".
        "            <div class=\"modal-header\">\n".
        "                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n".
        "                <h4 class=\"modal-title\">".
        ( ( $image != NULL ) ? "<img src=\"img/". $image ."\" width=\"30\"/>&nbsp;" : "" ) .
        $title . "</h4>\n".
        "            </div>\n".
        "            <div class=\"modal-body\">\n".
        "                <p>" . $text . "</p>\n".
        "            </div>\n".
        "            <div class=\"modal-footer\">\n".
        "                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">".$buttontext."</button>\n".
        "            </div>\n".
        "        </div>\n".
        "    </div>\n".
        "</div>\n";
}

/**
 * JS pro vyvolani modalniho dialogu po nahrani stranky
 */
function onLoadJs($popup) {
    return
        "<script type=\"text/javascript\">\n".
        "    $(window).load(function(){\n".
        "        $('#" . $popup ."').modal('show');\n".
        "    });\n".
        "</script>\n";
}

/**
 * jedna polozka radio menu
 */
function radioItem($id, $selected, $label, $func="onRadioClick") {
    $html = "<div class=\"radioitem\"><img id=\"radio" . $id . "\" src=\"app-radio" . ( $selected ? "-selected" : "" ) . ".png\"".
          " onClick='".$func ."(".$id.")'>" .
          " <label id=\"label" . $id . "\" for=\"radio" . $id . "\" onClick='".$func."(".$id.")'>" .
          $label . " </label></div>" ;
    return $html;
}

/**
 * stranka neprihlaseneho uzivatele
 */
function loginBody() {
    $html =
          "<div class=\"container appcontainer\">\n" .
          "  <div class=\"row\">\n" .
          "    <img src=\"app-header.jpg\" width=\"100%\">\n".
          "  </div>\n".
          "  <div class=\"row\">\n" .
          "    <div class=\"apptext\">\n" .
          "      <p>Déposer une déclaration en ligne</p>\n".
          "      <p>Police-on-web vous permet de déposer plainte en ligne pour les délits repris dans la liste ci-dessous, de déposer un avis d'absence, et également de déclarer votre système d'alarme.</p>\n".
          "      <p>Attention ! Si une intervention urgente est requise, appelez le 101.</p>\n" .
          "    </div>\n" .
          "    <!-- div class=\"center-block eighty\"><img src=\"app-menu.jpg\" width=\"100%\"></div -->\n".
          "  </div>\n".

          "  <div class=\"row\">\n" .
          "    <div class=\"col-sm-1\">\n" .
          "    </div>\n".
          "    <div class=\"col-sm-5\">\n" .
          "      <div class=\"sectionheader\">Systèmes d'alarme:</div>\n" .
          "      " . radioItem(200, false, "Gestion des déclarations") . "\n" .
          "      <div><br/><br/></div>\n" .
          "      <div class=\"sectionheader\">Déclaration d'absence:</div>\n" .
          "      " . radioItem(300, false, "Demande de surveillance d'habitation") . "\n" .
          "      <br/>\n" .
          "    </div>\n".
          "    <div class=\"col-sm-5\">\n" .
          "      <div class=\"sectionheader\">Dépôt de plainte:</div>\n" .
          "      " . radioItem(400, false, "Vol de vélo") . "\n" .
          "      " . radioItem(401, false, "Vol de vélomoteur") . "\n" .
          "      " . radioItem(402, false, "Vol à l'étalage") . "\n" .
          "      " . radioItem(403, false, "Dégradations diverses") . "\n" .
          "      " . radioItem(404, false, "Graffiti") . "\n" .
          "      <br/>\n" .
          "    </div>\n".
          "    <div class=\"col-sm-1\">\n" .
          "    </div>\n".
          "  </div>\n".          
          "  <div class=\"row\">\n" .
          "    <div class=\"col-sm-1\">\n" .
          "    </div>\n".
          "    <div class=\"col-sm-10\">\n" .
          "      <div class=\"sectionheader\">Méthode d'identification:</div>\n" .
          "        " . radioItem(100, false, "J'ai déjà une carte d'identité électronique, au moyen de laquelle je m'identifie","setLanguage") . "\n" .
          "        " . radioItem(101, false, "Je n'ai pas encore de carte d'identité électronique, par contre j'ai un token citoyen","setLanguage") . "\n" .
          "        " . radioItem(102, false, "Je n'ai ni carte d'identité électronique ni token; par contre j'ai un compte sur le portail fédéral","setLanguage") . "\n" .
          "        " . radioItem(103, false, "Jsem občan české republiky <img src=\"app-flag-cz.png\">","setLanguage") . "\n" .
          "      <p class=\"text-right\">\n" .
          "        <br/><br/><a id=\"loginbutton\" href=\"" . AducidClient::currentURL() . "?action=login\" class=\"btn loginbutton\"  onclick='return checkAction()' >Suivant<img src=\"app-login-arrow.png\"></a>\n" .
          "      </p>\n".
          "    </div>\n".
          "    <div class=\"col-sm-1\">\n" .
          "    </div>\n".
          "  </div>\n".
          "</div>\n";
    return $html;
}

/**
 * stranka prihlaseneho uzivatele
 */
function loggedInBody() {
    $html =
          "<div class=\"container appcontainer\">\n" .
          "  <div class=\"row\">\n" .
          "    <img src=\"app-header.jpg\" width=\"100%\">\n".
          "  </div>\n".
          "  <div class=\"row\">\n" .
          "    <div class=\"apptext\">\n" .
          "      <p>Dobrý den!</p>\n".
          "      <p>&nbsp;</p>\n" .
          "      <p>Přihlásil jste se jako</p>\n".
          "      <p class=\"appgradient\">" . $_SESSION["usercn"] . "</p>\n".
          "      <p>&nbsp;</p>\n" .
          "      <p>&nbsp;</p>\n" .
          "      <p>&nbsp;</p>\n" .
          "      <p>&nbsp;</p>\n" .
          "      <p>&nbsp;</p>\n" .
          "      <p>&nbsp;</p>\n" .
          "      <p>&nbsp;</p>\n" .
          "    </div>\n" .
          "  </div>\n".
          "  <div class=\"row\">\n" .
          "    <p class=\"text-right\">\n" .
          "    <a href=\"" . AducidClient::currentURL() . "?action=logout\" class=\"btn loginbutton\">Odhlásit se<img src=\"app-login-arrow.png\"></a>\n" .
          "    </p>\n".
          "  </div>\n".
          "</div>\n";
    return $html;
}

function body() {
    if( isset($_SESSION["username"]) ) {
        return loggedInBody();
    } else {
        return loginBody();
    }
}

/**
 * hlavni kod aplikace
 */
aducidRequire(3.002);

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "";

session_name("euapp");
session_start();
$errormsg = "";

echo head();
error_log("action is\"" . $action ."\"");
switch($action) {
case "login":
    $a = new AducidSessionClient($GLOBALS["aim"]);
    $a->open(AducidSessionClient::currentURL() . "?action=check");
    $a->invokePeig();
    break;
case "check":
    $a = new AducidSessionClient($GLOBALS["aim"]);
    $a->setFromRequest();
    if( $a->verify() ) {
        error_log("aducid ok");
        $db = new DDSDatabase();
        $username = $db->getUsernameByUdi( $a->getUserDatabaseIndex() );
        if( $username == "" ) {
            error_log("username error");
            $errormsg = "User unknown";
        } else {
            error_log("username \"" . $username . "\"");
            $_SESSION["username"] = $username;
            $_SESSION["usercn"] = $db->getCN($username);
        }
    } else {
        // failed
        error_log("aducid error");
        $errormsg = "Autentication failed!";
    }
    break;
case "logout":
    session_unset();
    break;
}

echo popupMessageWithParams("info.png","","nom d'utilisateur et mot de passe","Close","popupPassword");

if( $errormsg != "" ) {
    echo popupMessageWithParams("failed.png","Error",$errormsg,"Close");
    echo onLoadJs("popupModal");
}
echo body();
echo foot();

?>
