<?php
include('../includes/conn.php');


$result = $db->query('SELECT * FROM Produtos');
$catalogos = array();

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $catalogos[] = array($row['caminho_imagem'], $row['nome_produto'], $row['desc'], $row['preco']);
}
