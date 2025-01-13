<?php
include_once 'db.php';

class AdminController {
    private $conn;

    public function __construct() {
        $db = new Database();  // 创建 Database 实例
        $this->conn = $db->getConnection();  // 获取连接
    }

    public function login($username, $password) {
        $query = $this->conn->prepare("SELECT * FROM admins WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // 使用 password_verify 来验证哈希密码
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                // 密码验证成功
                return true;
            } else {
                // 密码错误
                return false;
            }
        } else {
            // 用户名不存在
            return false;
        }
    }

    // 例如注册或更新时使用 password_hash 来保存密码
    public function register($username, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = $this->conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $query->bind_param("ss", $username, $hashed_password);
        return $query->execute();
    }
}
