<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// ตรวจสอบการเข้าสู่ระบบของผู้ดูแลระบบ
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin/login.php');
    exit();
}

// ดึงข้อมูลพนักงานที่ต้องการแก้ไข
$id = $_GET['id'];
$employee = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$employee->execute([$id]);
$employee = $employee->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $job_title = $_POST['job_title'];
    $username = $_POST['username'];
    $status = $_POST['status'];
    $salary = $_POST['salary'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE employees SET name = ?, lastname = ?, job_title = ?, username = ?, password = ?, status = ?, salary = ? WHERE id = ?");
        $stmt->execute([$name, $lastname, $job_title, $username, $password, $status, $salary, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE employees SET name = ?, lastname = ?, job_title = ?, username = ?, status = ?, salary = ? WHERE id = ?");
        $stmt->execute([$name, $lastname, $job_title, $username, $status, $salary, $id]);
    }

    header('Location: manage_employees.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขพนักงาน</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include '../admin/layout/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center">แก้ไขพนักงาน</h1>
        <form action="edit_employee.php?id=<?php echo $id; ?>" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อ</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">นามสกุล</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($employee['lastname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="job_title" class="form-label">ตำแหน่ง</label>
                <select class="form-select" id="job_title" name="job_title" required>
                    <option value="staff" <?php if ($employee['job_title'] == 'staff') echo 'selected'; ?>>พนักงาน</option>
                    <option value="kitchen" <?php if ($employee['job_title'] == 'kitchen') echo 'selected'; ?>>ครัว</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($employee['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน (ปล่อยว่างถ้าไม่ต้องการเปลี่ยน)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">สถานะ</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active" <?php if ($employee['status'] == 'active') echo 'selected'; ?>>ใช้งาน</option>
                    <option value="inactive" <?php if ($employee['status'] == 'inactive') echo 'selected'; ?>>ไม่ใช้งาน</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">เงินเดือน</label>
                <input type="number" step="0.01" class="form-control" id="salary" name="salary" value="<?php echo htmlspecialchars($employee['salary']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
        </form>
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
