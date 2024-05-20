ระบบสั่งอาหารออนไลน์ผ่าน QR Code
รายละเอียดโปรเจค
โปรเจคนี้เป็นระบบสั่งอาหารออนไลน์ที่ให้ลูกค้าสามารถสั่งอาหารผ่านการสแกน QR Code ที่โต๊ะ ระบบประกอบด้วย 3 ส่วนหลัก ได้แก่ ผู้ดูแลระบบ (Admin), พนักงาน (Staff), และครัว (Kitchen)

คุณสมบัติของระบบ
ส่วนผู้ดูแลระบบ (Admin)
การจัดการเมนูอาหาร: เพิ่ม, แก้ไข, ลบเมนูอาหาร
การจัดการโต๊ะ: เพิ่ม, แก้ไข, ลบโต๊ะ พร้อมกับสร้าง QR Code สำหรับแต่ละโต๊ะ
การดูคำสั่งซื้อ: ดูคำสั่งซื้อทั้งหมดและสถานะของคำสั่งซื้อ
การจัดการพนักงาน: เพิ่ม, แก้ไข, ลบพนักงาน
การจัดการผู้ดูแลระบบ: เพิ่ม, แก้ไข, ลบผู้ดูแลระบบ
ส่วนพนักงาน (Staff)
การดูคำสั่งซื้อ: ดูคำสั่งซื้อที่ลูกค้าสั่งจากโต๊ะ
การเปลี่ยนสถานะคำสั่งซื้อ: อัปเดตสถานะคำสั่งซื้อเมื่อเสร็จสิ้นการให้บริการ
ส่วนครัว (Kitchen)
การดูคำสั่งซื้อ: ดูคำสั่งซื้อที่ต้องทำในครัว
การเปลี่ยนสถานะคำสั่งซื้อ: อัปเดตสถานะคำสั่งซื้อเมื่อทำอาหารเสร็จแล้ว
โครงสร้างโปรเจค
css
คัดลอกโค้ด
food-ordering-system/
├── admin/
│   ├── css/
│   │   └── style.css
│   ├── layout/
│   │   └── navbar.php
│   ├── login.php
│   ├── dashboard.php
│   ├── logout.php
│   └── register.php
├── cart.php
├── checkout.php
├── confirm_order.php
├── css/
│   └── style.css
├── includes/
│   ├── db.php
│   └── functions.php
├── js/
│   └── scripts.js
├── images/
│   └── (images used in the project)
├── kitchen/
│   ├── login.php
│   ├── dashboard.php
│   └── logout.php
├── menu/
│   ├── manage_menus.php
│   ├── add_menu.php
│   ├── edit_menu.php
│   └── delete_menu.php
├── staff/
│   ├── login.php
│   ├── dashboard.php
│   └── logout.php
├── tables/
│   ├── add_table.php
│   ├── manage_tables.php
│   ├── edit_table.php
│   └── delete_table.php
├── upload/
│   └── img/
├── qr_code/
├── database/
│   └── create_tables.sql
├── index.php
├── menu.php
├── node_modules/
├── package.json
├── update_order_status.php
└── README.md
การติดตั้ง
Clone โปรเจคนี้จาก GitHub:

bash
คัดลอกโค้ด
git clone https://github.com/Dominowite/food-ordering-system.git
ติดตั้ง dependencies โดยใช้ Composer และ npm:

bash
คัดลอกโค้ด
composer install
npm install
สร้างฐานข้อมูลและตารางที่จำเป็น โดยใช้ไฟล์ create_tables.sql:

sql
คัดลอกโค้ด
CREATE DATABASE IF NOT EXISTS food_ordering_db;
USE food_ordering_db;
SOURCE database/create_tables.sql;
ตั้งค่าการเชื่อมต่อฐานข้อมูลในไฟล์ includes/db.php:

php
คัดลอกโค้ด
<?php
$host = 'localhost';
$db = 'food_ordering_db';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8";
$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
?>
การใช้งาน
เปิดเว็บเซิร์ฟเวอร์ (เช่น Apache หรือ Nginx) และเข้าไปที่ http://localhost/food-ordering-system ในเบราว์เซอร์
เข้าสู่ระบบในส่วนผู้ดูแลระบบ (admin/login.php)
จัดการเมนูอาหารและโต๊ะในระบบ
ใช้ QR Code ที่สร้างจากระบบเพื่อสั่งอาหารจากโต๊ะ
ผู้มีส่วนร่วม
ชื่อผู้พัฒนา
ข้อมูลการติดต่อ
