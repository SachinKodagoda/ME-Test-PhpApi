<?php
class Database{

    // SPECIFY DATABASE CREDENTIALS
    private $host     = "localhost";
    private $db_name  = "db1";
    private $db_username = "root";
    private $db_password = "";
    public $conn;

    // GET THE DATABASE CONNECTION
    public function getConnection(){

        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->db_username, $this->db_password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>