<?php
include 'includes/db.php';
include 'includes/functions.php';

$table_id = isset($_GET['table_id']) ? intval($_GET['table_id']) : 0;

if ($table_id <= 0) {
    die("รหัสโต๊ะไม่ถูกต้อง");
}

// ดึงข้อมูลเมนูอาหารจากฐานข้อมูล
try {
    $stmt = $pdo->prepare("SELECT m.*, ms.status_name FROM menus m JOIN menu_statuses ms ON m.status_id = ms.id");
    $stmt->execute();
    $menus = $stmt->fetchAll();
} catch (PDOException $e) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูลเมนู: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เมนูอาหาร - ชื่อร้าน</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .card {
            margin-bottom: 20px;
        }
        .card img {
            max-height: 200px;
            object-fit: cover;
        }
        .quantity-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }
        .quantity-container input {
            width: 50px;
            text-align: center;
        }
        .cart-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            padding: 15px;
            cursor: pointer;
            z-index: 1000;
        }
        .cart-icon .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
        }
        .modal-content {
            max-width: 600px;
            margin: auto;
        }
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">ชื่อร้าน</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">หน้าแรก</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">เมนู</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">ติดต่อ</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">เมนูอาหาร - โต๊ะ: <?php echo htmlspecialchars($table_id); ?></h1>

        <div class="search-container">
            <input type="text" id="searchInput" class="form-control" placeholder="ค้นหาเมนูอาหาร">
        </div>

        <div class="row" id="menuCards">
            <?php foreach ($menus as $menu): ?>
                <div class="col-md-4 mb-3 menu-item">
                    <div class="card">
                        <img src="/food-ordering-system/img/<?php echo htmlspecialchars($menu['image']); ?>" alt="<?php echo htmlspecialchars($menu['name']); ?>" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($menu['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($menu['description']); ?></p>
                            <p class="card-text text-primary"><?php echo htmlspecialchars($menu['price']); ?> บาท</p>
                            <p class="card-text"><small class="<?php echo (strtolower($menu['status_name']) == 'available' ? 'text-success' : 'text-danger'); ?>">สถานะ: <?php echo htmlspecialchars($menu['status_name']); ?></small></p>
                            <div class="quantity-container">
                                <button class="btn btn-outline-secondary btn-sm" onclick="decreaseQuantity(this)">-</button>
                                <input type="number" class="form-control quantity-input" value="1" min="1">
                                <button class="btn btn-outline-secondary btn-sm" onclick="increaseQuantity(this)">+</button>
                            </div>
                            <button class="btn btn-primary add-to-cart mt-2" data-id="<?php echo htmlspecialchars($menu['id']); ?>" data-name="<?php echo htmlspecialchars($menu['name']); ?>" data-price="<?php echo htmlspecialchars($menu['price']); ?>">เพิ่มลงตะกร้า</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="cart-icon" data-bs-toggle="modal" data-bs-target="#cartModal">
        <i class="fas fa-shopping-cart"></i>
        <span class="badge" id="cart-count">0</span>
    </div>

    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">ตะกร้าสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ชื่อเมนู</th>
                                <th>จำนวน</th>
                                <th>ราคา</th>
                                <th>รวม</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <!-- รายการสินค้าในตะกร้าจะแสดงที่นี่ -->
                        </tbody>
                    </table>
                    <div class="text-end">
                        <h5>ราคารวม: <span id="total-price">0</span> บาท</h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary" id="confirm-order">ยืนยันการสั่งซื้อ</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">การแจ้งเตือน</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                รายการถูกเพิ่มลงในตะกร้าแล้ว
            </div>
        </div>
    </div>

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>
    <script>
        function increaseQuantity(button) {
            let input = button.previousElementSibling;
            input.value = parseInt(input.value) + 1;
        }

        function decreaseQuantity(button) {
            let input = button.nextElementSibling;
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        document.getElementById('searchInput').addEventListener('input', function() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let menuItems = document.querySelectorAll('.menu-item');

            menuItems.forEach(function(item) {
                let itemName = item.querySelector('.card-title').innerText.toLowerCase();
                if (itemName.includes(input)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const price = this.dataset.price;
                const quantity = this.previousElementSibling.querySelector('.quantity-input').value;
                const item = { id, name, price, quantity: parseInt(quantity) };

                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                const index = cart.findIndex(cartItem => cartItem.id === id);
                if (index === -1) {
                    cart.push(item);
                } else {
                    cart[index].quantity += item.quantity;
                }

                localStorage.setItem('cart', JSON.stringify(cart));
                showToast(`${name} จำนวน ${quantity} ถูกเพิ่มลงในตะกร้าแล้ว`);
                updateCartIcon();
            });
        });

        function showToast(message) {
            const toastElement = document.getElementById('liveToast');
            const toastBody = toastElement.querySelector('.toast-body');
            toastBody.textContent = message;
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        function updateCartIcon() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
            document.getElementById('cart-count').textContent = cartCount;
        }

        function renderCartItems() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItems = document.getElementById('cart-items');
            cartItems.innerHTML = '';
            let totalPrice = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                totalPrice += itemTotal;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>
                        <button class="btn btn-outline-secondary btn-sm" onclick="decreaseCartItemQuantity('${item.id}')">-</button>
                        <span>${item.quantity}</span>
                        <button class="btn btn-outline-secondary btn-sm" onclick="increaseCartItemQuantity('${item.id}')">+</button>
                    </td>
                    <td>${item.price}</td>
                    <td>${itemTotal}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="removeCartItem('${item.id}')">ลบ</button></td>
                `;
                cartItems.appendChild(row);
            });

            document.getElementById('total-price').textContent = totalPrice.toFixed(2);
        }

        function increaseCartItemQuantity(id) {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const index = cart.findIndex(item => item.id === id);
            if (index !== -1) {
                cart[index].quantity++;
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCartItems();
                updateCartIcon();
            }
        }

        function decreaseCartItemQuantity(id) {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const index = cart.findIndex(item => item.id === id);
            if (index !== -1 && cart[index].quantity > 1) {
                cart[index].quantity--;
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCartItems();
                updateCartIcon();
            }
        }

        function removeCartItem(id) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart = cart.filter(item => item.id !== id);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCartItems();
            updateCartIcon();
        }

        document.querySelector('.cart-icon').addEventListener('click', renderCartItems);

        document.addEventListener('DOMContentLoaded', updateCartIcon);

        document.getElementById('confirm-order').addEventListener('click', function() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart.length > 0) {
                // ดึง table_id จาก URL
                const tableId = <?php echo $table_id; ?>;
                // ส่งข้อมูลคำสั่งซื้อไปยัง server
                fetch('confirm_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ table_id: tableId, items: cart })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        localStorage.removeItem('cart');
                        updateCartIcon();
                        renderCartItems();
                        showToast('ยืนยันการสั่งซื้อสำเร็จ');
                        // ปิด modal
                        const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
                        cartModal.hide();
                    } else {
                        showToast('เกิดข้อผิดพลาดในการยืนยันคำสั่งซื้อ: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('เกิดข้อผิดพลาดในการยืนยันคำสั่งซื้อ');
                });
            } else {
                showToast('ตะกร้าสินค้าว่าง');
            }
        });
    </script>
</body>
</html>
