<!--menu.php-->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชื่อร้าน</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">

</head>
<body>


    <section id="menu" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">เมนูอาหาร</h2>

             <!-- Search bar -->
             <div class="row mb-4">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="ค้นหาเมนูอาหาร">
                        <button class="btn btn-primary" type="button" id="searchButton">ค้นหา</button>
                    </div>
                </div>
            </div>
            <!-- End of Search bar -->

            <div class="row">
                <div class="col-md-6 mb-4">
                    <h3 class="menu-category-title">อาหารจานเดียว</h3>
                    <?php
                    // ในอนาคต ตรงนี้จะเป็นส่วนที่ดึงข้อมูลจากฐานข้อมูล (รวมถึงลิงก์รูปภาพ)
                    $singleDishes = [
                        ["name" => "ข้าวผัด", "description" => "ข้าวผัดร้อนๆ หอมกระทะ", "price" => 50, "image" => "upload/img/menu1.png"],
                        ["name" => "ผัดกะเพรา", "description" => "ผัดกะเพราเครื่องแน่น รสจัดจ้าน", "price" => 60, "image" => "upload/img/menu2.png"],
                        // ... เพิ่มรายการอาหารอื่นๆ ได้
                    ];

                    

                    foreach ($singleDishes as $dish) {
                        echo '<div class="menu-item">';
                        echo '<img src="' . $dish["image"] . '" alt="' . $dish["name"] . '" class="menu-item-image">'; // เพิ่มรูปภาพ
                        echo '<h4>' . $dish["name"] . '</h4>';
                        echo '<p>' . $dish["description"] . '</p>';
                        echo '<p class="price">' . $dish["price"] . ' บาท</p>';
                        echo '<button class="btn btn-primary add-to-cart" data-name="' . $dish["name"] . '" data-price="' . $dish["price"] . '">เพิ่มลงตะกร้า</button>';
                        echo '</div>';
                    }
                    ?>
                </div>

                <div class="col-md-6 mb-4">
                    <h3 class="menu-category-title">กับข้าว</h3>
                    <?php
                    // ในอนาคต ตรงนี้จะเป็นส่วนที่ดึงข้อมูลจากฐานข้อมูล (รวมถึงลิงก์รูปภาพ)
                    $sideDishes = [
                        ["name" => "ต้มยำกุ้ง", "description" => "ต้มยำรสแซ่บ จัดจ้าน", "price" => 80, "image" => "upload/img/menu1.png"],
                        ["name" => "แกงเขียวหวาน", "description" => "แกงเขียวหวานเข้มข้น หอมเครื่องแกง", "price" => 70, "image" => "upload/img/menu3.png"],
                        // ... เพิ่มรายการอาหารอื่นๆ ได้
                    ];

                    foreach ($sideDishes as $dish) {
                        echo '<div class="menu-item">';
                        echo '<img src="' . $dish["image"] . '" alt="' . $dish["name"] . '" class="menu-item-image">'; // เพิ่มรูปภาพ
                        echo '<h4>' . $dish["name"] . '</h4>';
                        echo '<p>' . $dish["description"] . '</p>';
                        echo '<p class="price">' . $dish["price"] . ' บาท</p>';
                        echo '<button class="btn btn-primary add-to-cart" data-name="' . $dish["name"] . '" data-price="' . $dish["price"] . '">เพิ่มลงตะกร้า</button>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include("layout/footer.php"); ?>

    <script>
        // JavaScript สำหรับการค้นหาเมนู
        document.getElementById('searchButton').addEventListener('click', function() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let menuItems = document.querySelectorAll('.menu-item');

            menuItems.forEach(function(item) {
                let itemName = item.querySelector('h4').innerText.toLowerCase();
                if (itemName.includes(input)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
   <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script src="node_modules/@fortawesome/fontawesome-free/js/all.min.js"></script>
</body>
</html>
