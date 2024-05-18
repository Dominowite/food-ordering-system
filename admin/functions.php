<?php
include __DIR__ . '/../includes/db.php';

// ฟังก์ชันต่างๆ ที่ใช้ในโปรเจค เช่น การตรวจสอบสิทธิ์ การสมัครสมาชิก การล็อกอิน
function registerAdmin($username, $password, $email, $name) {
    global $pdo;
    // ตรวจสอบว่ามีชื่อผู้ใช้หรืออีเมลอยู่แล้วหรือไม่
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $admin = $stmt->fetch();

    if ($admin) {
        return false; // ชื่อผู้ใช้หรืออีเมลถูกใช้งานแล้ว
    }

    // แฮชรหัสผ่าน
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // เพิ่มแอดมินใหม่ลงฐานข้อมูล
    $stmt = $pdo->prepare("INSERT INTO admins (username, password, email, name) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$username, $hashedPassword, $email, $name]);
}

function loginAdmin($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        return true;
    }

    return false;
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']);
}
?>
