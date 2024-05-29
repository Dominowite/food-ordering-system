<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .navbar {
            background-color: #343a40;
        }
        .navbar-nav .nav-link {
            color: #fff !important;
        }
        .navbar-nav .nav-link:hover {
            background-color: #575d63;
        }
        .navbar-brand {
            color: #fff !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/dashboard.php">แดชบอร์ด</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../menu/manage_menus.php">จัดการเมนูอาหาร</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../order/view_orders.php">ดูคำสั่งซื้อ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../tables/manage_tables.php">จัดการโต๊ะ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../employees/manage_employees.php">จัดการพนักงาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/manage_admins.php">จัดการแอดมิน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../staff/dashboard.php">แดชบอร์ดพนักงาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../kitchen/dashboard.php">แดชบอร์ดครัว</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/logout.php">ออกจากระบบ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
