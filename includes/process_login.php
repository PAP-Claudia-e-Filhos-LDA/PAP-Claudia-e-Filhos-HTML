<?php
include('conn.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $username = $_POST['username'];
  $password = $_POST['password'];

  require_once 'conn.php';
  require_once 'functions_inc.php';

  if (emptyInputLogin($username, $password) !== false) {
    header("location: ../php/login.php?error=emptyinput");
    exit();
  }

  loginUser($db, $username, $password);
} else {
  header("location: ../php/login.php");
  exit();
}
