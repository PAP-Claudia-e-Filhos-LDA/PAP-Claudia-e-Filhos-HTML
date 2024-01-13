<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obter dados do formulário
  $username = $_POST['username'];
  $phoneNumber = $_POST['phone_number'];
  $password = $_POST['password'];

  require_once 'conn.php';
  require_once 'functions_inc.php';

  if (emptyInputSignup($username, $phoneNumber, $password) !== false) {
    header("location: ../php/register.php?error=emptyinput");
    exit();
  }
  if (invalidUsername($username) !== false) {
    header("location: ../php/register.php?error=ivalidusername");
    exit();
  }
  if (invalidPhone($phoneNumber) !== false) {
    header("location: ../php/register.php?error=ivalidphone");
    exit();
  }

  if (userExists($db, $username, $phoneNumber) !== false) {
    header("location: ../php/register.php?error=userexists");
    exit();
  }

  createUser($db, $username, $phoneNumber, $password);
} else {
  header("location: ../php/register.php");
  exit();
}
