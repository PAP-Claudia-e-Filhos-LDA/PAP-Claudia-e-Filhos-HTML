<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoRissois = $_POST['rissois'];
    $levantamento = $_POST['levantameto'];
    $metodoPagamento = $_POST['pagamento'];
    $mensagem = isset($_POST['mensagem']) ? $_POST['mensagem'] : "";

    // Agora você pode usar as variáveis acima conforme necessário.
    // ...

    // Recupere as variáveis da sessão
    $cartDetails = isset($_SESSION['cartDetails']) ? $_SESSION['cartDetails'] : [];
  

    echo "Tipo de Rissois: $tipoRissois<br>";
    echo "Levantamento: $levantamento<br>";
    echo "Método de Pagamento: $metodoPagamento<br>";
    echo "Mensagem: $mensagem<br>";
    
    foreach ($cartDetails as $item) {
        echo "Produto: " . $item['title'] . ", Quantidade: " . $item['quantity'] . "<br>";
    }

    // Lembre-se de validar e processar os dados conforme necessário.

} else {
    header("location: ../php/encomenda.php?error=op");
}
?>
