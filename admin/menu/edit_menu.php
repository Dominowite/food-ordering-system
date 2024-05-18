<?php
session_start();
include '../../includes/db.php';
include '../functions.php';

// ตรวจสอบการเข้าสู่ระบบแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ?");
    $stmt->execute([$id]);
    $menu = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    if ($image) {
        // อัปโหลดรูปภาพ
        $target_dir = "../../upload/img/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

        // อัปเดตเมนูอาหารในฐานข้อมูลพร้อมรูปภาพใหม่
        $stmt = $pdo->prepare("UPDATE menus SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $image, $id]);
    } else {
        // อัปเดตเมนูอาหารในฐานข้อมูลโดยไม่เปลี่ยนรูปภาพ
        $stmt = $pdo->prepare("UPDATE menus SET name = ?, description = ?, price = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $id]);
    }

    header('Location: manage_menus.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขเมนูอาหาร</title>
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
            <h1 class="text-center">แก้ไขเมนูอาหาร</h1>
            <div class="row mt-4">
                <div class="col-md-12">
                    <form action="edit_menu.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อเมนู</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($menu['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">คำอธิบาย</label>
                            <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($menu['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">ราคา</label>
                            <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($menu['price']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">รูปภาพ (ถ้ามีการเปลี่ยนแปลง)</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary">อัปเดตเมนู</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>

</body>
</html>
