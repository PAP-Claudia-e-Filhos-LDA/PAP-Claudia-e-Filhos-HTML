<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $phoneNumber = $_POST['phone_number'];
  $password = $_POST['password'];

  require_once 'conn.php';
  require_once 'functions_inc.php';

  if (emptyInputSignup($username, $phoneNumber, $password) !== false) {
    header("Location: ../php/register.php?error=emptyinput");
    exit();
  }
  if (invalidUsername($username) !== false) {
    header("Location: ../php/register.php?error=invalidusername");
    exit();
  }
  if (invalidPhone($phoneNumber) !== false) {
    header("Location: ../php/register.php?error=invalidphone");
    exit();
  }
  if (userExists($db, $username, $phoneNumber, $email) !== false) {
    header("Location: ../php/register.php?error=userexists");
    exit();
  }

  createUser($db, $username, $nome, $email, $phoneNumber, $password);
  header("Location: ../php/register.php?success=registered");
  exit();
} else {
  header("Location: ../php/register.php");
  exit();
}
