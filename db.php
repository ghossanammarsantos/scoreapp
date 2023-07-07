<?php
require_once 'config.php';

// Fungsi untuk melakukan koneksi ke database
function connectDatabase() {
    global $host, $username, $password, $database;
    $connection = mysqli_connect($host, $username, $password, $database);
    if (!$connection) {
        die('Koneksi database gagal: ' . mysqli_connect_error());
    }
    return $connection;
}

// Fungsi untuk menutup koneksi ke database
function closeDatabase($connection) {
    mysqli_close($connection);
}
?>
