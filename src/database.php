<?php

include_once "config.php";

class DDSDatabase {
    protected $connection;
    protected $default_users = array(
        0 => array("ft6Yj9","Password1","Josef Novák",NULL),
        1 => array("x5q9nn","s123456","Šimon Svoboda",NULL),
        2 => array("matous",NULL,"Matouš Novotný","101010"),
        3 => array("petr",NULL,"Petr Dvořák","abcdabcd"),
    );
    public function __construct( $db = "sqlite:db/db.sql" ) {
        $this->connection = $db;
        $this->createDatabaseIfNotExists();
    }
    public function getUsername($i) {
        if( $i < 0 || $i >= count($this->default_users) ) return "";
        return $this->default_users[$i][0];
    }
    private function getUsernameIndex($name) {
        for( $a=0; $a<count($this->default_users); $a++) {
            if( $this->getUsername($a) == $name ) return $a;
        }
        return -1;
    }
    public function getPassword($i) {
        if( gettype($i) == "string" ) { $i = $this->getUsernameIndex($i); }
        if( $i < 0 || $i >= count($this->default_users) ) return "";
        return $this->default_users[$i][1];
    }
    public function getCN($i) {
        if( gettype($i) == "string" ) { $i = $this->getUsernameIndex($i); }
        if( $i < 0 || $i >= count($this->default_users) ) return "";
        return $this->default_users[$i][2];
    }
    public function getOTP($i) {
        if( gettype($i) == "string" ) { $i = $this->getUsernameIndex($i); }
        if( $i < 0 || $i >= count($this->default_users) ) return "";
        return $this->default_users[$i][3];
    }
    public function defaultUsersAsText($part="ALL") {
        $txt="";
        foreach($default_users as $user) {
            if( $user[1] != NULL ) {
                if( $part == "ALL" or $part == "PWD") {
                    $txt .= $user[2] . " (";
                    $txt .= "login: '" . $user[0] . "' heslo: '" . $user[1] . "'";
                    $txt .= ")\n";
                }
            }
            if( $user[3] != NULL ) {
                if( $part == "ALL" or $part == "PIN") {
                    $txt .= $user[2] . " (";
                    $txt .= "jednorázové identifikační heslo: '" . $user[3] . "'";
                    $txt .= ")\n";
                }
            }
        }
        return $txt;
    }
    private function fillDatabase() {
        $file_db = new PDO($this->connection);
        $update = $file_db->prepare("insert into users (username,password,cn,otp) values (:username,:password,:cn,:otp)");
        for( $a=0; $a<count($this->default_users); $a++) {
            $update->execute(
                array(
                    ':username' => $this->getUsername($a),
                    ':password' => $this->getPassword($a),
                    ':cn' => $this->getCN($a),
                    ':otp' => $this->getOTP($a)
                )
            );
        }
    }

    /**
     * vyvoreni databaze
     */
    public function createDatabase() {
        $file_db = new PDO($this->connection);
        $file_db->exec("DROP TABLE IF EXISTS users");
        $file_db->exec("CREATE TABLE users (
                    id INTEGER PRIMARY KEY,
                    username TEXT UNIQUE,
                    password TEXT,
                    cn TEXT,
                    email TEXT,
                    udi TEXT UNIQUE,
                    otp TEXT
                    )");
        $file_db->exec("DROP TABLE IF EXISTS messages");
        $file_db->exec("CREATE TABLE messages (
                    id INTEGER PRIMARY KEY,
                    username TEXT,
                    date TEXT,
                    message TEXT,
                    signature TEXT
                    )");
        $file_db->exec("DROP TABLE IF EXISTS blocking");
        $file_db->exec("CREATE TABLE blocking (
                    time INTEGER
                    )");
        $this->fillDatabase();
    }
    public function createDatabaseIfNotExists() {
        // this works for sqlite only
        $list = explode(":",$this->connection,2);
        if( ! file_exists($list[1]) ) {
            $this->createDatabase();
        }
    }
    public function setUdi($user,$udi) {
        $file_db = new PDO($this->connection);
        $query = $file_db->prepare("update users set udi = :udi where ( username = :username ) and ( udi is NULL )");
        $query->execute( array( ":username" => $user, ":udi" => $udi) );
        $query = $file_db->prepare("select * from users where udi = :udi and username = :username");
        $query->execute( array( ":udi" => $udi, ":username" => $user) );
        $rows = $query->fetchAll();
        return ( count($rows) == 1 );
    }
    public function hasPeig($user){
        $file_db = new PDO($this->connection);
        $query = $file_db->prepare("select udi from users where ( username = :username )");
        $query->execute( array( ":username" => $user) );
        $rows = $query->fetchAll();
        if( count($rows) == 1 ) {
            if($rows[0]["udi"] == NULL) { return false;}
            return true;
        }
        return false;
    }
    public function getUdi($user){
        $file_db = new PDO($this->connection);
        $query = $file_db->prepare("select udi from users where ( username = :username )");
        $query->execute( array( ":username" => $user) );
        $rows = $query->fetchAll();
        if( count($rows) == 1 ) {
            if($rows[0]["udi"] == NULL) { return "";}
            return $rows[0]["udi"];
        }
        return "";
    }
    public function getUsernameByUdi($udi) {
        $file_db = new PDO($this->connection);
        $query = $file_db->prepare("select username from users where ( udi = :udi )");
        $query->execute( array( ":udi" => $udi) );
        $rows = $query->fetchAll();
        if( count($rows) == 1 ) {
            return $rows[0]["username"];
        }
        return "";
    }
    public function getUsernameByOTP($otp) {
        $file_db = new PDO($this->connection);
        $query = $file_db->prepare("select username from users where ( otp = :otp )");
        $query->execute( array( ":otp" => $otp) );
        $rows = $query->fetchAll();
        if( count($rows) == 1 ) {
            return $rows[0]["username"];
        }
        return "";
    }
    public function isOTPValid($otp){
        $file_db = new PDO($this->connection);
        $query = $file_db->prepare("select udi from users where ( otp = :otp ) and ( udi is NULL)");
        $query->execute( array( ":otp" => $otp) );
        $rows = $query->fetchAll();
        return ( count($rows) == 1 );
    }

    /**
     * blokovani resetu
     */
    public function blockReset() {
        $file_db = new PDO($this->connection);
        $file_db->exec("DELETE FROM blocking");
        $query = $file_db->prepare("INSERT INTO blocking ( time ) values ( :time )");
        $query->execute( array( ":time" => time() ) );
    }
    public function isResetBlocked() {
        $file_db = new PDO($this->connection);

        $query = $file_db->prepare("SELECT time FROM blocking");
        $query->execute( );
        $rows = $query->fetchAll();
        if ( count( $rows ) == 0 ) { return false; };
        return ( $rows[0]["time"] + $GLOBALS["resettime"] * 60 > time() );
    }
}

?>
