<?php
//cek sudah login atau belum
if(!isset($_SESSION['admin'])) header('location:login.php');
?>