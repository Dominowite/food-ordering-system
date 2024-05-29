<?php
include('../includes/db.php');

// ฟังก์ชันตรวจสอบความถูกต้องของข้อมูล
function validateAdminData($username, $password, $email, $name) {
    $errors = [];

    if (empty($username)) {
        $errors[] = 'กรุณากรอกชื่อผู้ใช้';
    }
    if (!empty($password) && strlen($password) < 6) {
        $errors[] = 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'กรุณากรอกอีเมลที่ถูกต้อง';
    }
    if (empty($name)) {
        $errors[] = 'กรุณากรอกชื่อ';
    }

    return $errors;
}

// ฟังก์ชันตรวจสอบชื่อผู้ใช้หรืออีเมลซ้ำ
function isDuplicate($pdo, $username, $email, $excludeId = null) {
    $sql = "SELECT COUNT(*) FROM admins WHERE (username = :username OR email = :email)";
    if ($excludeId) {
        $sql .= " AND id != :excludeId";
    }
    $stmt = $pdo->prepare($sql);
    $params = ['username' => $username, 'email' => $email];
    if ($excludeId) {
        $params['excludeId'] = $excludeId;
    }
    $stmt->execute($params);
    return $stmt->fetchColumn() > 0;
}

// เพิ่มแอดมิน
if (isset($_POST['add_admin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $approved = isset($_POST['approved']) ? 1 : 0;

    $errors = validateAdminData($username, $password, $email, $name);
    if (!empty($errors)) {
        // แสดงข้อผิดพลาด
        echo "<div class='alert alert-danger'>";
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo "</div>";
    } elseif (isDuplicate($pdo, $username, $email)) {
        echo "<div class='alert alert-danger'>ชื่อผู้ใช้หรืออีเมลนี้มีอยู่ในระบบแล้ว</div>";
    } else {
        try {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO admins (username, password, email, name, approved) VALUES (:username, :password, :email, :name, :approved)");
            $stmt->execute(['username' => $username, 'password' => $password, 'email' => $email, 'name' => $name, 'approved' => $approved]);
            header('Location: manage_admins.php');
            exit();
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>เกิดข้อผิดพลาดในการเพิ่มแอดมิน: " . $e->getMessage() . "</div>";
        }
    }
}

// ลบแอดมิน
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM admins WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header('Location: manage_admins.php');
    exit();
}

// แก้ไขแอดมิน
if (isset($_POST['edit_admin'])) {
    $id = $_POST['admin_id'];
    $username = $_POST['username'];
    $password = $_POST['password']; // ไม่ hash รหัสผ่านถ้าไม่เปลี่ยน
    $email = $_POST['email'];
    $name = $_POST['name'];
    $approved = isset($_POST['approved']) ? 1 : 0;

    $errors = validateAdminData($username, $password, $email, $name);
    if (!empty($errors)) {
        // แสดงข้อผิดพลาด
        echo "<div class='alert alert-danger'>";
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo "</div>";
    } elseif (isDuplicate($pdo, $username, $email, $id)) {
        echo "<div class='alert alert-danger'>ชื่อผู้ใช้หรืออีเมลนี้มีอยู่ในระบบแล้ว</div>";
    } else {
        try {
            // Hash รหัสผ่านถ้ามีการเปลี่ยนแปลง
            if (!empty($password)) {
                $password = password_hash($password, PASSWORD_DEFAULT);
            } else {
                // ดึงรหัสผ่านเดิมจากฐานข้อมูล
                $stmt = $pdo->prepare("SELECT password FROM admins WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $admin = $stmt->fetch();
                $password = $admin['password'];
            }

            $stmt = $pdo->prepare("UPDATE admins SET username = :username, password = :password, email = :email, name = :name, approved = :approved WHERE id = :id");
            $stmt->execute(['username' => $username, 'password' => $password, 'email' => $email, 'name' => $name, 'approved' => $approved, 'id' => $id]);
            header('Location: manage_admins.php'); 
            exit();
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>เกิดข้อผิดพลาดในการแก้ไขแอดมิน: " . $e->getMessage() . "</div>";
        }
    }
}

// ดึงข้อมูลแอดมินทั้งหมด
$stmt = $pdo->query("SELECT * FROM admins");
$admins = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>จัดการแอดมิน</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .table thead th {
            background-color: #007bff;
            color: #fff;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php include('layout/navbar.php'); ?>

    <div class="container">
        <h1 class="mb-4">จัดการแอดมิน</h1>

        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addAdminModal">
            เพิ่มแอดมิน
        </button>

        <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAdminModalLabel">เพิ่มแอดมิน</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="manage_admins.php" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">รหัสผ่าน</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">อีเมล</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="approved" name="approved">
                                <label class="form-check-label" for="approved">อนุมัติ</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            <button type="submit" class="btn btn-primary" name="add_admin">เพิ่มแอดมิน</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <h2>รายชื่อแอดมิน</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ชื่อผู้ใช้</th>
                    <th>อีเมล</th>
                    <th>ชื่อ</th>
                    <th>อนุมัติ</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?= $admin['id']; ?></td>
                    <td><?= $admin['username']; ?></td>
                    <td><?= $admin['email']; ?></td>
                    <td><?= $admin['name']; ?></td>
                    <td><?= $admin['approved'] ? 'ใช่' : 'ไม่'; ?></td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAdminModal<?= $admin['id']; ?>">
                            แก้ไข
                        </button>
                        <div class="modal fade" id="editAdminModal<?= $admin['id']; ?>" tabindex="-1" aria-labelledby="editAdminModalLabel<?= $admin['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editAdminModalLabel<?= $admin['id']; ?>">แก้ไขแอดมิน</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="manage_admins.php" method="post">
                                        <input type="hidden" name="admin_id" value="<?= $admin['id']; ?>">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="username<?= $admin['id']; ?>" class="form-label">ชื่อผู้ใช้</label>
                                                <input type="text" class="form-control" id="username<?= $admin['id']; ?>" name="username" value="<?= $admin['username']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password<?= $admin['id']; ?>" class="form-label">รหัสผ่าน</label>
                                                <input type="password" class="form-control" id="password<?= $admin['id']; ?>" name="password">
                                                <small class="form-text text-muted">เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน</small>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email<?= $admin['id']; ?>" class="form-label">อีเมล</label>
                                                <input type="email" class="form-control" id="email<?= $admin['id']; ?>" name="email" value="<?= $admin['email']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="name<?= $admin['id']; ?>" class="form-label">ชื่อ</label>
                                                <input type="text" class="form-control" id="name<?= $admin['id']; ?>" name="name" value="<?= $admin['name']; ?>" required>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="approved<?= $admin['id']; ?>" name="approved" <?= $admin['approved'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="approved<?= $admin['id']; ?>">อนุมัติ</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                            <button type="submit" class="btn btn-primary" name="edit_admin">บันทึกการเปลี่ยนแปลง</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <a href="manage_admins.php?delete=<?= $admin['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบแอดมินนี้?')">ลบ</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script></body>
</html>
