<?php

// IP ของ MySQL Server
$_CONFIG['mysql']['host'] = 'localhost';
// Username และ Password
$_CONFIG['mysql']['username'] = 'root';
$_CONFIG['mysql']['password'] = 'password';
// ชื่อ Database ของเกม Ragnarok
$_CONFIG['mysql']['db_name'] = 'ragnarok';
// ใช้ระบบ MD5 ในการเข้ารหัส password หรือไม่
$_CONFIG['tmpay']['use_md5'] = false;

// IP ของ TMPAY.NET ที่อนุญาตให้รับส่งข้อมูลบัตรเงินสด (ไม่ควรแก้ไข)
$_CONFIG['tmpay']['access_ip'] = '203.146.127.112';
// รหัสร้านค้า (merchant_id) ของ TMPAY.NET
$_CONFIG['tmpay']['merchant_id'] = 'MYRAG';
// URL ที่ได้ทำการติดตั้ง tmpay.php
$_CONFIG['tmpay']['resp_url'] = 'http://localhost/tmpay/tmpay.php';

// มูลค่าบัตรเงินสด (ไม่ควรแก้ไข)
$_CONFIG['tmpay']['amount'][0] = 0;
$_CONFIG['tmpay']['amount'][1] = 50;
$_CONFIG['tmpay']['amount'][2] = 90;
$_CONFIG['tmpay']['amount'][3] = 150;
$_CONFIG['tmpay']['amount'][4] = 300;
$_CONFIG['tmpay']['amount'][5] = 500;
$_CONFIG['tmpay']['amount'][6] = 1000;

$_CONFIG['tmpay']['card_status'][0] = 'รอการตรวจสอบ';
$_CONFIG['tmpay']['card_status'][1] = '<font color="green">ผ่าน</font>';
$_CONFIG['tmpay']['card_status'][2] = '<font color="green">ผ่าน</font> (รับ Cash แล้ว)';
$_CONFIG['tmpay']['card_status'][3] = '<font color="red">ถูกใช้ไปแล้ว</font>';
$_CONFIG['tmpay']['card_status'][4] = '<font color="red">รหัสผิดพลาด</font>';
$_CONFIG['tmpay']['card_status'][5] = '<font color="red">บัตรทรูมูฟ</font>';

?>