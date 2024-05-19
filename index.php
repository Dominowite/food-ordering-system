<?php
include 'includes/db.php';
include 'includes/functions.php';

$table_id = isset($_GET['table_id']) ? intval($_GET['table_id']) : 0;

if ($table_id <= 0) {
    // ถ้าไม่มี table_id หรือ table_id ไม่ถูกต้อง
    die("Invalid table ID");
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชื่อร้าน</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">
</head>

<body>
    <!-- Hero Section -->
    <section class="hero text-white text-center bg-dark py-5">
        <div class="container">
            <h1 class="display-4">ยินดีต้อนรับสู่ชื่อร้าน</h1>
            <p class="lead">สั่งอาหารง่ายๆ ด้วย QR Code</p>
        </div>
    </section>

    <!-- Menu Section -->
    <section class="container mt-5">
        <div class="table">
            <h1>โต๊ะ: <?php echo htmlspecialchars($table_id); ?></h1> 
        </div>

        <div class="menu-buttons my-4 text-center">
            <a href="menu.php?table_id=<?php echo htmlspecialchars($table_id); ?>" class="btn btn-primary mx-2">
                <i class="fas fa-utensils"></i> สั่งอาหาร
            </a>
            <button class="btn btn-secondary mx-2">
                <i class="fas fa-bell"></i> เรียกพนักงาน
            </button>
            <button class="btn btn-success mx-2">
                <i class="fas fa-money-bill-wave"></i> เก็บเงิน
            </button>
        </div>

        <div class="best__menu text-center">
            <h1>เมนูแนะนำ</h1>
        </div>

        <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/menu1.png" class="d-block w-100" alt="Example headline">
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <h1>Example headline.</h1>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/menu2.png" class="d-block w-100" alt="Another example headline">
                    <div class="container">
                        <div class="carousel-caption">
                            <h1>Another example headline.</h1>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/menu3.png" class="d-block w-100" alt="One more for good measure">
                    <div class="container">
                        <div class="carousel-caption text-end">
                            <h1>One more for good measure.</h1>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-light py-3 mt-5">
        <div class="container text-center">
            <p>&copy; 2023 ชื่อร้านอาหาร</p>
        </div>
    </footer>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script src="node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>
</body>

</html>
