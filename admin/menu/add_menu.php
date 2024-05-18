<?php
session_start();
include '../../includes/db.php';
include '../functions.php';

// ตรวจสอบการเข้าสู่ระบบแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    
    // อัปโหลดรูปภาพ
    $target_dir = "../../upload/img/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // เพิ่มเมนูอาหารลงฐานข้อมูล
    $stmt = $pdo->prepare("INSERT INTO menus (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $image]);

    header('Location: manage_menus.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มเมนูอาหาร</title>
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <!-- แถบเมนูด้านข้าง -->
    <sidebar class="sidebar">
        <?php include '../layout/sidebar.php'; ?>
    </sidebar>

    <div class="main-content">
        <div class="container mt-3">
            <h1 class="text-center">เพิ่มเมนูอาหาร</h1>
            <div class="row mt-4">
                <div class="col-md-12">
                    <form action="add_menu.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อเมนู</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">คำอธิบาย</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">ราคา</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">รูปภาพ</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                        <button type="submit" class="btn btn-success">เพิ่มเมนู</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>

</body>
</html>
