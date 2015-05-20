<?php
include_once "aducid/aducid.php";
include_once "config.php";
include_once "pageproducer.php";
include_once "database.php";
include_once "user.php";

aducidRequire(3.000);

session_name($GLOBALS["sessionname"]);
session_start();

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "";

$db = new DDSDatabase();
$user = new DDSUser($db);
$pp = new PageProducer($db,$user);

// error_log("Action: $action");
switch($action) {
    case "loginp": // password login
        if( ! $user->loginWithPassword($_REQUEST["username"],$_REQUEST["password"]) ) {
            $pp->errorMessage("Chybné uživatelské jméno nebo heslo");
        };
        break;
    case "startlogina":
        $a = new AducidSessionClient($GLOBALS["aim"]);
        $a->open($GLOBALS["myurl"] . "?action=logina");
        $a->invokePeig(
            AducidTransferMethod::REDIRECT,
            NULL
        );
        break;
    case "logina":
        $a = new AducidSessionClient($GLOBALS["aim"]);
        $a->setFromRequest();
        if( ! $user->loginWithADUCID($a) ) {
            $pp->errorMessage("Přihlášení ADUCIDem se nezdařilo.");
        }
        break;
    case "logout": // user logout
        $user->logout();
        break;
    case "reset": // reset database
        if( $db->isResetBlocked() ) {
            if( isset($_REQUEST["password"]) && ( $_REQUEST["password"] == $GLOBALS["resetpassword"] ) ){
                $db->createDatabase();
                $user->logout();
                session_unset();
            } else {
                $pp->errorMessage("Chybné heslo pro reset databáze");
            }
        } else {
            $db->createDatabase();
            $user->logout();
            session_unset();
        }
        $db->blockReset();
        break;
    case "startusingpeig":
        $a = new AducidSessionClient($GLOBALS["aim"]);
        $a->open($GLOBALS["myurl"] . "?action=startusingpeigcheck");
        $a->invokePeig(
            AducidTransferMethod::REDIRECT,
            NULL
        );
        break;
    case "startusingpeigcheck":
        $a = new AducidSessionClient($GLOBALS["aim"]);
        $a->setFromRequest();
        if( $a->verify() ) {
            if( $user->setUdi( $a->getUserDatabaseIndex() ) ) {
                $pp->infoMessage("Propojení s ADUCIDem proběhlo úspěšně.");
            } else {
                $pp->errorMessage("Tento PEIG patří někomu jinému. Nelze spárovat!");
            }
        } else {
            $pp->errorMessage("Propojení s ADUCIDem se nezdařilo.");
        }
        break;
    case "proofingotp":
        if( $user->loginWithOTP($_REQUEST["password"]) ) {
            session_unset();
            $_SESSION["informed"] = true;
            $_SESSION["proofingotp"] = $_REQUEST["password"];
            $a = new AducidSessionClient($GLOBALS["aim"]);
            $a->open($GLOBALS["myurl"] . "?action=proofingotpcheck");
            $a->invokePeig(
                AducidTransferMethod::REDIRECT,
                NULL
            );
        } else {
            $pp->errorMessage("Chybné jednorázové heslo");
        }
        break;
    case "proofingotpcheck":
        $a = new AducidSessionClient($GLOBALS["aim"]);
        if( $a->verify() ) {
            if( $user->loginWithOTP($_SESSION["proofingotp"]) ) {
                $user->setUdi( $a->getUserDatabaseIndex() );
                $user->save(); // FIXME
            } else {
                $pp->errorMessage( "Chybné jednorázové heslo!" );	    
            }
        } else {
            $pp->errorMessage( "Chyba ADUCID autentizace!" );
        }
        unset($_SESSION["proofingotp"]);
        break;
    case "proofingpwd":
        if( $user->loginWithPassword($_REQUEST["username"],$_REQUEST["password"]) ) {
            session_unset();
            $_SESSION["informed"] = true;
            $_SESSION["proofinguser"] = $_REQUEST["username"];
            $_SESSION["proofingpwd"] = $_REQUEST["password"];
            $a = new AducidSessionClient($GLOBALS["aim"]);
            $a->open($GLOBALS["myurl"] . "?action=proofingpwdcheck");
            $a->invokePeig(
                AducidTransferMethod::REDIRECT,
                NULL
            );
        } else {
            $pp->errorMessage( "Chybné jméno nebo heslo!" );
        }
        break;
    case "proofingpwdcheck":
        $a = new AducidSessionClient($GLOBALS["aim"]);
        if( $a->verify() ) {
            if( $user->loginWithPassword( $_SESSION["proofinguser"],$_SESSION["proofingpwd"] ) ) {
                $user->setUdi( $a->getUserDatabaseIndex() );
            } else {
                $pp->errorMessage( "Chybné jednorázové heslo!" );
            }
        } else {
            $pp->errorMessage( "Chyba ADUCID autentizace!" );
        }
        unset($_SESSION["proofinguser"]);
        unset($_SESSION["proofingpwd"]);
        break;
    case "signpoa":
        /*
        $params = array( "PaymentMessage" => $_REQUEST["text"], "UseLocalFactor" => "1" );
        $aducid = new AducidSessionClient($GLOBALS["aim"],"poa");
        $aducid->requestOperation(
            "exuse",
            "ConfirmTransaction",
            $params,
            array(
                "personalObjectName" => "DDS",
                "personalObjectTypeName" => "payment",
                "personalObjectAlgorithmName" => "PAYMENT"
            ),
            NULL,
            NULL,
            $GLOBALS["myurl"] ."?action=verifypoa"
        );
        $aducid->invokePeig(
            AducidTransferMethod::REDIRECT,
            NULL
        );
        */
        $aducid = new AducidSessionClient($GLOBALS["aim"],"poa");
        $aducid->confirmTextTransaction( $_REQUEST["text"], true, $GLOBALS["myurl"] ."?action=verifypoa" );  
        $aducid->invokePeig();
        break;
    case "verifypoa":
        /*
        $aducid = new AducidSessionClient($GLOBALS["aim"],"poa");
        $aducid->setFromRequest();
        $result = $aducid->getResult(AducidPSLAttributesSet::ALL);
        $poaResult = false;
        if( $aducid->verify() && ( $aducid->getUserDatabaseIndex() == $user->getUdi() ) ) {
            // error_log("verify ok");
            $result = $aducid->getResult(AducidPSLAttributesSet::ALL);
            $po = $result["personalObject"];
            $poa = $po->personalObjectAttribute;
            $poaSignature = $poa["PaymentSignature"];
            $poaText = $poa["PaymentMessage"];
            if( $poa["Return_Status"] == "ConfirmedByUser" ) {
                $poaResult = "OK";
                // is local factor OK ?
                if( isset( $poa["UseLocalFactor"] ) ) {
                    $lf = $poa["UseLocalFactor"];
                    if( ($lf[0] == 1) && ($lf[1] != "OK") ) {
                        $pp->errorMessage( "Chybně zadané tajemství" );
                    } else {
                        $poaResult = true;
                    }
                } else {
                    $pp->errorMessage( "Nebylo použito tajemství" );
                }
            }
            if( $poa["Return_Status"] == "RefusedByUser" ) {
                $pp->errorMessage( "Zamítnuto uživatelem" );
            }
        } else {
            $pp->errorMessage( "Chyba transakce" );
        }
        $pp->successMessage("<h2>Podepsaná zpráva<h2><h3>Text zprávy</h3>\n" .
        "<p>" .$poaText . "</p>\n".
        "<h3>Podpis</h3>\n<p>" . wordwrap($poaSignature,30,"\n",true) ) . "</p>\n";
        */
        $aducid = new AducidSessionClient($GLOBALS["aim"],"poa");
        $aducid->setFromRequest();
        $transaction = $aducid->verifyTransaction();
        //error_log(var_export($transaction,true));
        if( $transaction["result"] ) {
            $pp->successMessage("<h2>Podepsaná zpráva<h2><h3>Text zprávy</h3>\n" .
                                "<p>" .$transaction["PaymentMessage"] . "</p>\n".
                                "<h3>Podpis</h3>\n<p>" .
                                wordwrap($transaction["PaymentSignature"],30,"\n",true) ) . "</p>\n";
        } else {
            if( $transaction["Return_Status"] == "RefusedByUser" ) {
                $pp->errorMessage( "Zamítnuto uživatelem" );
            } elseif( ! isset( $transaction["UsePersonalFactor"] ) ) {
                $pp->errorMessage( "Nebylo použito tajemství" );
            } elseif( $transaction["UsePersonalFactor"] != "OK" ) {
                $pp->errorMessage( "Chybně zadané tajemství" );
            } else {
                $pp->errorMessage( "Nečekaná chyba transakce" );
            }
        }
        break;
    case "initpersonalfactor":
        $aducid = new AducidClient($GLOBALS["aim"]);
        $aducid->initPersonalFactor($GLOBALS["myurl"]);
        $aducid->invokePeig();
        break;
    case "initpayment":
        $aducid = new AducidClient($GLOBALS["aim"]);
        $aducid->initPayment(true,$GLOBALS["myurl"]);
        $aducid->invokePeig();
        break;
}

if( $user->loggedIn() ) {
    if( $user->registered()  ) { 
        print $pp->userPage();
    } else {
        print $pp->registrationPage();
    }
} else {
    print $pp->loginPage();
}

?>