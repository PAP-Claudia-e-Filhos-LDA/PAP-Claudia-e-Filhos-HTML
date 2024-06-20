<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $assunto = $_POST['assunto'];
  $mensagem = $_POST['mensagem'];

  $userId = $_SESSION["userid"];

  require_once 'conn.php';
  require_once 'functions_inc.php';

  // Mapear os valores dos assuntos
  $assuntoMap = [
    'others' => 1,
    'products' => 2,
    'encomenda' => 3
  ];

  // Obter o id_assunto correspondente
  $assuntoId = isset($assuntoMap[$assunto]) ? $assuntoMap[$assunto] : null;

  if ($assuntoId !== null) {
    messageSend($db, $userId, $assuntoId, $mensagem);
  } else {
    header("location: ../php/contact.php?error=invalidassunto");
    exit();
  }
} else {
  header("location: ../php/login.php");
  exit();
}
