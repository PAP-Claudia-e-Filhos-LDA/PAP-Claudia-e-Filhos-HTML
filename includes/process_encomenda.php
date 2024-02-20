<?php
session_start();

// Recupere os dados do carrinho
$cartDetails = isset($_SESSION['cartDetails']) ? $_SESSION['cartDetails'] : [];

// Obtenha os dados do corpo da requisição
$postData = file_get_contents("php://input");
$postData = json_decode($postData, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoRissois = $_POST['rissois'];
    $levantamento = $_POST['levantameto'];
    $metodoPagamento = $_POST['pagamento'];
    $mensagem = isset($_POST['mensagem']) ? $_POST['mensagem'] : "";

    echo "Tipo de Rissois: $tipoRissois<br>";
    echo "Levantamento: $levantamento<br>";
    echo "Método de Pagamento: $metodoPagamento<br>";
    echo "Mensagem: $mensagem<br>";




    foreach ($cartDetails as $item) {
        echo "" . $item['title'] . "," . $item['quantity'] . "<br>";
    }
} else {
    header("location: ../php/index.php");
}
