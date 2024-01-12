<?php
include('conn.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Verifica se o formulário foi enviado via método POST

  // Obtém os valores dos campos do formulário
  $emailOrPhone = $_POST['email_or_phone'];
  $password = $_POST['password'];

  // Consulta na tabela "Clientes" para verificar os dados de login
  $query = "SELECT * FROM Clientes WHERE (nome_cliente = '$emailOrPhone' OR contacto = '$emailOrPhone') AND pass = '$password'";
  $result = $db->query($query);

  if ($result) {
      if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "Funcionou!";
      } else {
          echo "Credenciais inválidas. Por favor, tente novamente.";
      }
  } else {
      echo "Erro ao processar a solicitação. Por favor, tente novamente.";
  }
}
?>