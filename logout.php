<?php
include("include.php");
// เริ่ม Session
session_start();

// ตรวจสอบว่ามีการเข้าสู่ระบบอยู่หรือไม่
if (isset($_SESSION['user']) || isset($_SESSION['admin'])) {
    // เก็บประเภทผู้ใช้ก่อนล้าง session (เพื่อใช้แสดงข้อความที่เหมาะสม)
    $user_type = isset($_SESSION['admin']) ? 'admin' : 'user';
    
    // ลบข้อมูล Session ทั้งหมด
    session_unset();
    
    // ทำลาย Session
    session_destroy();
    
    // แสดงข้อความแจ้งเตือนเมื่อออกจากระบบสำเร็จ
    if ($user_type == 'admin') {
        $logout_message = "ผู้ดูแลระบบได้ออกจากระบบสำเร็จแล้ว";
    } else {
        $logout_message = "คุณได้ออกจากระบบสำเร็จแล้ว";
    }
    
    header("Location: index.php?message=" . urlencode($logout_message) . "&status=success");
} else {
    // กรณีไม่ได้เข้าสู่ระบบ ให้กลับไปที่หน้าหลักโดยไม่มีข้อความ
    header("Location: index.php");
}
exit();
?>