<?php

session_start();

class Database {
    private $conn;

    public function __construct() {
        $server = "localhost";
        $username = "root";
        $password = "";
        $database = "visitor_system";

        $this->conn = mysqli_connect($server, $username, $password, $database);

        if (!$this->conn) {
            die("Connection failed: ". mysqli_connect_error());
        }
    }   

    public function getConnection() {
        return $this->conn;
    }
}
