<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// ตรวจสอบการเข้าสู่ระบบของแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// ดึงข้อมูลเมนูที่จะแก้ไข
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ?");
$stmt->execute([$id]);
$menu = $stmt->fetch();

if (!$menu) {
    die("ไม่พบข้อมูลเมนูที่ต้องการแก้ไข");
}

// การแก้ไขเมนู
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $status_id = $_POST['status_id'];
    $image = $menu['image']; // ใช้ภาพเดิมโดยค่าเริ่มต้น

    // ตรวจสอบว่ามีการอัพโหลดภาพใหม่หรือไม่
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target = "../upload/img/" . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $target;
        } else {
            $error = "การอัพโหลดภาพล้มเหลว";
        }
    }

    $stmt = $pdo->prepare("UPDATE menus SET name = ?, description = ?, price = ?, image = ?, status_id = ?, category = ? WHERE id = ?");
    $stmt->execute([$name, $description, $price, $image, $status_id, $category, $id]);

    header('Location: manage_menus.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขเมนู</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../admin/layout/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center">แก้ไขเมนู</h1>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form action="edit_menu.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อเมนู</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($menu['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">คำอธิบาย</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($menu['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">ราคา</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($menu['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">ประเภท</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="single_dish" <?php echo $menu['category'] == 'single_dish' ? 'selected' : ''; ?>>อาหารจานเดียว</option>
                    <option value="side_dish" <?php echo $menu['category'] == 'side_dish' ? 'selected' : ''; ?>>กับข้าว</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status_id" class="form-label">สถานะ</label>
                <select class="form-select" id="status_id" name="status_id" required>
                    <option value="1" <?php echo $menu['status_id'] == 1 ? 'selected' : ''; ?>>Available</option>
                    <option value="2" <?php echo $menu['status_id'] == 2 ? 'selected' : ''; ?>>Unavailable</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">ภาพ</label>
                <input type="file" class="form-control" id="image" name="image">
                <img src="<?php echo htmlspecialchars($menu['image']); ?>" alt="Menu Image" width="100" class="mt-2">
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
        </form>
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
