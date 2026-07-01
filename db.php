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

    public function insert(string $table, array $data): bool
    {
        $columns = implode(', ', array_map(fn($c) => "`$c`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO `{$table}` ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception($this->conn->error);
        }

        $types = '';
        $values = [];

        foreach ($data as $value) {
            $values[] = $value;

            $types .= match (true) {
                is_int($value)   => 'i',
                is_float($value) => 'd',
                default          => 's'
            };
        }

        $stmt->bind_param($types, ...$values);

        return $stmt->execute();
    }

    public function update(string $table, array $data, array $where): bool
    {
        $set = [];
        $conditions = [];
        $values = [];
        $types = '';

        foreach ($data as $column => $value) {
            $set[] = "`$column` = ?";
            $values[] = $value;

            $types .= match (true) {
                is_int($value)   => 'i',
                is_float($value) => 'd',
                default          => 's'
            };
        }

        foreach ($where as $column => $value) {
            $conditions[] = "`$column` = ?";
            $values[] = $value;

            $types .= match (true) {
                is_int($value)   => 'i',
                is_float($value) => 'd',
                default          => 's'
            };
        }

        $sql = "UPDATE `{$table}` SET " . implode(', ', $set);

        if ($conditions) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception($this->conn->error);
        }

        $stmt->bind_param($types, ...$values);

        return $stmt->execute();
    }

    public function delete(string $table, array $where): bool
    {
        $conditions = [];
        $values = [];
        $types = '';

        foreach ($where as $column => $value) {
            $conditions[] = "`$column` = ?";
            $values[] = $value;

            $types .= match (true) {
                is_int($value)   => 'i',
                is_float($value) => 'd',
                default          => 's'
            };
        }

        $sql = "DELETE FROM `{$table}`";

        if ($conditions) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception($this->conn->error);
        }

        $stmt->bind_param($types, ...$values);

        return $stmt->execute();
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
