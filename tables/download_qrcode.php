<?php
if (isset($_GET['url']) && !empty($_GET['url'])) {
    $url = $_GET['url'];

    // ตรวจสอบว่าลิงก์ QR Code เป็น URL ที่ถูกต้อง
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $imageContent = file_get_contents($url);

        if ($imageContent !== false) {
            // กำหนดชื่อไฟล์สำหรับดาวน์โหลด
            $filename = 'qrcode.png';

            // ตั้งค่า header เพื่อให้บราวเซอร์ดาวน์โหลดไฟล์
            header('Content-Description: File Transfer');
            header('Content-Type: image/png');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Content-Length: ' . strlen($imageContent));
            header('Pragma: public');

            // ส่งเนื้อหาไฟล์ให้บราวเซอร์
            echo $imageContent;
            exit;
        } else {
            die('ไม่สามารถดาวน์โหลดไฟล์ QR Code ได้');
        }
    } else {
        die('ลิงก์ QR Code ไม่ถูกต้อง');
    }
} else {
    die('ไม่มีลิงก์ QR Code');
}
?>
