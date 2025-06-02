<?php
// config.php

// Eğer oturum başlamamışsa başlat
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Veritabanı bağlantı bilgileri
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'shopdb';

// Bağlantı oluştur
$conn = new mysqli($host, $user, $pass, $db);

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Karakter seti ayarla
$conn->set_charset("utf8mb4");
