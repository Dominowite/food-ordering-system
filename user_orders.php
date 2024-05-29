<?php
include 'includes/db.php';

$table_number = isset($_GET['table_number']) ? intval($_GET['table_number']) : 0;

if ($table_number <= 0) {
    die("Invalid table number");
}

// ดึง table_id จาก table_number
$stmt = $pdo->prepare("SELECT id FROM tables WHERE table_number = ?");
$stmt->execute([$table_number]);
$table = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$table) {
    die("Invalid table number");
}

$table_id = $table['id'];

try {
    $stmt = $pdo->prepare("
        SELECT o.id AS order_id, o.table_id, o.status, o.payment_status, o.order_time, 
               GROUP_CONCAT(m.name SEPARATOR ', ') AS items, SUM(oi.quantity * m.price) AS total_amount
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN menus m ON oi.menu_id = m.id
        WHERE o.table_id = ?
        GROUP BY o.id
        ORDER BY o.order_time DESC
    ");
    $stmt->execute([$table_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // คำนวณยอดรวมทั้งหมด
    $total_sum = array_reduce($orders, function($carry, $order) {
        return $carry + $order['total_amount'];
    }, 0);
} catch (Exception $e) {
    die('เกิดข้อผิดพลาด: ' . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการคำสั่งซื้อของโต๊ะหมายเลข <?php echo htmlspecialchars($table_number); ?></title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        @media (max-width: 576px) {
            .table-responsive {
                font-size: 0.8rem;
            }
            .table th, .table td {
                padding: 0.3rem;
            }
        }
        .total-summary {
            font-size: 1.2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">รายการคำสั่งซื้อของโต๊ะหมายเลข <?php echo htmlspecialchars($table_number); ?></h1>

        <?php if (!empty($orders)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>คำสั่งซื้อที่</th>
                            <th>สถานะ</th>
                            <th>สถานะการชำระเงิน</th>
                            <th>เวลา</th>
                            <th>รายการอาหาร</th>
                            <th>ยอดรวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td><?php echo htmlspecialchars($order['payment_status']); ?></td>
                                <td><?php echo htmlspecialchars($order['order_time']); ?></td>
                                <td><?php echo htmlspecialchars($order['items']); ?></td>
                                <td><?php echo htmlspecialchars($order['total_amount']); ?> บาท</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end total-summary">
                ยอดรวมทั้งหมด: <?php echo htmlspecialchars($total_sum); ?> บาท
            </div>
        <?php else: ?>
            <p class="text-center">ไม่มีคำสั่งซื้อที่จะแสดง</p>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="menu.php?table_number=<?php echo htmlspecialchars($table_number); ?>" class="btn btn-primary mb-2">กลับไปที่เมนู</a>
            <a href="index.php?table_number=<?php echo htmlspecialchars($table_number); ?>" class="btn btn-secondary mb-2">กลับไปที่หน้าแรก</a>
        </div>
    </div>

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
