<?php
session_start();
include '../includes/db.php';
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginAdmin($username, $password)) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ล็อกอินแอดมิน</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
     body {
        font-family: 'Kanit', sans-serif; /* ใช้ฟอนต์ Kanit ตามเอกสาร */
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        background-color: #f0f0f0; /* เปลี่ยนพื้นหลังให้เป็นสีเทาอ่อน */
    }

    .login-container {
        background-color: #fff;
        padding: 3rem;
        border-radius: 15px; /* ปรับให้มนขึ้น */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* เพิ่มความชัดของเงา */
        width: 350px; /* ปรับขนาดให้พอดีกับเนื้อหา */
    }

    .login-container h2 {
        color: #333;
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-label {
        font-weight: bold;
    }

    .form-control {
        border-radius: 8px; /* ปรับให้มนขึ้น */
        border: 1px solid #ced4da;
    }

    .form-control:focus {
        border-color: #007bff; /* สีฟ้าเมื่อ focus */
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn-primary {
        background-color: #007bff; /* สีฟ้า */
        border-color: #007bff;
        border-radius: 8px; /* ปรับให้มนขึ้น */
    }

    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }

    </style>
</head>
<body>
    <div class="login-container text-center">
        <img src="../images/logo.png" alt="โลโก้ร้าน" style="width: 100px; height: 100px; margin-bottom: 20px;">
        <h2>ล็อกอินแอดมิน</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">ล็อกอิน</button>
        </form>
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
