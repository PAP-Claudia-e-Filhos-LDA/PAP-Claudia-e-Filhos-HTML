<?php
include('../includes/conn.php');

// Função para obter o número de encomendas de um cliente
function getNumeroEncomendas($userId, $db)
{
  $query = "SELECT COUNT(*) as numEncomendas FROM Encomendas WHERE id_clientes = :userid";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':userid', $userId, SQLITE3_INTEGER);
  $result = $stmt->execute();
  $row = $result->fetchArray(SQLITE3_ASSOC);

  return $row['numEncomendas'];
}

// Função para obter os 10 principais compradores
function getTopBuyers($db)
{
  $query = "
        SELECT 
            c.id_clientes, 
            c.username, 
            c.nome_cliente, 
            c.imagem_perfil, 
            COUNT(e.id_Encomendas) as numEncomendas
        FROM 
            Clientes c
        LEFT JOIN 
            Encomendas e 
        ON 
            c.id_clientes = e.id_clientes
        GROUP BY 
            c.id_clientes
        ORDER BY 
            numEncomendas DESC
        LIMIT 5";

  $stmt = $db->prepare($query);
  $result = $stmt->execute();

  $topBuyers = array();
  while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $topBuyers[] = $row;
  }

  return $topBuyers;
}

// Obtendo os 10 principais compradores
$topBuyers = getTopBuyers($db);
