<?php
class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "maria123";
    private $db_name = "drink750ml-main";
    public $conn;
    public function getConnection(){
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
