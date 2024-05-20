<?php
session_start();
include '../includes/db.php';
include '../admin/functions.php';

// ตรวจสอบการเข้าสู่ระบบของแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// ดึงข้อมูลเมนูอาหารจากฐานข้อมูล
$menus = $pdo->query("SELECT m.*, ms.status_name FROM menus m JOIN menu_statuses ms ON m.status_id = ms.id")->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการเมนูอาหาร</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../admin/css/style.css">
    <style>
        /* ปรับแต่งการ์ด */
        .card {
            margin-bottom: 20px;
        }
        .card img {
            max-height: 200px;
            object-fit: cover;
        }
        /* ปรับแต่งช่องค้นหา */
        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .search-container input {
            max-width: 300px;
        }
        .status-available {
            color: green !important;
        }
        .status-unavailable {
            color: red !important;
        }
    </style>
</head>
<body>

    <!-- แถบเมนู -->
    <nav class="sidebar">
        <?php include '../admin/layout/navbar.php'; ?>
    </nav>

    <div class="main-content">
        <div class="container mt-3">
            <h1 class="text-center">จัดการเมนูอาหาร</h1>
            <div class="row mt-4">
                <div class="col-md-12 search-container mb-3">
                    <a href="add_menu.php" class="btn btn-success">เพิ่มเมนูอาหาร</a>
                    <input type="text" id="searchInput" class="form-control" placeholder="ค้นหาเมนูอาหาร">
                </div>
                <div class="col-md-12">
                    <div class="row" id="menuCards">
                        <?php foreach ($menus as $menu): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                        <img src="<?php echo htmlspecialchars($menu['image']); ?>" class="card-img-top" alt="Image">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($menu['name']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($menu['description']); ?></p>
                                        <p class="card-text text-primary"><?php echo htmlspecialchars($menu['price']); ?> บาท</p>
                                        <p class="card-text">
                                            <small class="text-muted 
                                                <?php 
                                                if (strtolower($menu['status_name']) == 'available') {
                                                    echo 'status-available';
                                                } else {
                                                    echo 'status-unavailable';
                                                } ?>
                                            ">
                                                สถานะ: <?php echo htmlspecialchars($menu['status_name']); ?>
                                            </small>
                                        </p>
                                        <a href="edit_menu.php?id=<?php echo $menu['id']; ?>" class="btn btn-primary btn-sm">แก้ไข</a>
                                        <a href="delete_menu.php?id=<?php echo $menu['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบเมนูนี้?');">ลบ</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <script>
        // JavaScript สำหรับการค้นหาเมนูอาหาร
        document.getElementById('searchInput').addEventListener('input', function() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let menuCards = document.querySelectorAll('#menuCards .col-md-4');

            menuCards.forEach(function(card) {
                let itemName = card.querySelector('.card-title').innerText.toLowerCase();
                if (itemName.includes(input)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
