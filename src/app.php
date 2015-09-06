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


include_once "aducid/aducid.php";
include_once "config.php";
include_once "database.php";

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
        "<script src=\"responderJS.php?js=dds\"></script>\n".
        "<!-- =================================== head end -->\n";
    // add modal dialogs
    /*
    $html .= $this->modalDialogResetDb();
    $html .= $this->modalDialogResetDbPassword();
    $html .= $this->modalInfoDialog();
    $html .= $this->popupMessage();
    */
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

function popupMessageWithParams($image,$title,$text,$buttontext) {
    return
        "\n<!-- =================================== popupMessage -->\n".
        "<div id=\"popupModal\" class=\"modal fade\">\n".
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

function onLoadJs($popup) {
    return
        "<script type=\"text/javascript\">\n".
        "    $(window).load(function(){\n".
        "        $('#" . $popup ."').modal('show');\n".
        "    });\n".
        "</script>\n";
}

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
          "    <div class=\"center-block eighty\"><img src=\"app-menu.jpg\" width=\"100%\"></div>\n".
          "  </div>\n".
          "  <div class=\"row\">\n" .
          "    <div class=\"center-block eighty\">\n" .
          "      <p>&nbsp;</p>\n" .
          "      <p>&nbsp;</p>\n" .
          "      <p class=\"text-right\">\n" .
          "        <a href=\"" . AducidClient::currentURL() . "?action=login\"><img src=\"app-login.jpg\"></a>\n" .
          "      </p>\n".
          "    </div>\n".
          "  </div>\n".
          "</div>\n";
    return $html;
}

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
          "      <p>&nbsp;</p>\n" .
          "    </div>\n" .
          "  </div>\n".
          "  <div class=\"row\">\n" .
          "    <p class=\"text-right\">\n" .
          "    <a href=\"" . AducidClient::currentURL() . "?action=logout\"><img src=\"app-logout.jpg\"></a>\n" .
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

/*
 *
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

if( $errormsg != "" ) {
    echo popupMessageWithParams("failed.png","Error",$errormsg,"Close");
    echo onLoadJs("popupModal");
}
echo body();
echo foot();

?>
