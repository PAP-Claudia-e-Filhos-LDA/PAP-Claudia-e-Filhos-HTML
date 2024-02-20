<?php
session_start();

// Recupere os dados do carrinho
$cartDetails = isset($_SESSION['cartDetails']) ? $_SESSION['cartDetails'] : [];

// Obtenha os dados do corpo da requisição
$postData = file_get_contents("php://input");
$postData = json_decode($postData, true);

$tipoRissois = $postData['rissois'];
$levantamento = $postData['levantamento'];
$metodoPagamento = $postData['pagamento'];
$mensagem = isset($postData['mensagem']) ? $postData['mensagem'] : "";

echo "Tipo de Rissois: $tipoRissois<br>";
echo "Levantamento: $levantamento<br>";
echo "Método de Pagamento: $metodoPagamento<br>";
echo "Mensagem: $mensagem<br>";



foreach ($cartDetails as $item) {
    echo "Produto: " . $item['title'] . ", Quantidade: " . $item['quantity'] . "<br>";
}

// Lembre-se de validar e processar os dados conforme necessário.
