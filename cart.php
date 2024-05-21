<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
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
                    <!-- ปุ่มไปยังหน้าคำสั่งซื้อของฉัน -->
                    <a href="user_orders.php" id="viewOrder" class="btn btn-primary mt-2">ไปยังคำสั่งซื้อของฉัน</a>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
                localStorage.removeItem('cart');
                alert('คำสั่งซื้อของคุณถูกยืนยันแล้ว');
            } else {
                alert('เกิดข้อผิดพลาดในการยืนยันคำสั่งซื้อ: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการยืนยันคำสั่งซื้อ');
        });
    });
</script>
</body>
</html>
