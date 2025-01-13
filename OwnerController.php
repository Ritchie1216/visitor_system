<?php

include_once 'db.php';

class OwnerController {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function OwnerRegistation($name, $email, $password, $unit, $phone) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, unit, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $password_hash, $unit, $phone);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function OwnerLogin($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($row && password_verify($password, $row['password'])) {
                $_SESSION['owner_id'] = $row['id'];
                $_SESSION['owner_name'] = $row['name'];
                return true;
            }
        }
        return false;
    }

    public function OwnerList($limit = 10, $offset = 0) {
        $stmt = $this->conn->prepare("SELECT * FROM users LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $owners = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $owners;
    }

    public function SearchOwner($name, $unit, $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];
        $types = "";
        
        if (!empty($name)) {
            $sql .= " AND name LIKE ?";
            $params[] = "%$name%";
            $types .= "s";
        }
        if (!empty($unit)) {
            $sql .= " AND unit LIKE ?";
            $params[] = "$unit%";
            $types .= "s";
        }
        
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $owners = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $owners;
    }

    public function CountOwners($name, $unit) {
        $sql = "SELECT COUNT(*) AS count FROM users WHERE 1=1";
        $params = [];
        $types = "";
        
        if (!empty($name)) {
            $sql .= " AND name LIKE ?";
            $params[] = "%$name%";
            $types .= "s";
        }
        if (!empty($unit)) {
            $sql .= " AND unit LIKE ?";
            $params[] = "%$unit%";
            $types .= "s";
        }
        
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'];
    }

    public function OwenrModify($name, $email, $password, $unit, $phone, $id) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET name = ?, email = ?, password = ?, unit = ?, phone = ? WHERE id = ?");
        $stmt->bind_param('sssssi', $name, $email, $password_hash, $unit, $phone, $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function OwnerDelete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function GetOwnerById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $owner = $result->fetch_assoc();
        $stmt->close();
        return $owner;
    }
}
