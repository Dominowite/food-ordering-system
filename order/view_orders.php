<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../admin/login.php');
    exit();
}

try {
    $stmt = $pdo->query('SELECT * FROM orders ORDER BY id DESC');
    $orders = $stmt->fetchAll();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
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
</head>
<body>

 <!-- แถบเมนู -->
 <navbar class="navbar">
        <?php include '../admin/layout/navbar.php' ?>
    </navbar>
    <div class="container mt-5">
        <h1 class="mb-4">รายการคำสั่งซื้อ</h1>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">รหัสคำสั่งซื้อ</th>
                    <th scope="col">รหัสโต๊ะ</th>
                    <th scope="col">เวลาสั่งซื้อ</th>
                    <th scope="col">สถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $row) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['table_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['order_time']); ?></td>
                    <td>
                        <?php 
                        $status = htmlspecialchars($row['status']);
                        $badgeClass = '';
                        switch ($status) {
                            case 'pending':
                                $badgeClass = 'badge-warning';
                                break;
                            case 'preparing':
                                $badgeClass = 'badge-info';
                                break;
                            case 'completed':
                                $badgeClass = 'badge-success';
                                break;
                            case 'cancelled':
                                $badgeClass = 'badge-danger';
                                break;
                        }
                        ?>
                        <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>
</body>
</html>
