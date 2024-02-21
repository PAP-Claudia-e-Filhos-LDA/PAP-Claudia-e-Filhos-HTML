<?php
include('../includes/conn.php');

function getNumeroEncomendas($userId, $db) {
    // Consulta à base de dados para obter o número de encomendas do usuário
    $query = "SELECT COUNT(*) as numEncomendas FROM Encomendas WHERE id_clientes = :userid";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':userid', $userId, SQLITE3_INTEGER);
    $result = $stmt->execute();

    // Obtém o resultado da consulta
    $row = $result->fetchArray(SQLITE3_ASSOC);

    // Retorna o número de encomendas do usuário
    return $row['numEncomendas'];
}

if (!isset($_SESSION["userid"])) {
    // Redireciona para a página de login se o usuário não estiver logado
    header("location: ../php/login.php");
    exit();
}

$userId = $_SESSION["userid"];

// Consulta à base de dados para obter os produtos
$query = "SELECT * FROM Clientes WHERE id_clientes = :userid";
$stmt = $db->prepare($query);
$stmt->bindValue(':userid', $userId, SQLITE3_INTEGER);
$result = $stmt->execute();

// Array para armazenar os produtos obtidos do banco de dados
$user = array();

// Loop para obter os resultados da consulta
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    // Adiciona cada produto ao array $catalogos
    $user[] = array($row['username'], $row['nome_cliente'], $row['contacto'], $row['email'], $row['imagem_perfil']);
}

// Obtém o número de encomendas do usuário
$numEncomendas = getNumeroEncomendas($userId, $db);
?>
