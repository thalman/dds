<?php
include_once "aducid/aducid.php";

class DDSUser {
    protected $database;
    protected $username;
    protected $udi;
    protected $CN;
    protected $loginWay;
    
    function __construct($db) {
        $this->database = $db;
        $this->username = "";
        $this->udi = "";
        $this->loginWay = "";
        $this->load();
    }
    function loggedIn() {
        //return ( $this->username != "" );
        return ( $this->username != ""  || $this->udi != "" );
    }
    function registered() {
        //return ( $this->username != "" );
        return ( $this->username != "" );
    }
    function loggedInWithAducid() {
        return ( $this->loginWay == "aducid");
    }
    function loggedInWithPassword() {
        return ( $this->loginWay == "password");
    }
    function save() {
        $_SESSION["username"] = $this->username;
        $_SESSION["udi"] = $this->udi;
        $_SESSION["loginWay"] = $this->loginWay;
    }
    function load() {
        if( isset($_SESSION["username"]) ){
            $this->username = $_SESSION["username"];
        }
        $this->loadFromDb();
        if( isset($_SESSION["udi"]) && ( $this->udi == "" ) ){
            $this->udi = $_SESSION["udi"];
        }
        if( isset($_SESSION["loginWay"]) ){
            $this->loginWay = $_SESSION["loginWay"];
        }
    }
    private function loadFromDb() {
        if( $this->loggedIn() ) {
            $this->CN = $this->database->getCN($this->username);
            $this->udi = $this->database->getUDI($this->username);
        } else {
            $this->username = "";
            $this->CN = "";
            $this->udi = "";
        }
    }
    function loginWithPassword($name,$pwd) {
        if( $name != "" && $this->database->getPassword($name) == $pwd ) {
            $this->username = $name;
            $this->loadFromDb();
            $this->loginWay = "password";
            $this->save();
            return true;
        }
        return false;
    }
    function loginWithADUCID($aducid) {
        if( $aducid->verify() ) {
            $this->loginWay = "aducid";
            $this->udi = $aducid->getUserDatabaseIndex();
            $this->username = $this->database->getUsernameByUdi($this->udi);
            if( $this->username != "" ) { 
                $this->loadFromDb();
            }
            $this->save();
            return true;
        }
        return false;
    }
    function loginWithOTP($otp) {
        if( $this->database->isOTPValid($otp) ) {
            $this->username = $this->database->getUsernameByOTP($otp);
            $this->loadFromDb();
            $this->save();
            return true;
        }
        return false;
    }
    function logout() {
        unset($_SESSION["username"]);
        unset($_SESSION["udi"]);
        unset($_SESSION["loginWay"]);
        $this->username = "";
        $this->udi = "";
        $this->CN = "";
        $this->loginWay = "";
    }
    function commonName() {
        if( $this->loggedIn() ) { return $this->CN; }
        return "";
    }
    function hasPeig() {
        if( ! $this->loggedIn() ) {
            return false;
        }
        return $this->database->hasPeig($this->username);
    }
    function setUdi($udi) {
        if( $this->database->setUdi($this->username, $udi) ) {
            $this->udi = $udi;
            $this->loginWay = "aducid";
            return true;
        }
        return false;
    }
    function getUdi() {
        return $this->udi;
    }
};
?>