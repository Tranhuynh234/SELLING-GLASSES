<?php
require_once __DIR__ . "/../../config/db_connect.php";

class BaseModel {
    protected $conn;
    protected $table;

    public function __construct() {
        $this->conn = Database::connect();
    }
    public function beginTransaction() {
    return $this->conn->beginTransaction();
}

public function commit() {
    return $this->conn->commit();
}

public function rollBack() {
    return $this->conn->rollBack();
}

    // Lấy tất cả
    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    //  Tìm theo ID
public function find($id, $primaryKey = "id") {
    $sql = "SELECT * FROM {$this->table} WHERE {$primaryKey} = :id LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    //  Insert (auto)
    public function create($data) {
        $columns = implode(",", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt->execute($data)) return false;
        return $this->conn->lastInsertId();
    }

    //  Update
    public function update($id, $data, $primaryKey = "userId") {
        $fields = "";

        foreach ($data as $key => $value) {
            $fields .= "$key = :$key, ";
        }

        $fields = rtrim($fields, ", ");

        $sql = "UPDATE {$this->table} SET $fields WHERE {$primaryKey} = :id";
        $data['id'] = $id;

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    //  Delete
    public function delete($id, $primaryKey = "userId") {
        $sql = "DELETE FROM {$this->table} WHERE {$primaryKey} = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Find by field (custom)
public function findBy($field, $value) {
    $sql = "SELECT * FROM {$this->table} WHERE {$field} = :value LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':value' => $value]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
        // SELECT nhiều dòng
    public function queryAll($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // SELECT 1 dòng
    public function queryOne($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>