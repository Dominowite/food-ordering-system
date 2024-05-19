<?php
include 'includes/db.php';
include 'includes/functions.php';

$table_id = isset($_GET['table_id']) ? intval($_GET['table_id']) : 0;

if ($table_id <= 0) {
    // ถ้าไม่มี table_id หรือ table_id ไม่ถูกต้อง
    die("Invalid table ID");
}

// ดึงข้อมูลเมนูอาหารจากฐานข้อมูล
$singleDishes = $pdo->query("SELECT * FROM menus WHERE status_id = 1 AND category = 'single_dish'")->fetchAll();
$sideDishes = $pdo->query("SELECT * FROM menus WHERE status_id = 1 AND category = 'side_dish'")->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชื่อร้าน</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body>

<section id="menu" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">เมนูอาหาร</h2>
        <h4 class="text-center mb-4">โต๊ะ: <?php echo htmlspecialchars($table_id); ?></h4>

        <!-- Search bar -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="ค้นหาเมนูอาหาร">
                    <button class="btn btn-primary" type="button" id="searchButton">ค้นหา</button>
                </div>
            </div>
        </div>
        <!-- End of Search bar -->

        <div class="row">
            <div class="col-md-6 mb-4">
                <h3 class="menu-category-title">อาหารจานเดียว</h3>
                <?php
                foreach ($singleDishes as $dish) {
                    echo '<div class="menu-item">';
                    echo '<img src="' . htmlspecialchars($dish["image"]) . '" alt="' . htmlspecialchars($dish["name"]) . '" class="menu-item-image">';
                    echo '<h4>' . htmlspecialchars($dish["name"]) . '</h4>';
                    echo '<p>' . htmlspecialchars($dish["description"]) . '</p>';
                    echo '<p class="price">' . htmlspecialchars($dish["price"]) . ' บาท</p>';
                    echo '<button class="btn btn-primary add-to-cart" data-name="' . htmlspecialchars($dish["name"]) . '" data-price="' . htmlspecialchars($dish["price"]) . '">เพิ่มลงตะกร้า</button>';
                    echo '</div>';
                }
                ?>
            </div>

            <div class="col-md-6 mb-4">
                <h3 class="menu-category-title">กับข้าว</h3>
                <?php
                foreach ($sideDishes as $dish) {
                    echo '<div class="menu-item">';
                    echo '<img src="' . htmlspecialchars($dish["image"]) . '" alt="' . htmlspecialchars($dish["name"]) . '" class="menu-item-image">';
                    echo '<h4>' . htmlspecialchars($dish["name"]) . '</h4>';
                    echo '<p>' . htmlspecialchars($dish["description"]) . '</p>';
                    echo '<p class="price">' . htmlspecialchars($dish["price"]) . ' บาท</p>';
                    echo '<button class="btn btn-primary add-to-cart" data-name="' . htmlspecialchars($dish["name"]) . '" data-price="' . htmlspecialchars($dish["price"]) . '">เพิ่มลงตะกร้า</button>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>


<script>
    // JavaScript สำหรับการค้นหาเมนู
    document.getElementById('searchButton').addEventListener('click', function() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let menuItems = document.querySelectorAll('.menu-item');

        menuItems.forEach(function(item) {
            let itemName = item.querySelector('h4').innerText.toLowerCase();
            if (itemName.includes(input)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
<script src="node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>
</body>
</html>
