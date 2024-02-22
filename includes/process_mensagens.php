<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $assunto = $_POST['assunto'];
  $mensagem = $_POST['mensagem'];



  $userId = $_SESSION["userid"];

  require_once 'conn.php';
  require_once 'functions_inc.php';

 messageSend($db, $userId, $assunto, $mensagem);

} else {
  header("location: ../php/login.php");
  exit();
}
