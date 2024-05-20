<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// ตรวจสอบการเข้าสู่ระบบของครัวหรือแอดมิน
if (!isset($_SESSION['kitchen_logged_in']) && !isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// ดึงคำสั่งซื้อที่ยังไม่ได้ทำ พร้อมหมายเลขโต๊ะ
try {
    $pendingOrders = $pdo->query("
        SELECT o.*, t.table_number 
        FROM orders o 
        JOIN tables t ON o.table_id = t.id 
        WHERE o.status IN ('pending', 'preparing') 
        ORDER BY o.order_time DESC
    ")->fetchAll(); // เรียงตามเวลาล่าสุด
} catch (Exception $e) {
    die("Error fetching orders: " . $e->getMessage());
}

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
    <title>แดชบอร์ดครัว</title>
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
        <h1 class="text-center mb-4">แดชบอร์ดครัว</h1>

        <?php if (isset($_SESSION['admin_logged_in'])): ?>
            <div class="alert alert-info text-center">เข้าสู่ระบบโดย: <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?> (แอดมิน)</div>
        <?php elseif (isset($_SESSION['kitchen_logged_in'])): ?>
            <div class="alert alert-info text-center">เข้าสู่ระบบโดย: <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?> (ครัว)</div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h2 class="h4 mb-0">คำสั่งซื้อที่ต้องทำ</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>หมายเลข</th>
                                <th>หมายเลขโต๊ะ</th>
                                <th>เวลา</th>
                                <th>รายการ</th> 
                                <th>สถานะ</th>
                                <th>การกระทำ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingOrders as $order) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['table_number']); ?></td>
                                    <td><?php echo htmlspecialchars($order['order_time']); ?></td>
                                    <td>
                                        <?php 
                                            try {
                                                $stmt = $pdo->prepare("SELECT oi.quantity, m.name FROM order_items oi JOIN menus m ON oi.menu_id = m.id WHERE oi.order_id = ?");
                                                $stmt->execute([$order['id']]);
                                                $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $itemNames = array_map(function($item) {
                                                    return htmlspecialchars($item['name']) . ' x' . htmlspecialchars($item['quantity']);
                                                }, $orderItems);
                                                echo implode(", ", $itemNames);
                                            } catch (Exception $e) {
                                                echo "Error fetching order items: " . $e->getMessage();
                                            }
                                        ?>
                                    </td>
                                    <td><span class="status-<?php echo htmlspecialchars($order['status']); ?>"><?php echo getOrderStatusText($order['status']); ?></span></td>
                                    <td>
                                        <a href="update_order_status.php?order_id=<?php echo htmlspecialchars($order['id']); ?>&status=preparing" class="btn btn-warning btn-sm <?php echo ($order['status'] == 'preparing') ? 'disabled' : ''; ?>">
                                            <i class="bi bi-clock-history"></i> กำลังเตรียม
                                        </a>
                                        <a href="update_order_status.php?order_id=<?php echo htmlspecialchars($order['id']); ?>&status=completed" class="btn btn-success btn-sm <?php echo ($order['status'] == 'completed') ? 'disabled' : ''; ?>">
                                            <i class="bi bi-check-circle"></i> เสร็จสิ้น
                                        </a>
                                    </td>
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
