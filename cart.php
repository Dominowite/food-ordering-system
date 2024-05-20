<?php
include 'includes/db.php';
include 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body>
<section id="cart" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">ตะกร้าสินค้า</h2>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ชื่อเมนู</th>
                            <th>ราคา</th>
                            <th>จำนวน</th>
                            <th>รวม</th>
                            <th>การกระทำ</th>
                        </tr>
                    </thead>
                    <tbody id="cartItems">
                        <!-- รายการในตะกร้าจะถูกเพิ่มที่นี่ -->
                    </tbody>
                </table>
                <div class="text-end">
                    <h4>ราคารวม: <span id="totalPrice">0</span> บาท</h4>
                    <button class="btn btn-success" id="checkout">ยืนยันคำสั่งซื้อ</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartItems = document.getElementById('cartItems');
        const totalPriceElement = document.getElementById('totalPrice');
        let totalPrice = 0;

        function updateCart() {
            cartItems.innerHTML = '';
            totalPrice = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                totalPrice += itemTotal;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.price}</td>
                    <td>${item.quantity}</td>
                    <td>${itemTotal}</td>
                    <td>
                        <button class="btn btn-danger btn-sm remove-item" data-id="${item.id}">ลบ</button>
                    </td>
                `;
                cartItems.appendChild(row);
            });

            totalPriceElement.textContent = totalPrice.toFixed(2);
        }

        updateCart();

        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const index = cart.findIndex(cartItem => cartItem.id === id);

                if (index !== -1) {
                    cart.splice(index, 1);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCart();
                }
            });
        });

        document.getElementById('checkout').addEventListener('click', function() {
            if (cart.length === 0) {
                alert('ตะกร้าว่างเปล่า');
                return;
            }

            const order = {
                table_id: <?php echo isset($_GET['table_id']) ? intval($_GET['table_id']) : 0; ?>,
                items: cart,
                total: totalPrice
            };

            fetch('checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(order)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('คำสั่งซื้อของคุณถูกยืนยันแล้ว');
                    localStorage.removeItem('cart');
                    window.location.href = 'index.php';
                } else {
                    alert('เกิดข้อผิดพลาดในการยืนยันคำสั่งซื้อ');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
</body>
</html>
