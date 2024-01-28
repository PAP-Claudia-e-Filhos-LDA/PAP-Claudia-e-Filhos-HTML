<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obter dados do formulário
  $username = $_POST['username'];
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $phoneNumber = $_POST['phone_number'];


  require_once 'conn.php';
  require_once 'functions_inc.php';

  if (emptyInputProfile($username, $nome, $phoneNumber, $email) !== false) {
    header("location: ../php/editProfile.php?error=emptyinput");
    exit();
  }
  if (invalidUsername($username) !== false) {
    header("location: ../php/editProfile.php?error=ivalidusername");
    exit();
  }
  if (invalidPhone($phoneNumber) !== false) {
    header("location: ../php/editProfile.php?error=ivalidphone");
    exit();
  }
  if (userExistsProfile($db, $_SESSION["userid"], $username, $phoneNumber) !== false) {
    header("location: ../php/editProfile.php?error=userexists");
    exit();
  }


  updateUser($db, $_SESSION["userid"], $username, $nome, $email, $phoneNumber);
} else {
  header("location: ../php/register.php");
  exit();
}
