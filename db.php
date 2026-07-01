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

    public function select(
        string $table,
        array|string $columns = ['*'],
        array $where = []
    ): array {

        if (is_array($columns)) {
            $columns = implode(', ', array_map(fn($c) => "`$c`", $columns));
        }

        $sql = "SELECT {$columns} FROM `{$table}`";

        $params = [];
        $types = '';

        if (!empty($where)) {

            $conditions = [];

            foreach ($where as $column => $value) {

                $conditions[] = "`$column` = ?";

                $params[] = $value;

                $types .= match (true) {
                    is_int($value)   => 'i',
                    is_float($value) => 'd',
                    default          => 's'
                };
            }

            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception($this->conn->error);
        }

        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
