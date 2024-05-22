<?php
include 'includes/db.php';
session_start();

$table_id = isset($_GET['table_id']) ? intval($_GET['table_id']) : 0;

if ($table_id <= 0) {
    die("Invalid table ID");
}

try {
    $stmt = $pdo->prepare('
        SELECT o.id AS order_id, t.table_number, o.table_id, o.order_time, o.status, oi.menu_id, oi.quantity, m.name, m.price 
        FROM orders o 
        JOIN order_items oi ON o.id = oi.order_id 
        JOIN menus m ON oi.menu_id = m.id 
        JOIN tables t ON o.table_id = t.id 
        WHERE t.table_number = ? 
        ORDER BY o.id DESC');
    $stmt->execute([$table_id]);
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
    <title>บิลของโต๊ะ <?php echo htmlspecialchars($table_id); ?></title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .receipt-container {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .receipt-header {
            margin-bottom: 20px;
        }
        .receipt-items table {
            width: 100%;
        }
        .receipt-items th, .receipt-items td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .receipt-footer {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">บิลของโต๊ะ: <?php echo htmlspecialchars($table_id); ?></h1>
        <div id="receiptContainer"></div>
    </div>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <script>
        // สร้างตัวแปร JavaScript จากข้อมูล PHP
        const groupedOrders = <?php echo json_encode($groupedOrders); ?>;

        function renderReceipts(orders) {
            const receiptContainer = document.getElementById('receiptContainer');
            for (const table_number in orders) {
                for (const order_id in orders[table_number]) {
                    const order = orders[table_number][order_id];
                    const receiptDiv = document.createElement('div');
                    receiptDiv.classList.add('receipt-container');
                    let itemsHtml = '';
                    let total = 0;
                    order.items.forEach(item => {
                        const itemTotal = item.quantity * item.price;
                        total += itemTotal;
                        itemsHtml += `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.quantity}</td>
                                <td>${item.price} บาท</td>
                                <td>${itemTotal.toFixed(2)} บาท</td>
                            </tr>
                        `;
                    });

                    receiptDiv.innerHTML = `
                        <div class="receipt-header">
                            <h5>รหัสคำสั่งซื้อ: ${order.order_id}</h5>
                            <p>รหัสโต๊ะ: ${order.table_number}</p>
                            <p>เวลาสั่งซื้อ: ${order.order_time}</p>
                            <p>สถานะ: <span class="badge ${getBadgeClass(order.status)}">${order.status}</span></p>
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
                                    ${itemsHtml}
                                </tbody>
                            </table>
                        </div>
                        <div class="receipt-footer">
                            <h5>ราคารวม: ${total.toFixed(2)} บาท</h5>
                        </div>
                    `;
                    receiptContainer.appendChild(receiptDiv);
                }
            }
        }

        function getBadgeClass(status) {
            switch (status) {
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

        renderReceipts(groupedOrders);
    </script>
</body>
</html>
