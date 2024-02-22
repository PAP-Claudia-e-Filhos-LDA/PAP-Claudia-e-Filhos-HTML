<?php
include('../includes/conn.php');

function getNumeroEncomendas($userId, $db)
{

    $query = "SELECT COUNT(*) as numEncomendas FROM Encomendas WHERE id_clientes = :userid";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':userid', $userId, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    return $row['numEncomendas'];
}

if (!isset($_SESSION["userid"])) {
    header("location: ../php/login.php");
    exit();
}

$userId = $_SESSION["userid"];


$query = "SELECT * FROM Clientes WHERE id_clientes = :userid";
$stmt = $db->prepare($query);
$stmt->bindValue(':userid', $userId, SQLITE3_INTEGER);
$result = $stmt->execute();


$user = array();


while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $user[] = array($row['username'], $row['nome_cliente'], $row['contacto'], $row['email'], $row['imagem_perfil']);
}

$numEncomendas = getNumeroEncomendas($userId, $db);
