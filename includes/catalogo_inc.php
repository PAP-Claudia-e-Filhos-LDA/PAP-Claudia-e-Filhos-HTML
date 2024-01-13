<?php
include('../includes/conn.php');

// Consulta à base de dados para obter os produtos
$result = $db->query('SELECT * FROM Produtos');

// Array para armazenar os produtos obtidos do banco de dados
$catalogos = array();

// Loop para obter os resultados da consulta
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    // Adiciona cada produto ao array $catalogos
    $catalogos[] = array($row['caminho_imagem'], $row['nome_produto'], $row['desc'], $row['preco']);
}
