<?php
session_start();
include '../includes/db.php';
include 'functions.php';

// ตรวจสอบการเข้าสู่ระบบแอดมิน
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// ดึงข้อมูลสรุปจากฐานข้อมูล
$menuCount = $pdo->query("SELECT COUNT(*) FROM menus")->fetchColumn();
$orderPendingCount = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
$orderTotalCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $pdo->query("SELECT SUM(total_amount) FROM receipts")->fetchColumn();
$tableCount = $pdo->query("SELECT COUNT(*) FROM tables WHERE status = 'occupied'")->fetchColumn();
$employeeCount = $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
$recentActivities = $pdo->query("SELECT * FROM activity_logs ORDER BY timestamp DESC LIMIT 5")->fetchAll();
$orderHistories = $pdo->query("SELECT * FROM order_history ORDER BY completion_time DESC LIMIT 10")->fetchAll();

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดแอดมิน</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 12px;
        }
        .card-title {
            font-size: 1.2rem;
        }
        .card-text {
            font-size: 1rem;
        }
        .main-content {
            margin-top: 20px;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-nav .nav-link {
            color: #fff !important;
        }
        .scrollable-list {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <!-- แถบเมนู -->
    <?php include 'layout/navbar.php'; ?>

    <div class="main-content">
        <div class="container mt-3">
            <h1 class="text-center">แดชบอร์ดแอดมิน</h1>
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">จำนวนเมนูอาหาร</h5>
                            <p class="card-text"><?php echo $menuCount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">คำสั่งซื้อที่รอดำเนินการ</h5>
                            <p class="card-text"><?php echo $orderPendingCount; ?> คำสั่งซื้อ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">คำสั่งซื้อทั้งหมด</h5>
                            <p class="card-text"><?php echo $orderTotalCount; ?> คำสั่งซื้อ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">รายได้รวมทั้งหมด</h5>
                            <p class="card-text"><?php echo number_format($totalRevenue, 2); ?> บาท</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">จำนวนโต๊ะที่ใช้งาน</h5>
                            <p class="card-text"><?php echo $tableCount; ?> โต๊ะ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-secondary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">จำนวนพนักงาน</h5>
                            <p class="card-text"><?php echo $employeeCount; ?> คน</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <h4 class="text-center">กิจกรรมล่าสุด</h4>
                    <ul class="list-group scrollable-list">
                        <?php foreach ($recentActivities as $activity): ?>
                            <li class="list-group-item">
                                <?php echo $activity['timestamp'] . " - " . $activity['action']; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4 class="text-center">ประวัติคำสั่งซื้อล่าสุด</h4>
                    <ul class="list-group scrollable-list">
                        <?php foreach ($orderHistories as $history): ?>
                            <li class="list-group-item">
                                คำสั่งซื้อ #<?php echo $history['order_id']; ?> 
                                - สถานะ: <?php echo $history['status']; ?>
                                - เวลา: <?php echo $history['completion_time']; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>

</body>
</html>
