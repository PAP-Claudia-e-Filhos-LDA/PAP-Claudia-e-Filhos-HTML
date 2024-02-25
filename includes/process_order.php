<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = file_get_contents("php://input");
    $postData = json_decode($postData, true);

    $cartDetails = isset($postData['cartDetails']) ? $postData['cartDetails'] : [];
    $total = isset($postData['total']) ? $postData['total'] : 0;
    
    $_SESSION['cartDetails'] = $cartDetails;
    $_SESSION['total'] = $total;
} else {
    echo "Nenhuma solicitação POST recebida";
    http_response_code(400);
}
