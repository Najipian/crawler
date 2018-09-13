<?php
require_once("config/db.php");
class dbClient{
    public $dbconn ;
    private $userName ;
    private $password;
    private $dbname;
    private $hostname;

    function __construct(){
        $this->userName = constant("username");
        $this->password = constant("password");
        $this->dbname = constant("dbname");
        $this->hostname = constant("hostname");
    }

    public function openDB(){
        try{
            $this->dbconn = new mysqli($this->hostname, $this->userName, $this->password, $this->dbname)or die("Unable to connect to MySQL");
        }catch(Exception $e){
            return $e->getMessage();
        }

    }

    public function closeDB(){
        mysqli_close($this->dbconn);
    }
}
?>