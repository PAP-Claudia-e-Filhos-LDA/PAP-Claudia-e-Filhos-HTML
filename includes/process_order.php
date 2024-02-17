<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['details']) && isset($_GET['total'])) {
    $cartDetails = json_decode($_GET['details'], true);
    $total = $_GET['total'];

    // Armazene as variáveis em uma sessão
    $_SESSION['cartDetails'] = $cartDetails;
    $_SESSION['total'] = $total;

} else {
    echo "Nenhum produto no carrinho";
    http_response_code(400);
}
?>
