<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// ตรวจสอบการเข้าสู่ระบบของพนักงาน
if (!isset($_SESSION['staff_logged_in'])) {
    header('Location: login.php');
    exit();
}

// ดึงคำสั่งซื้อที่ยังไม่ได้ดำเนินการ
$orders = $pdo->query("
    SELECT o.*, t.table_number 
    FROM orders o 
    JOIN tables t ON o.table_id = t.id 
    ORDER BY o.order_time DESC
")->fetchAll();

// ฟังก์ชันสำหรับแสดงสถานะคำสั่งซื้อ
function getOrderStatusText($status) {
    switch ($status) {
        case 'pending':
            return 'รอดำเนินการ';
        case 'preparing':
            return 'กำลังเตรียม';
        case 'completed':
            return 'เสร็จสิ้น';
        default:
            return 'ไม่ทราบสถานะ';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ดพนักงาน</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .status-pending { color: #dc3545; }
        .status-preparing { color: #ffc107; }
        .status-completed { color: #28a745; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">แดชบอร์ดพนักงาน</h1>

        <?php if (isset($_SESSION['staff_logged_in'])): ?>
            <div class="alert alert-info text-center">เข้าสู่ระบบโดย: <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?> (พนักงาน)</div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0">คำสั่งซื้อทั้งหมด</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>หมายเลขคำสั่งซื้อ</th>
                                <th>หมายเลขโต๊ะ</th>
                                <th>เวลา</th>
                                <th>รายการ</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['table_number']); ?></td>
                                    <td><?php echo htmlspecialchars($order['order_time']); ?></td>
                                    <td>
                                        <?php 
                                            $stmt = $pdo->prepare("SELECT oi.quantity, m.name FROM order_items oi JOIN menus m ON oi.menu_id = m.id WHERE oi.order_id = ?");
                                            $stmt->execute([$order['id']]);
                                            $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            $itemNames = array_map(function($item) {
                                                return htmlspecialchars($item['name']) . ' x' . htmlspecialchars($item['quantity']);
                                            }, $orderItems);
                                            echo implode(", ", $itemNames);
                                        ?>
                                    </td>
                                    <td><span class="status-<?php echo htmlspecialchars($order['status']); ?>"><?php echo getOrderStatusText($order['status']); ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
