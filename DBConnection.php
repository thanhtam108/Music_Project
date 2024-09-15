<?php

class DBConnection {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "audiostoredb";
    public $conn;

    // Constructor to establish connection

    public function __construct()
    {
        $this->connect();
    }

    protected function connect()
    {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->dbname);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        mysqli_set_charset($this->conn, "UTF8");
    }

    // Disconnect from the database
    public function disconnect() {
        mysqli_close($this->conn);
    }
}

?>