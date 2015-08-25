<?php

/**
   login page structure
   +----------------------------------------------------------+
   | ddsHeaderNotLoggedIn = czechPoint Portal verejnespravy   |
   +----------------------------------------------------------+
   | ddsBanner - banner with QR code                          |
   +----------------------------------------------------------+
   | ddsNavigation - prihlaseni jmenem a heslem               |
   +----------------------------------------------------------+
   | +----------------------------+ +-----------------------+ |
   | | loginForm                  | | loginInfo             | |
   | +----------------------------+ +-----------------------+ |
   +----------------------------------------------------------+
   | ddsFoot - reset db                                       |
   +----------------------------------------------------------+

   user page structure
   +----------------------------------------------------------+
   | ddsHeaderLoggedIn                                        |
   +----------------------------------------------------------+
   | ddsBannerLoggedIn                                        |
   +----------------------------------------------------------+
   | ddsNavigationLoggedIn - zpravy, zacit pouzivat peig      |
   +----------------------------------------------------------+
   | +----------------------------+ +-----------------------+ |
   | | signatureForm              | | loginWithPasswordInfo | |
   | +----------------------------+ +-----------------------+ |
   +----------------------------------------------------------+
   | ddsFoot - reset db                                       |
   +----------------------------------------------------------+
  
   registration page structure
   +----------------------------------------------------------+
   | ddsHeaderRegistration                                    |
   +----------------------------------------------------------+
   | +----------------------------+ +-----------------------+ |
   | | registrationForm           | | registrationFormInfo  | |
   | +----------------------------+ +-----------------------+ |
   +----------------------------------------------------------+
   | ddsFoot - reset db                                       |
   +----------------------------------------------------------+

*/


class PageProducer {
    protected $database;
    protected $user;
    protected $errormsg;
    protected $infomsg;
    protected $successmsg;

    function __construct($database,$user) {
        $this->database = $database;
        $this->user = $user;
        $this->errormsg = "";
        $this->infomsg = "";
        $this->succesmsg = "";
    }
    /**
     * Hlavicka stranky
     *
     * Pokud je nastaven parametr $refresh, provede stranka po dvou sekundach
     * presmerovani podle tohoto url.
     *
     */
    function head($refresh="") {
        $html =
            "<!DOCTYPE html>\n<html>\n".
            "<head>\n".
            "   <meta charset=\"utf-8\">\n".
            "   <meta http-equiv=\"X-UA-Compatible\" content=\"IE=9\" />\n";
        if( $refresh != "" ) {
            $html .= "   <meta http-equiv=\"refresh\" content=\"2;url=".$refresh."\" />\n";
        }
        $html .=
            "   <title>Datové schránky</title>\n".
            "   <link rel=\"stylesheet\" href=\"css/bootstrap.min.css\">\n".
            "   <link rel=\"stylesheet\" href=\"dds.css\">\n".
            "</head>\n".
            "<body>\n".
            "<script src=\"js/jquery-1.10.1.min.js\"></script>\n".
            "<script src=\"js/bootstrap.js\"></script>\n".
            "<script src=\"responderJS.php?js=dds\"></script>\n".
            "<!-- script src=\"aducid-functions.js\"></script -->\n".
            "<!-- =================================== head end -->\n";
        // add modal dialogs
        $html .= $this->modalDialogResetDb();
        $html .= $this->modalDialogResetDbPassword();
        $html .= $this->modalInfoDialog();
        $html .= $this->popupMessage();
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
     * modal dialog for reset db
     */
    function modalDialogResetDb() {
        $html = 
            "\n<!-- =================================== modalResetDialog -->\n".
            "<div id=\"resetDbModal\" class=\"modal fade\">\n".
            "    <div class=\"modal-dialog\">\n".
            "        <div class=\"modal-content\">\n".
            "            <div class=\"modal-header\">\n".
            "                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n".
            "                <h4 class=\"modal-title\">Resetovat databázi?</h4>\n".
            "            </div>\n".
            "            <div class=\"modal-body\">\n".
            "                <p>Opravdu chcete resetovat databázi uživatelů?</p>\n".
            "            </div>\n".
            "            <div class=\"modal-footer\">\n".
            "                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Zrušit</button>\n".
            "                <a href=\"?action=reset\" class=\"btn btn-default\">Resetovat</a>\n".
            "            </div>\n".
            "        </div>\n".
            "    </div>\n".
            "</div>\n";
        return $html;
    }
    /**
     * modal dialog for reset db with password
     */
    function modalDialogResetDbPassword() {
        $html = 
            "\n<!-- =================================== modalResetDialog -->\n".
            "<div id=\"resetDbModalPassword\" class=\"modal fade\">\n".
            "    <div class=\"modal-dialog\">\n".
            "        <div class=\"modal-content\">\n".
            "            <form action=\"index.php\" method=\"POST\"><input type=\"hidden\" name=\"action\" value=\"reset\" >\n".
            "            <div class=\"modal-header\">\n".
            "                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n".
            "                <h4 class=\"modal-title\">Resetovat databázi?</h4>\n".
            "            </div>\n".
            "            <div class=\"modal-body\">\n".
            "                <p>Zadejte heslo pro reset databáze uživatelů:</p>\n".
            "                <input type=\"password\" width=\"100%\" name=\"password\" class=\"form-control highlight\" />\n".
            "            </div>\n".
            "            <div class=\"modal-footer\">\n".
            "                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Zrušit</button>\n".
            "                <input type=\"submit\" class=\"btn btn-default\" name=\"submit\" value=\"Resetovat\">\n".
            "            </div>\n".
            "            </form>\n".
            "        </div>\n".
            "    </div>\n".
            "</div>\n";
        return $html;
    }
    /**
     * Funkce vrátí html které vyvolá modální dialog stránky po té, co je dokončeno načítání (onLoad).
     */
    function modalInfoDialog() {
        $html = 
            "\n<!-- =================================== modalInfoDialog -->\n".
            "<div class=\"modal fade\" id=\"modalInfoDialog\">\n".
            "    <div class=\"modal-dialog\">\n".
            "        <div class=\"modal-content\">\n".
            "            <div class=\"modal-header\">\n".
            "                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\" id=\"modalX\">&times;</button>\n".
            "                <h4 class=\"modal-title\"><img src=\"img/info.png\">&nbsp;<img src=\"img/aducidinfo.png\"></h4>\n".
            "            </div>\n".
            "            <div class=\"modal-body\">\n".
            "                <p>\n".
            "                DEMO DATOVÉ SCHRÁNKY ukazuje některé možnosti použití autentizace ADUCID pro datové schránky.\n".
            "                V této příkladové aplikaci jsou pro demonstrační účely předdefinovaní 4 uživatelé. Dva stávající\n".
            "                a dva noví.\n".
            "                </p>\n".
            "                \n".
            "                <h4>A. stávající uživatelé</h4>\n".
            "                \n".
            "                <p>\n".
            "                Aktivní uživatelé datových schránek, ti kteří již svoji schránku používají a\n".
            "                přihlašují se do ní některým z dnes dostupných způsobů, mohou začít používat automatickou identitu PEIG.\n".
            "                V našem příkladu se stávající uživatelé přihlašují jménem a heslem. Pro demonstrační účely jsme zvolili\n".
            "                jednoduchá přihlašovací jména a hesla.\n".
            "                </p>\n".
            "                <p>\n".
            "                Po přihlášení jménem a heslem, nabídnou DEMO DATOVÉ SCHRÁNKY stávajícím uživatelům možnost jednoduše\n".
            "                vytvořit automatickou identitu PEIG a spárovat ji se svým účtem. Po tom, co si uživatelé automatickou identitu PEIG\n".
            "                vytvoří, se jim příště už tato nabídka zobrazovat nebude.\n".
            "                </p>\n".
            "                \n".
            "                <h4>B. noví uživatelé</h4>\n".
            "                \n".
            "                <p>\n".
            "                Představujeme také jeden ze způsobů, jak si svoji automatickou identitu PEIG mohou vytvořit uživatelé, kteří s datovými\n".
            "                schránkami dosud nepracovali. Implementovaný scénář předpokládá, že tito uživatelé svoji fyzickou identitu předem\n".
            "                ověřili na příslušném místě (např. CzechPOINT). Na základě toho jim byl vytvořen uživatelský účet. K tomuto účtu získali jednorázové\n".
            "                identifikační heslo. Pro demonstrační účely jsou nastavená jednorázová identifikační hesla zvolena jednoduchá.\n".
            "                </p>\n".
            "                <p>\n".
            "                Svoji automatickou identitu PEIG mohou noví uživatelé aktivovat buď přímo na zařízení, na kterém mají\n".
            "                DEMO DATOVÉ SCHRÁNKY spuštěny, nebo na svém mobilním telefonu.\n".
            "                </p>\n".
            "                \n".
            "                <h4>PEIG</h4>\n".
            "                <p>\n".
            "                Pro to, aby autentizace automatickou identitou PEIG mohla fungovat, je nutné na příslušné zařízení (počítač, mobilní telefon)\n".
            "                předem stáhnout a nainstalovat PEIG (personal electronic identity guardian). Příslušný typ PEIG je pro\n".
            "                jednotlivé platformy zdarma ke stažení. Návod je k dispozici na <a href=\"http://www.aducid.com/support/\">webových stránkách ADUCID</a>.\n".
            "                </p>\n".
            "                \n".
            "            </div>\n".
            "            <div class=\"modal-footer\">\n".
            "                <!-- button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Pokračovat</button -->\n".
            "                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" id=\"modalMessageContinue\">Pokračovat</button>\n".
            "            </div>\n".
            "        </div><!-- /.modal-content -->\n".
            "    </div><!-- /.modal-dialog -->\n".
            "</div><!-- /.modal -->\n";
        if( ! isset( $_SESSION["informed"] ) ) {
            $html .= $this->onLoadJs("modalInfoDialog") .
                "<script type=\"text/javascript\">\n".
                "    $('#modalInfoDialog').on('shown.bs.modal', function () {\n".
                "        $('#modalX').focus();\n".
                "    })\n".
                "    $('#modalInfoDialog').on('hidden.bs.modal', function () {\n".
                "        $('#username').focus();\n".
                "    })\n".
                "</script>\n";
          $_SESSION["informed"] = true;
        }
        return $html;
    }

    /**
     * Defaultni stranka neprihlaseneho uzivatele
     */
    function ddsHeaderNotLoggedIn() {
        return
            "\n<!-- =================================== ddsHeaderNotLoggedIn -->\n".
            "  <div class=\"row checkpointbg\">\n".
            "    <b>DEMONSTRACE PŘIHLAŠOVÁNÍ POMOCÍ ADUCID</b>\n".
            "  </div>\n";
    }
    function ddsBanner() {
        return
            "\n<!-- =================================== ddsBanner -->\n".
            "  <div class=\"row headerbg\">\n".
            "    <div class=\"pull-right\">\n".
            "      <table cellpadding=\"10px\">\n".
            "        <tr>\n".
            "          <td align=\"right\">\n".
            "            <div class=\"whitelink\">\n".
            "              <font size=\"+2\"><b>Moje datová<br/>schránka<br/></b>\n".
            "              <a href=\"index.php?action=startlogina\" class=\"whitelink highlightyellow\"><b><font color=\"#de5209\">PEIG login</font></b></a>\n".
            "            </font>\n".
            "            </div>\n".
            "          </td>\n".
            "          <td>\n".
            "    	    <img id=\"aducidqrcode\" SRC=\"img/qrCode.png\" width=\"120px\">\n".
            "          </td>\n".
            "        </tr>\n".
            "      </table>\n".
            "    </div>\n".
            "  </div>\n" .
            "  <script src=\"responderJS.php?js=aducidWorker\"></script>\n";
    }
    function ddsNavigation() {
        return
            "\n  <!-- =================================== ddsNavigation -->\n".
            "  <div class=\"row\">\n".
            "    <img src=\"img/navigation.jpg\" width=\"100%\">\n".
            "  </div>\n".
            "  <div class=\"row\">\n".
            "    <p></p>\n".
            "  </div>\n";
    }
    function loginForm() {
        return
            "\n  <!-- =================================== loginForm -->\n".
            "  <div class=\"col-md-7 col-xs-7\">\n".
            "    <br/>\n".
            "    <br/>\n".
            "    <form width=\"100%\" method=\"POST\" action=\"\">\n".
            "    <div class=\"form-group\">\n".
            "    <input type=\"hidden\" name=\"action\" value=\"loginp\" />\n".
            "    <table width=\"100%\" style=\"table-layout: fixed;\" cellpadding=\"4\">\n".
            "      <tr>\n".
            "        <td align=\"right\">Uživatelské jméno</td>\n".
            "        <td><input type=\"text\" width=\"100%\" name=\"username\" class=\"form-control highlight\" id=\"username\" autofocus /></td>\n".
            "      </tr>\n".
            "      <tr>\n".
            "        <td align=\"right\">Heslo</td>\n".
            "        <td><input type=\"password\" width=\"100%\" name=\"password\" class=\"form-control highlight\" /></td>\n".
            "        <td>\n".
            "          <table>\n".
            "            <tr>\n".
            "              <td><div class=\"btn btn-default\"><img src=\"img/keyboard.png\"></div></td>\n".
            "              <td><font size=\"-2\">Otevřít grafickou<br/>klávesnici</font></td>\n".
            "            </tr>\n".
            "          </table>\n".
            "        </td>\n".
            "      </tr>\n".
            "      <tr>\n".
            "        <td align=\"right\">&nbsp;</td>\n".
            "        <td><img src=\"img/captcha.png\" width=\"100%\"  height=\"65px\"/></td>\n".
            "        <td>\n".
            "          <table>\n".
            "          <tr>\n".
            "            <td><div class=\"btn btn-default\"><img src=\"img/sound.png\"></div></td>\n".
            "            <td><font size=\"-2\">Přehrát kód</font></td>\n".
            "          </tr>\n".
            "          <tr>\n".
            "            <td><div class=\"btn btn-default\"><img src=\"img/reload.png\"></div></td>\n".
            "            <td><font size=\"-2\">Vytvořit nový kód</font></td>\n".
            "          </tr>\n".          
            "          </table>\n".
            "        </td>\n".
            "      </tr>\n".
            "      <tr>\n".
            "        <td align=\"right\">Opište kód z obrázku</td>\n".
            "        <td><input type=\"text\" width=\"100%\" name=\"captcha\" class=\"form-control\" value=\"44653\" disabled=\"1\" /></td>\n".
            "      </tr>\n".
            "      <tr>\n".
            "        <td>&nbsp;</td>\n".
            "        <td><input type=\"submit\" name=\"submit\" value=\"Přihlásit\" width=\"100%\" class=\"form-control highlightbutton\" /></td>\n".
            "      </tr>\n".
            "    </table>\n".
            "    </div>\n".
            "    </form>\n".
            /* "    <p><br/><br/><br/><br/><br/>" . reset_db("NONE",0) . "<br/><br/></p> " */
            "  </div>\n";
    }
    function loginInfo() {
        return
            "\n  <!-- =================================== loginInfo -->\n".
            "  <div class=\"col-md-5 col-xs-5\">\n".
            "    <br/>\n".
            "    <img src=\"img/info.png\">&nbsp;<img src=\"img/aducidinfo.png\">\n".
            "    <br/>\n".
            "    <br/>\n".
            "    <p>\n".
            "    Uživatel se do DEMO DATOVÝCH SCHRÁNEK může přihlásit obvyklým způsobem pomocí jména a hesla.\n".
            "    Pokud má na svém zařízení instalovaný PEIG, může k přihlášení použít automatickou identitu PEIG.\n".
            "    Má-li PEIG nainstalovaný přímo na zařízení, ze kterého se chce přihlásit, klikne na tlačítko\n".
            "    PEIG login. Chce-li se přihlásit z jiného počítače nebo tabletu, vyfotí QR kód v pravém horním\n".
            "    rohu pomocí PEIG nainstalovaného na svém telefonu.\n".
            "    </p>\n".
            "    <p></p>\n".
            "    <table>\n".
            "      <tr>\n".
            "        <td style=\"padding-right:10px;\"><b>stávající uživatel</b></td>\n".
            "        <td style=\"padding-right:10px;\"><b>jméno pro přihlášení</b></td>\n".
            "        <td><b>heslo</b></td>\n".
            "      </tr>\n".
            "      <tr>\n".
            "	     <td style=\"padding-right:10px;\">". ( $this->database->getCN(0) ) ."</td>\n".
            "        <td style=\"padding-right:10px;\">". ( $this->database->getUsername(0) )."</td>\n".
            "        <td>". ( $this->database->getPassword(0) ) ."</td>\n".
            "      </tr>\n".
            "      <tr>\n".
            "	     <td style=\"padding-right:10px;\">". ( $this->database->getCN(1) )."</td>\n".
            "        <td style=\"padding-right:10px;\">". ( $this->database->getUsername(1) )."</td>\n".
            "        <td>". ( $this->database->getPassword(1) ) ."</td>\n".
            "      </tr>\n".
            "    </table>\n".
            "    <br />\n".
            "    <br />\n".
            "\n".
            "  </div>\n";
    }
    function loginPage() {
        $html =
            "\n<!-- =================================== loginPage -->\n".
            "\n".
            "<div class=\"container mycontainer\">\n".
            $this->ddsHeaderNotLoggedIn() .
            $this->ddsBanner() .
            $this->ddsNavigation() .
            "  <div class=\"row\">\n".
            $this->loginForm() .
            $this->loginInfo() .
            "  </div>\n".
            $this->ddsFoot() .
            "</div> <!-- container -->\n";
        return $this->head() . $html . $this->foot();
    }    
    function ddsHeaderLoggedIn() {
        return
            "\n  <!-- =================================== ddsHeaderLoggedIn -->\n".
            "  <div class=\"row\">\n".
            "    <div class=\"col-md-10 col-xs-10 orangebg\">\n".
            "        <b>DEMONSTRACE PŘIHLAŠOVÁNÍ POMOCÍ ADUCID</b>\n".
            "    </div>\n".
            "    <div class=\"col-md-2 col-xs-2 bluebg\">\n".
            "      <center>\n".
            "      <a href=\"?action=logout\" class=\"bluebg logout\">".
            "<b>&nbsp;&nbsp;&nbsp;&nbsp;Odhlásit&nbsp;&nbsp;&nbsp;&nbsp;</b></a>\n".
            "      </center>\n".
            "    </div>\n".
            "  </div>\n";        
    }
    function ddsBannerLoggedIn() {
        $html =
            "\n  <!-- =================================== ddsBannerLoggedIn -->\n".
            "  <div class=\"row\">\n".
            "    <div class=\"col-md-3 col-xs-3\">\n".
            "      <img src=\"img/logo.png\" width=\"180px\">\n".
            "    </div>\n".
            "    <div class=\"col-md-3 col-xs-3\">\n".
            "      <br/>Přihlášený uživatel:<br/><b><font size=\"+2\" color=\"#de5209\" >".($this->user->commonName())."</font></b>\n".
            "    </div>\n".
            "    <div class=\"col-md-3 col-xs-3\">\n".
            "      <br/>Oprávnění:<br/><b>číst zprávy, číst zprávy do vlastních rukou, posílat zprávy, zoprazovat seznamy a dodejky</b>\n".
            "    </div>\n".
            "    <div class=\"col-md-3 col-xs-3\">\n".
            "      ";
        if(  $this->user->hasPeig() ) {
            $html .= 
                "      <br/>Doplňkové služby:<br/><b>Aktivní ADUCID přihlášení</b>\n";
        } else {
            $html .=
                "      <br/>Doplňkové služby:<br/><b>Žádné doplňkové služby.</b>\n";
        }
        $html .=
            "    </div>\n".
            "  </div>";
        return $html;
    }
    function ddsNavigationLoggedIn() {
        return
            "\n  <!-- =================================== ddsNavigationLoggedIn -->\n".
            "   <div class=\"row internalnavigation\">\n".
            "    <div class=\"col-md-9 col-xs-9\">\n".
            "    </div>\n".
            "    <div class=\"col-md-3 col-xs-3\">\n".
            ( $this->user->hasPeig() ? "" : (
            "      <a href=\"index.php?action=startusingpeig\" class=\"btn btn-default\">\n".
            "        <div style=\"display: inline-block; height: 72px; width: 180px; padding-top:15px\">\n".
            "        <font size=\"+1\" color=\"#505050\"><b>Začít používat<br/>\n".
            "        PEIG</b></font>\n".
            "        </div>\n".
            "      </a>\n" ) ) .
            "    </div>\n".
            "  </div>\n";
    }
    function signatureForm() {
        $html =
            "\n    <!-- =================================== signatureForm -->\n".
            "    <div class=\"col-md-8 col-xs-8\">\n";
        if(  $this->user->hasPeig() ) {
            $html .=
                "        <div class=\"jumbotron\" id=\"signframe\" onClick=\"createSignForm('" .
                $this->user->commonName() ."')\">\n".
                "            <h2>Zadat a podepsat zprávu</h2>\n".
                "    	     <p>Lze zadat textovou zprávu, kterou můžete pomocí ADUCID\n".
                "    	     elektronicky podepsat svým mobilním telefonem.\n".
                "    	     </p>\n".
                "        </div>\n";
        } else {
            $html .=
                "        <div class=\"jumbotron\" id=\"signframe\">\n".
                "           <h2>Zprávy nelze zatím podepisovat</h2>\n".
                "    	    <p>Uživatelé, kteří mají PEIG mohou zde zadat textovou zprávu,\n".
                "           kterou můžou pomocí ADUCID\n".
                "    	    elektronicky podepsat svým mobilním telefonem.\n".
                "    	    </p>\n".
                "        </div>\n";
        }
        $html .= "    </div>\n";
        return $html;
    }
    function loginWithPasswordInfo() {
        return
            "\n    <!-- =================================== loginWithPasswordInfo -->\n".
            "    <div class=\"col-md-4 col-xs-4\">\n".
            "      <br/>\n".
            "      <img src=\"img/info.png\">&nbsp;<img src=\"img/aducidinfo.png\">\n".
            "      <br/><br/>\n".
            "
            <p>
  	        Uživatel je přihlášen do datových schránek pomocí jména a hesla.
	        Může vytvořit automatickou identitu PEIG a propojit ji se svým účtem.
            To zajistí kliknutím do tlačítka “Začít používat PEIG”. 
	        </p>
            <p>
	        Pozn.: Aplikace DEMO DATOVÉ SCHRÁNKY není simulací reálných datových schránek,
	        pouze demonstruje možnosti použití automatické identity PEIG.
	        Ostatní funkce datových schránek nejsou demonstrovány.
	        </p>\n
	        <br />\n
	        <br />\n".
            "    </div>\n";
    }
    function userPage() {
        $html = "";
        $html .=
            "\n<!-- =================================== userPage -->\n".
            "\n".
            "<div class=\"container mycontainer\">\n".
            $this->ddsHeaderLoggedIn() .
            $this->ddsBannerLoggedIn() .
            $this->ddsNavigationLoggedIn() .
            "  <p></p>\n".
            "  <div class=\"row\">\n".
            $this->signatureForm() .
            $this->loginWithPasswordInfo() .
            "  </div>\n".
            $this->ddsFoot() .
            "</div> <!-- container -->\n";
        /*
        $html .= query_reset();
        if( ! isset($_SESSION["informed"] ) ) {
            $html .= info_dialog();
            $_SESSION["informed"] = 1;
        }
        */
        return $this->head() . $html . $this->foot();
    }
    /**
     * formular pro reset databaze
     */
    function ddsFoot($list="ALL", $header=1) {
        $html =
            "\n  <!-- =================================== ddsFoot -->\n".
            "  <div class=\"row ddsfooter\">\n".
            "    <div class=\"ddsfooter\">\n".
            "    <a href=\"index.php?action=reset\" class=\"graylink\" onClick=\"return invokeResetDbModal()\"  title=\"Reset aplikace do počátečního stavu\" >\n".
            "    Pro demonstrační účely lze vymazat identity".
            "    </a>&nbsp;\n".
            "    </div>\n".
            "  </div>";
            return $html;
    }
    function onLoadJs($popup) {
        return
            "<script type=\"text/javascript\">\n".
            "    $(window).load(function(){\n".
            "        $('#" . $popup ."').modal('show');\n".
            "    });\n".
            "</script>\n";
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
    function popupMessage() {
        if( $this->errormsg != "" ) {
            $html = $this->popupMessageWithParams("failed.png","Chyba",$this->errormsg,"Zavřít").
                $this->onLoadJs("popupModal");
            $this->errormsg = "";
            $this->infomsg = "";
            $this->succesmsg = "";
            return $html;
        }
        if( $this->successmsg != "" ) {
            $html = $this->popupMessageWithParams("ok.png","",$this->successmsg,"Ok").
                $this->onLoadJs("popupModal");
            $this->errormsg = "";
            $this->infomsg = "";
            $this->succesmsg = "";
            return $html;
        }
        if( $this->infomsg != "" ) {
            $html = $this->popupMessageWithParams("info.png","",$this->infomsg,"Ok").
                $this->onLoadJs("popupModal");
            $this->errormsg = "";
            $this->infomsg = "";
            $this->succesmsg = "";
            return $html;
        }
        return "";
    }
    function errorMessage($text) {
        $this->errormsg = $text;
    }
    function infoMessage($text) {
        $this->infomsg = $text;
    }
    function successMessage($text) {
        $this->successmsg = $text;
    }
    function ddsHeaderRegistration() {
        return
            "\n  <!-- =================================== ddsHeaderRegistration -->\n".
            "  <div class=\"row\">\n".
            "    <div class=\"col-md-10 col-xs-10 orangebg\">\n".
            "      <b>DEMONSTRACE PŘIHLAŠOVÁNÍ POMOCÍ ADUCID</b>\n".
            "    </div>\n".
            "    <div class=\"col-md-2  col-xs-2 bluebg\">\n".
            "      <center>\n".
            "        <a href=\"?action=logout\" class=\"bluebg logout\"><b>&nbsp;&nbsp;&nbsp;&nbsp;Zpět&nbsp;&nbsp;&nbsp;&nbsp;</b></a>\n".
            "      </center>\n".
            "    </div>\n".
            "  </div>\n";
    }
    function registrationForm() {
        return
            "\n  <!-- =================================== registrationForm -->\n".
            "  <div class=\"col-md-7 col-xs-7\">\n".
            "    <br/>\n".
            "    <h4>Propojení nového uživatele s identitou PEIG</h4>\n".
            "    <br/>\n".
            "    <form width=\"100%\" method=\"POST\" action=\"index.php\">\n".
            "    <div class=\"form-group\">\n".
            "    <input type=\"hidden\" name=\"action\" value=\"proofingotp\" />\n".
            "    <table width=\"100%\" style=\"table-layout: fixed;\">\n".
            "      <tr>\n".
            "        <td align=\"right\">Jednorázové heslo</td>\n".
            "        <td><input type=\"password\" width=\"100%\" name=\"password\" class=\"form-control highlight\" autofocus /></td>\n".
            "        <td>\n".
            "          <table>\n".
            "            <tr>\n".
            "              <td><div class=\"btn btn-default\"><img src=\"img/keyboard.png\"></div></td>\n".
            "              <td><font size=\"-2\">Otevřít grafickou<br/>klávesnici</font></td>\n".
            "            </tr>\n".
            "          </table>\n".
            "        </td>\n".
            "      </tr>\n".
            "      <tr>\n".
            "        <td>&nbsp;</td>\n".
            "        <td><input type=\"submit\" name=\"submit\" value=\"Propojit\" width=\"100%\" class=\"form-control highlightbutton\" /></td>\n".
            "      </tr>\n".
            "    </table>\n".
            "    </div>\n".
            "    </form>\n".
            "    <br/>\n".
            "    <h4>Propojení stávajícího uživatele s identitou PEIG</h4>\n".
            "    <br/>\n".
            "    <form width=\"100%\" method=\"POST\" action=\"index.php\">\n".
            "    <div class=\"form-group\">\n".
            "      <input type=\"hidden\" name=\"action\" value=\"proofingpwd\" />\n".
            "      <table width=\"100%\" style=\"table-layout: fixed;\" cellpadding=\"4\">\n".
            "        <tr>\n".
            "          <td align=\"right\">Uživatelské jméno</td>\n".
            "          <td><input type=\"text\" width=\"100%\" name=\"username\" class=\"form-control highlight\" id=\"username\" autofocus /></td>\n".
            "	     </tr>\n".
            "        <tr>\n".
            "          <td align=\"right\">Heslo</td>\n".
            "          <td><input type=\"password\" width=\"100%\" name=\"password\" class=\"form-control highlight\" /></td>\n".
            "          <td>\n".
            "            <table>\n".
            "              <tr>\n".
            "                <td><div class=\"btn btn-default\"><img src=\"img/keyboard.png\"></div></td>\n".
            "                <td><font size=\"-2\">Otevřít grafickou<br/>klávesnici</font></td>\n".
            "              </tr>\n". 
            "            </table>\n".
            "          </td>\n".
            "        </tr>\n".
            "        <tr>\n".
            "          <td align=\"right\">&nbsp;</td>\n".
            "          <td><img src=\"img/captcha.png\" width=\"100%\"  height=\"65px\"/></td>\n".
            "          <td>\n".
            "            <table>\n".
            "              <tr>\n".
            "                <td><div class=\"btn btn-default\"><img src=\"img/sound.png\"></div></td>\n".
            "                <td><font size=\"-2\">Přehrát kód</font></td>\n".
            "              </tr>\n".
            "              <tr>\n".
            "                <td><div class=\"btn btn-default\"><img src=\"img/reload.png\"></div></td>\n".
            "                <td><font size=\"-2\">Vytvořit nový kód</font></td>\n".
            "              </tr>\n".
            "            </table>\n".
            "          </td>\n".
            "        </tr>\n".
            "        <tr>\n".
            "          <td align=\"right\">Opište kód z obrázku</td>\n".
            "          <td><input type=\"text\" width=\"100%\" name=\"captcha\" class=\"form-control\" value=\"44653\" disabled=\"1\" /></td>\n".
            "        </tr>\n".
            "        <tr>\n".
            "          <td>&nbsp;</td>\n".
            "          <td><input type=\"submit\" name=\"submit\" value=\"Propojit\" width=\"100%\" class=\"form-control highlightbutton\" /></td>\n".
            "        </tr>\n".
            "      </table>\n".
            "    </div>\n".
            "    </form>\n".
            "    <br />\n".
            "    <br />\n".
            "  </div>\n";
    }
    function registrationFormInfo() {
        return
            "\n  <!-- =================================== registrationFormInfo -->\n".
            "  <div class=\"col-md-5 col-xs-5\">\n".
            "    <br/>\n".
            "    <img src=\"img/info.png\">&nbsp;<img src=\"img/aducidinfo.png\">\n".
            "    <br/>\n".
            "    <br/>\n".
            "    <p>\n".
            "      Aplikace DEMO DATOVÝCH SCHRÁNEK zatím nezná Vaši identitu PEIG.\n".
            "      Na této stránce můžete propojit identitu PEIG s údaji uživatele. \n".
            "      Nový uživatel použijte jednorázové heslo, které mu bylo vygenerováno systémem.\n".
            "    </p>\n".
            "    <table>\n".
            "      <tr>\n".
            "        <td style=\"padding-right:10px;\"><b>nový uživatel</b></td>\n".
            "        <td><b>jednorázové identifikační heslo</b></td>\n".
            "      </tr>\n".
            "      <tr>\n".
            "	     <td style=\"padding-right:10px;\">".$this->database->getCN(2)."</td>\n".
            "        <td>".$this->database->getOTP(2)."</td>\n".
            "      </tr>\n".
            "      <tr>\n".
            "	     <td style=\"padding-right:10px;\">".$this->database->getCN(3)."</td>\n".
            "        <td>".$this->database->getOTP(3)."</td>\n".
            "      </tr>\n".
            "    </table>\n".
            "    <p>\n".
            "      Stávající uživatel zadá stávající platné jméno a heslo.\n".
            "    </p>\n".
            "    <table>\n".
            "        <tr>\n".
            "          <td style=\"padding-right:10px;\"><b>stávající uživatel</b></td>\n".
            "          <td style=\"padding-right:10px;\"><b>jméno pro přihlášení</b></td>\n".
            "          <td><b>heslo</b></td>\n".
            "        </tr>\n".
            "        <tr>\n".
            "	       <td style=\"padding-right:10px;\">".$this->database->getCN(0)."</td>\n".
            "          <td style=\"padding-right:10px;\">".$this->database->getUsername(0)."</td>\n".
            "          <td>".$this->database->getPassword(0)."</td>\n".
            "        </tr>\n".
            "        <tr>\n".
            "	       <td style=\"padding-right:10px;\">".$this->database->getCN(1)."</td>\n".
            "          <td style=\"padding-right:10px;\">".$this->database->getUsername(1)."</td>\n".
            "          <td>".$this->database->getPassword(1)."</td>\n".
            "        </tr>\n".
            "    </table>\n".
            "    <br/>\n".
            "    <br/>\n".
            "  </div> <!-- col-??-5 -->\n";
    }
    function registrationPage() {
        $html =
            "\n<!-- =================================== registrationPage -->\n".
            "\n".
            "<div class=\"container mycontainer\">\n".
            $this->ddsHeaderRegistration() .
            "  <div class=\"row\">\n".
            $this->registrationForm() .
            $this->registrationFormInfo() .
            "  </div> <!-- row -->\n".
            /* "<br/>\n". */
            $this->ddsFoot() .
            "</div> <!-- container -->\n";
        return $this->head() . $html . $this->foot();
    }
}

?>
