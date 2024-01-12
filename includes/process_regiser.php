<?php
include('conn.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $username = $_POST['username'];
  $phoneNumber = $_POST['phone_number'];
  $password = $_POST['password'];

  $insertQuery = "INSERT INTO Clientes (nome_cliente, contacto, pass) VALUES ('$username', '$phoneNumber', '$password')";
  $result = $db->exec($insertQuery);

  if ($result) {
      echo "funcionou!";
  } else {
      echo "Erro ao registrar. Por favor, tente novamente.";
  }
}
?>