<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin/login.php');
    exit();
}

$table_id = isset($_GET['table_id']) ? intval($_GET['table_id']) : 0;

try {
    $stmt = $pdo->query('
        SELECT o.id AS order_id, t.table_number, o.table_id, o.order_time, o.status, oi.menu_id, oi.quantity, m.name, m.price 
        FROM orders o 
        JOIN order_items oi ON o.id = oi.order_id 
        JOIN menus m ON oi.menu_id = m.id 
        JOIN tables t ON o.table_id = t.id 
        ORDER BY o.id DESC');
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

// จัดกลุ่มรายการอาหารตามรหัสคำสั่งซื้อ
$groupedOrders = [];
foreach ($orders as $order) {
    $order_id = $order['order_id'];
    $table_number = $order['table_number'];
    if (!isset($groupedOrders[$table_number])) {
        $groupedOrders[$table_number] = [];
    }
    if (!isset($groupedOrders[$table_number][$order_id])) {
        $groupedOrders[$table_number][$order_id] = [
            'order_id' => $order_id,
            'table_number' => $table_number,
            'order_time' => $order['order_time'],
            'status' => $order['status'],
            'items' => []
        ];
    }
    $groupedOrders[$table_number][$order_id]['items'][] = [
        'name' => $order['name'],
        'quantity' => $order['quantity'],
        'price' => $order['price']
    ];
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ดูคำสั่งซื้อ</title>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/view_orders_style.css">
</head>
<body>

    <!-- แถบเมนู -->
    <navbar class="navbar">
        <?php include '../admin/layout/navbar.php' ?>
    </navbar>
    <div class="container mt-5">
        <h1 class="mb-4">รายการคำสั่งซื้อ</h1>
        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <?php foreach ($groupedOrders as $table_number => $orders): ?>
                        <a class="nav-link <?php echo $table_number == array_key_first($groupedOrders) ? 'active' : ''; ?>" id="v-pills-<?php echo $table_number; ?>-tab" data-bs-toggle="pill" href="#v-pills-<?php echo $table_number; ?>" role="tab" aria-controls="v-pills-<?php echo $table_number; ?>" aria-selected="true">โต๊ะ: <?php echo $table_number; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <?php foreach ($groupedOrders as $table_number => $orders): ?>
                        <div class="tab-pane fade <?php echo $table_number == array_key_first($groupedOrders) ? 'show active' : ''; ?>" id="v-pills-<?php echo $table_number; ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo $table_number; ?>-tab">
                            <div class="receipt-grid">
                                <?php foreach ($orders as $order): ?>
                                    <div class="receipt-container">
                                        <div class="receipt-header">
                                            <h5>รหัสคำสั่งซื้อ: <?php echo htmlspecialchars($order['order_id']); ?></h5>
                                            <p>รหัสโต๊ะ: <?php echo htmlspecialchars($order['table_number']); ?></p>
                                            <p>เวลาสั่งซื้อ: <?php echo htmlspecialchars($order['order_time']); ?></p>
                                            <p>สถานะ: <span class="badge <?php echo getBadgeClass($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></p>
                                        </div>
                                        <div class="receipt-items">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>รายการอาหาร</th>
                                                        <th>จำนวน</th>
                                                        <th>ราคา</th>
                                                        <th>รวม</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $total = 0;
                                                    foreach ($order['items'] as $item): 
                                                        $itemTotal = $item['quantity'] * $item['price'];
                                                        $total += $itemTotal;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                                        <td><?php echo htmlspecialchars($item['price']); ?> บาท</td>
                                                        <td><?php echo htmlspecialchars(number_format($itemTotal, 2)); ?> บาท</td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="receipt-footer text-end">
                                            <h5>ราคารวม: <?php echo htmlspecialchars(number_format($total, 2)); ?> บาท</h5>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <script>
        // สร้างตัวแปร JavaScript จากข้อมูล PHP
        const groupedOrders = <?php echo json_encode($groupedOrders); ?>;

        // สร้างฟังก์ชันสำหรับการใช้งานใน JavaScript
        console.log(groupedOrders);
    </script>
</body>
</html>

<?php
function getBadgeClass($status) {
    switch ($status) {
        case 'pending':
            return 'badge-warning';
        case 'preparing':
            return 'badge-info';
        case 'completed':
            return 'badge-success';
        case 'cancelled':
            return 'badge-danger';
        default:
            return '';
    }
}
?>
