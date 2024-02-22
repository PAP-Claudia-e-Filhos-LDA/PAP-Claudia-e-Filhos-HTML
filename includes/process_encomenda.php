<?php
session_start();

// Recupere os dados do carrinho
$cartDetails = isset($_SESSION['cartDetails']) ? $_SESSION['cartDetails'] : [];

require_once 'conn.php';
require_once 'functions_inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoRissois = $_POST['rissois'];
    $levantamento = $_POST['levantameto'];
    $metodoPagamento = $_POST['pagamento'];
    $mensagem = isset($_POST['mensagem']) ? $_POST['mensagem'] : "";

    echo " $tipoRissois<br>";
    echo " $levantamento<br>";
    echo " $metodoPagamento<br>";
    echo " $mensagem<br>";


    $encomendaId = createOrder($db, $_SESSION["userid"], $metodoPagamento, $levantamento, $mensagem);
    if ($encomendaId !== false && $encomendaId !== null && $encomendaId !== 0) {
        createOrderLine($db, $encomendaId, $tipoRissois, $cartDetails);
        
        header("location: ../php/catalogo.php?error=none");
    } else {
        echo "Erro na criação da encomenda.";
    }
} else {
    header("location: ../php/index.php");
}
