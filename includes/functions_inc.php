<?php

use LDAP\Result;


// ------------------  Functions Validations ---------------------
function emptyInputSignup($username, $phoneNumber, $password)
{
    $result = false;
    if (empty($username) || empty($phoneNumber) || empty($password)) {
        $result = true;
    }
    return $result;
}

function invalidUsername($username)
{
    return !preg_match("/^[a-zA-Z0-9_]*$/", $username);
}



function invalidPhone($phoneNumber)
{
    if (empty($phoneNumber)) {
        return false;
    }
    return !preg_match("/^[0-9]{9}$/", $phoneNumber);
}

function userExists($db, $username, $phoneNumber, $email)
{
    $sql = "SELECT * FROM Clientes WHERE username = :username OR contacto = :phoneNumber OR email = :email";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        header("location: ../php/register.php?error=stmtfailed");
        exit();
    }

    $stmt->bindParam(':username', $username, SQLITE3_TEXT);
    $stmt->bindParam(':phoneNumber', $phoneNumber, SQLITE3_TEXT);
    $stmt->bindParam(':email', $email, SQLITE3_TEXT);

    $result = $stmt->execute();

    $row = $result->fetchArray(SQLITE3_ASSOC);

    $stmt->close();

    if ($row) {
        return $row;
    } else {
        $result = false;
        return $result;
    }
}


// ------------------  Function Register ---------------------
function createUser($db, $username, $nome, $email, $phoneNumber, $password)
{

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO clientes(username, nome_cliente, contacto, email, pass, imagem_perfil) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        header("location: ../php/register.php?error=stmtfailed&msg=" . $db->lastErrorMsg());
        exit();
    }

    $stmt->bindParam(1, $username, SQLITE3_TEXT);
    $stmt->bindParam(2, $nome, SQLITE3_TEXT);
    $stmt->bindParam(3, $phoneNumber, SQLITE3_TEXT);
    $stmt->bindParam(4, $email, SQLITE3_TEXT);
    $stmt->bindParam(5, $hashedPassword, SQLITE3_TEXT);

    $imagem_perfil = "../img/user.png";
    $stmt->bindValue(6, $imagem_perfil, SQLITE3_TEXT);

    $result = $stmt->execute();

    if (!$result) {
        header("location: ../php/register.php?error=stmtexecutionfailed&msg=" . $db->lastErrorMsg());
        exit();
    }

    $stmt->close();
    header("location: ../php/login.php?error=none");
    exit();
}




// ------------------  Functions login ---------------------
function emptyInputLogin($username, $password)
{
    $result = false;
    if (empty($username) || empty($password)) {
        $result = true;
    }
    return $result;
}

function loginUser($db, $username, $password)
{
    $userExists = userExists($db, $username, $username, $username);

    if ($userExists === false) {
        header("location: ../php/login.php?error=wronglogin");
        exit();
    }

    $storedPassword = $userExists["pass"];

    if (!password_verify($password, $storedPassword)) {
        header("location: ../php/login.php?error=wronglogin");
        exit();
    }

    session_start();
    $_SESSION["userid"] = $userExists["id_clientes"];
    $_SESSION["username"] = $userExists["username"];
    header("location: ../php/index.php?error=none");
    exit();
}




// ------------------  Function Edit Profile ---------------------
function emptyInputProfile($db, $username, $nome, $phoneNumber, $email)
{
    $userId = $_SESSION["userid"];

    $query = "SELECT * FROM Clientes WHERE id_clientes = :userid";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':userid', $userId, SQLITE3_INTEGER);
    $result = $stmt->execute();

    $user = array();

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $user = array(
            'username' => $row['username'],
            'nome' => $row['nome_cliente'],
            'phoneNumber' => $row['contacto'],
            'email' => $row['email'],
            'imagem_perfil' => $row['imagem_perfil']
        );
    }

    if (invalidUsername($username) !== false) {
        header("location: ../php/editProfile.php?error=invalidusername");
        exit();
    }

    if (invalidPhone($phoneNumber) !== false) {
        header("location: ../php/editProfile.php?error=invalidphone");
        exit();
    }

    if (userExistsProfile($db, $userId, $username, $phoneNumber, $email) !== false) {
        header("location: ../php/editProfile.php?error=userexists2");
        exit();
    }

    if (empty($username)) {
        $username = $user['username'];
    }

    if (empty($nome)) {
        $nome = $user['nome'];
    }

    if (empty($phoneNumber)) {
        $phoneNumber = $user['phoneNumber'];
    }

    if (empty($email)) {
        $email = $user['email'];
    }

    $imagem_perfil = $user['imagem_perfil'];  

    updateUser($db, $userId, $username, $nome, $email, $phoneNumber, $imagem_perfil);
    return false;
}



function userExistsProfile($db, $id_clientes, $username, $phoneNumber, $email)
{
    $sql = "SELECT * FROM Clientes WHERE (username = :username AND id_clientes != :id_clientes)
            OR (contacto = :phoneNumber AND id_clientes != :id_clientes)
            OR (email = :email AND id_clientes != :id_clientes)";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        $errorMessage = $db->lastErrorMsg();
        header("location: ../php/editProfile.php?error=stmtfailed&message=" . urlencode($errorMessage));
        exit();
    }

    if (!empty($username)) {
        $stmt->bindParam(':username', $username, SQLITE3_TEXT);
    }

    if (!empty($phoneNumber)) {
        $stmt->bindParam(':phoneNumber', $phoneNumber, SQLITE3_TEXT);
    }

    if (!empty($email)) {
        $stmt->bindParam(':email', $email, SQLITE3_TEXT);
    }

    $stmt->bindParam(':id_clientes', $id_clientes, SQLITE3_INTEGER);
    $result = $stmt->execute();

    $row = $result->fetchArray(SQLITE3_ASSOC);

    $stmt->close();

    if ($row) {
        return $row;
    } else {
        return false;
    }
}


function updateImage($db, $id_clientes, $imagem_perfil_caminho)
{
    $sql = "UPDATE clientes SET imagem_perfil = ? WHERE id_clientes = ?";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        header("location: ../php/editProfile.php?error=stmtfailed&msg=" . $db->lastErrorMsg());
        exit();
    }

    $stmt->bindParam(1, $imagem_perfil_caminho, SQLITE3_TEXT);
    $stmt->bindParam(2, $id_clientes, SQLITE3_INTEGER);

    $result = $stmt->execute();

    if (!$result) {
        header("location: ../php/editProfile.php?error=stmtexecutionfailed&msg=" . $db->lastErrorMsg());
        exit();
    }

    $stmt->close();
}


function updateUser($db, $id_clientes, $username, $nome, $email, $phoneNumber)
{
    $sql = "UPDATE clientes SET username = ?, nome_cliente = ?, contacto = ?, email = ? WHERE id_clientes = ?";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        header("location: ../php/editProfile.php?error=stmtfailed&msg=" . $db->lastErrorMsg());
        exit();
    }

    $stmt->bindParam(1, $username, SQLITE3_TEXT);
    $stmt->bindParam(2, $nome, SQLITE3_TEXT);
    $stmt->bindParam(3, $phoneNumber, SQLITE3_TEXT);
    $stmt->bindParam(4, $email, SQLITE3_TEXT);
    $stmt->bindParam(5, $id_clientes, SQLITE3_INTEGER);

    $result = $stmt->execute();

    if (!$result) {
        header("location: ../php/editProfile.php?error=stmtexecutionfailed&msg=" . $db->lastErrorMsg());
        exit();
    }

    $stmt->close();

    header("location: ../php/editProfile.php?error=none");
    exit();
}


// ------------------  Functions Encomenda ---------------------
function createOrder($db, $id_clientes, $metodo_pagamento, $metodo_entrega, $mensagem)
{
    $data_encomenda = date('Y-m-d'); 
    $metodo_pagamento = ($metodo_pagamento == 'mbway') ? 1 : 0;
    $metodo_entrega = ($metodo_entrega == 'domicilio') ? 1 : 0;

    
    $sql = "INSERT INTO Encomendas (id_clientes, data_encomenda, metedo_pagamento, metedo_entrega, mensagem) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        die("Erro na preparação da declaração: " . $db->lastErrorMsg());
    }

    $stmt->bindParam(1, $id_clientes, SQLITE3_INTEGER);
    $stmt->bindParam(2, $data_encomenda, SQLITE3_TEXT);
    $stmt->bindParam(3, $metodo_pagamento, SQLITE3_INTEGER);
    $stmt->bindParam(4, $metodo_entrega, SQLITE3_INTEGER);
    $stmt->bindParam(5, $mensagem, SQLITE3_TEXT);

    $result = $stmt->execute();

    if (!$result) {
        die("Erro na execução da declaração: " . $db->lastErrorMsg());
    }

    $lastId = $db->lastInsertRowID();
    $stmt->close();

    return $lastId;
}



function getProductIdByName($db, $nomeProduto)
{
    $nomeProduto = trim($nomeProduto);
    $sql = "SELECT id_produto FROM Produtos WHERE nome_produto = ?";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        die("Erro na preparação da declaração: " . $db->lastErrorMsg());
    }

    $stmt->bindParam(1, $nomeProduto, SQLITE3_TEXT);
    $result = $stmt->execute();

    if (!$result) {
        die("Erro na execução da declaração: " . $db->lastErrorMsg());
    }

    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row === false) {
        echo "Produto não encontrado para o nome: $nomeProduto<br>";
        $stmt->close();
        return null;
    }

    $idProduto = $row['id_produto'];
    $stmt->close();

    return $idProduto;
}



function createOrderLine($db, $encomendaId, $tipoRissois, $cartDetails)
{
    $sql = "INSERT INTO Linha_de_Encomenda (Encomendas_id_Encomendas, Produtos_id_produto, congelados, quantidade, preco_produto) VALUES (?, ?, ?, ?, (SELECT preco FROM Produtos WHERE id_produto = ?))";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        echo "Erro: Preparação da declaração falhou.<br>";
        return false;
    }

    foreach ($cartDetails as $item) {
        if (!isset($item['title'], $item['quantity'], $tipoRissois)) {
            echo "Erro: Variáveis necessárias ausentes em um item do carrinho.<br>";
            continue;
        }

        $nomeProduto = $item['title'];
        $quantidade = $item['quantity'];

        echo "Nome do Produto: $nomeProduto<br>";
        echo "Quantidade: $quantidade<br>";
        echo "Tipo: $tipoRissois<br>";
        echo "$tipoRissois <br>";


        $idProduto = getProductIdByName($db, $nomeProduto);

        if ($idProduto === null) {
            continue;
        }

        $congelado = ($tipoRissois == 'congelado') ? 1 : 0;

        $stmt->bindParam(1, $encomendaId, SQLITE3_INTEGER);
        $stmt->bindParam(2, $idProduto, SQLITE3_INTEGER);
        $stmt->bindParam(3, $congelado, SQLITE3_INTEGER);
        $stmt->bindParam(4, $quantidade, SQLITE3_INTEGER);
        $stmt->bindParam(5, $idProduto, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if (!$result) {
            echo "Erro: Execução da declaração falhou para o item: $nomeProduto.<br>";
            continue;
        }
    }

    $stmt->close();
    return $encomendaId;
}



function getUserOrders($userId, $db)
{
    $queryOrders = "SELECT * FROM Encomendas WHERE id_clientes = :userid";
    $stmtOrders = $db->prepare($queryOrders);
    $stmtOrders->bindValue(':userid', $userId, SQLITE3_INTEGER);
    $resultOrders = $stmtOrders->execute();

    $orders = array();

    while ($rowOrders = $resultOrders->fetchArray(SQLITE3_ASSOC)) {
        $order = array(
            'id_Encomendas' => $rowOrders['id_Encomendas'],
            'data_encomenda' => $rowOrders['data_encomenda'],
            'metedo_pagamento' => $rowOrders['metedo_pagamento'],
            'metedo_entrega' => $rowOrders['metedo_entrega'],
            'mensagem' => $rowOrders['mensagem'],
            'items' => array()
        );

        $queryItems = "SELECT p.nome_produto, p.preco, p.caminho_imagem, l.quantidade 
                       FROM Linha_de_Encomenda l
                       INNER JOIN Produtos p ON l.Produtos_id_produto = p.id_produto
                       WHERE l.Encomendas_id_Encomendas = :orderId";
        $stmtItems = $db->prepare($queryItems);
        $stmtItems->bindValue(':orderId', $rowOrders['id_Encomendas'], SQLITE3_INTEGER);
        $resultItems = $stmtItems->execute();

        while ($rowItems = $resultItems->fetchArray(SQLITE3_ASSOC)) {
            $order['items'][] = array(
                'nome_produto' => $rowItems['nome_produto'],
                'preco' => $rowItems['preco'],
                'caminho_imagem' => $rowItems['caminho_imagem'],
                'quantidade' => $rowItems['quantidade']
            );
        }
        $orders[] = $order;
    }

    return $orders;
}





// ------------------  Function Mensagem ---------------------
function messageSend($db, $userId, $assunto, $mensagem)
    {
        $sql = "INSERT INTO Mensagens_Clientes (id_cliente, assunto, mensagem) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            header("location: ../index.php?error=stmtfailed&msg=" . $db->lastErrorMsg());
            exit();
        }

        $stmt->bindParam(1, $userId,SQLITE3_TEXT);
        $stmt->bindParam(2, $assunto, SQLITE3_TEXT);
        $stmt->bindParam(3, $mensagem, SQLITE3_TEXT);

        $result = $stmt->execute();

        if (!$result) {
            header("location: ../php/contact.php?error=stmtexecutionfailed&msg=" . $db->lastErrorMsg());
            exit();
        }else{
            header("location: ../php/contact.php?error=none");
        }

        $stmt->close();
        
        exit();
    }
