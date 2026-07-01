<?php

class DB
{
    private string $host = "localhost";
    private string $user = "root";
    private string $password = "";
    private string $database = "auth_db";

    private mysqli $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Ulanishda xatolik: " . $this->conn->connect_error);
        }
    }

    public function query(string $sql)
    {
        return $this->conn->query($sql);
    }

    public function select(string $table, $columns = '*', $where = '')
    {
        $sql = "SELECT $columns FROM $table";
        if ($where) {
            $sql .= " WHERE $where";
        }

        return $this->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}