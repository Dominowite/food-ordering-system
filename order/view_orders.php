<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin/login.php');
    exit();
}

try {
    $stmt = $pdo->query('
        SELECT o.id AS order_id, o.table_id, o.order_time, o.status, oi.menu_id, oi.quantity, m.name, m.price 
        FROM orders o 
        JOIN order_items oi ON o.id = oi.order_id 
        JOIN menus m ON oi.menu_id = m.id 
        ORDER BY o.id DESC');
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

// จัดกลุ่มรายการอาหารตามรหัสคำสั่งซื้อ
$groupedOrders = [];
foreach ($orders as $order) {
    $order_id = $order['order_id'];
    if (!isset($groupedOrders[$order_id])) {
        $groupedOrders[$order_id] = [
            'order_id' => $order_id,
            'table_id' => $order['table_id'],
            'order_time' => $order['order_time'],
            'status' => $order['status'],
            'items' => []
        ];
    }
    $groupedOrders[$order_id]['items'][] = [
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

    <!-- แถบเมนู -->
    <navbar class="navbar">
        <?php include '../admin/layout/navbar.php' ?>
    </navbar>
    <div class="container mt-5">
        <h1 class="mb-4">รายการคำสั่งซื้อ</h1>
        <div id="receiptContainer"></div>
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <script>
        const orders = <?php echo json_encode(array_values($groupedOrders)); ?>;

        function renderReceipts(orders) {
            const receiptContainer = document.getElementById('receiptContainer');
            orders.forEach(order => {
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
                        <p>รหัสโต๊ะ: ${order.table_id}</p>
                        <p>เวลาสั่งซื้อ: ${order.order_time}</p>
                        <p>สถานะ: <span class="badge ${getBadgeClass(order.status)}">${order.status}</span></p>
                    </div>
                    <div class="receipt-items">
                        <table>
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
            });
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

        renderReceipts(orders);
    </script>
</body>
</html>
