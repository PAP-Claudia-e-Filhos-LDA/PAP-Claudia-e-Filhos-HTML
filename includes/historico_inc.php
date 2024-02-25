<?php
session_start();

require_once 'conn.php';
require_once 'functions_inc.php';

if (!isset($_SESSION["userid"])) {
    header("location: ../php/login.php");
    exit();
}

$userId = $_SESSION["userid"];
$userOrders = getUserOrders($userId, $db);

