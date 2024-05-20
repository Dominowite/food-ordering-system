# ระบบสั่งอาหารออนไลน์ผ่าน QR Code

## รายละเอียดโปรเจค

โปรเจคนี้เป็นระบบสั่งอาหารออนไลน์ที่ให้ลูกค้าสามารถสั่งอาหารผ่านการสแกน QR Code ที่โต๊ะ ระบบประกอบด้วย 3 ส่วนหลัก ได้แก่ ผู้ดูแลระบบ (Admin), พนักงาน (Staff), และครัว (Kitchen)

## คุณสมบัติของระบบ

### ส่วนผู้ดูแลระบบ (Admin)

- การจัดการเมนูอาหาร: เพิ่ม, แก้ไข, ลบเมนูอาหาร
- การจัดการโต๊ะ: เพิ่ม, แก้ไข, ลบโต๊ะ พร้อมกับสร้าง QR Code สำหรับแต่ละโต๊ะ
- การดูคำสั่งซื้อ: ดูคำสั่งซื้อทั้งหมดและสถานะของคำสั่งซื้อ
- การจัดการพนักงาน: เพิ่ม, แก้ไข, ลบพนักงาน
- การจัดการผู้ดูแลระบบ: เพิ่ม, แก้ไข, ลบผู้ดูแลระบบ

### ส่วนพนักงาน (Staff)

- การดูคำสั่งซื้อ: ดูคำสั่งซื้อที่ลูกค้าสั่งจากโต๊ะ
- การเปลี่ยนสถานะคำสั่งซื้อ: อัปเดตสถานะคำสั่งซื้อเมื่อเสร็จสิ้นการให้บริการ

### ส่วนครัว (Kitchen)

- การดูคำสั่งซื้อ: ดูคำสั่งซื้อที่ต้องทำในครัว
- การเปลี่ยนสถานะคำสั่งซื้อ: อัปเดตสถานะคำสั่งซื้อเมื่อทำอาหารเสร็จแล้ว

## โครงสร้างโปรเจค

food-ordering-system/
│
├── admin/
│ ├── css/
│ │ └── style.css # ไฟล์ CSS สำหรับการออกแบบหน้าเว็บในส่วนแอดมิน
│ ├── layout/
│ │ └── navbar.php # ไฟล์สำหรับ Navbar
│ ├── login.php # หน้าเว็บสำหรับล็อกอินแอดมิน
│ ├── dashboard.php # หน้าเว็บหลักสำหรับแอดมินหลังจากล็อกอินสำเร็จ
│ ├── logout.php # หน้าเว็บสำหรับล็อกเอาท์แอดมิน
│ └── register.php # หน้าเว็บสำหรับสมัครสมาชิกแอดมิน
│
├── css/
│ └── style.css # ไฟล์ CSS สำหรับการออกแบบหน้าเว็บ
│
├── includes/
│ ├── db.php # การเชื่อมต่อฐานข้อมูล
│ └── functions.php # ฟังก์ชันต่างๆ ที่ใช้ในโปรเจค เช่น การตรวจสอบสิทธิ์ การสมัครสมาชิก การล็อกอิน
│
├── js/
│ └── scripts.js # ไฟล์ JavaScript สำหรับการทำงานต่างๆ (ถ้ามี)
│
├── images/
│ └── (images used in the project)
│
├── kitchen/
│ ├── login.php # หน้าเว็บสำหรับล็อกอินครัว
│ ├── dashboard.php # หน้าเว็บหลักสำหรับครัวหลังจากล็อกอินสำเร็จ
│ └── logout.php # หน้าเว็บสำหรับล็อกเอาท์ครัว
│
├── menu/
│ ├── manage_menus.php # ไฟล์สำหรับจัดการเมนูอาหาร
│ ├── add_menu.php # ไฟล์สำหรับเพิ่มเมนูอาหาร
│ ├── edit_menu.php # ไฟล์สำหรับแก้ไขเมนูอาหาร
│ └── delete_menu.php # ไฟล์สำหรับลบเมนูอาหาร
│
├── staff/
│ ├── login.php # หน้าเว็บสำหรับล็อกอินพนักงาน
│ ├── dashboard.php # หน้าเว็บหลักสำหรับพนักงานหลังจากล็อกอินสำเร็จ
│ └── logout.php # หน้าเว็บสำหรับล็อกเอาท์พนักงาน
│
├── tables/
│ ├── add_table.php # ไฟล์สำหรับเพิ่มโต๊ะ
│ ├── manage_tables.php # ไฟล์สำหรับจัดการโต๊ะ
│ ├── edit_table.php # ไฟล์สำหรับแก้ไขโต๊ะ
│ └── delete_table.php # ไฟล์สำหรับลบโต๊ะ
│
├── upload/
│ └── img/ # โฟลเดอร์สำหรับเก็บรูปภาพของเมนู
│ └── (images used for menu items)
│ └── qr_code/ # โฟลเดอร์สำหรับเก็บ QR Code ของโต๊ะ
│
├── database/
│ └── create_tables.sql # ไฟล์ SQL สำหรับสร้างตารางในฐานข้อมูล
│
├── index.php # หน้าเว็บหลักของโปรเจค
├── menu.php # หน้าเว็บแสดงเมนูอาหาร
├── node_modules/
├── package.json
└── README.md

bash
คัดลอกโค้ด

## การติดตั้ง

1. Clone โปรเจคนี้จาก GitHub:

```bash
git clone https://github.com/username/food-ordering-system.git
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