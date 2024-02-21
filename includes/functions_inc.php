<?php

use LDAP\Result;

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
    // Se o número estiver vazio, considera como válido
    if (empty($phoneNumber)) {
        return false;
    }

    // Verifica se o número segue o formato correto
    return !preg_match("/^[0-9]{9}$/", $phoneNumber);
}

function userExists($db, $username, $phoneNumber,$email)
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


function createUser($db, $username, $nome, $email, $phoneNumber, $password)
{
    $sql = "INSERT INTO clientes(username, nome_cliente, contacto, email, pass, imagem_perfil) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        header("location: ../php/register.php?error=stmtfailed&msg=" . $db->lastErrorMsg());
        exit();
    }

    // Bind dos parâmetros usando variáveis
    $stmt->bindParam(1, $username, SQLITE3_TEXT);
    $stmt->bindParam(2, $nome, SQLITE3_TEXT);
    $stmt->bindParam(3, $phoneNumber, SQLITE3_TEXT);
    $stmt->bindParam(4, $email, SQLITE3_TEXT);
    $stmt->bindParam(5, $password, SQLITE3_TEXT);

    // Para o sexto parâmetro (imagem_perfil), você pode usar bindValue
    $imagem_perfil = "../img/user.png";
    $stmt->bindValue(6, $imagem_perfil, SQLITE3_TEXT);

    $result = $stmt->execute();

    if (!$result) {
        header("location: ../php/register.php?error=stmtexecutionfailed&msg=" . $db->lastErrorMsg());
        exit();
    }

    $stmt->close();

    // Após o fechamento da declaração, você pode redirecionar
    header("location: ../php/login.php?error=none");
    exit();
}




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
    $userExists = userExists($db, $username, $username);

    if ($userExists === false) {
        header("location: ../php/login.php?error=wronglogin");
        exit();
    }

    // Obtenha a senha armazenada no banco de dados
    $storedPassword = $userExists["pass"];

    // Compare a senha fornecida com a senha armazenada (sem hash)
    if ($password !== $storedPassword) {
        header("location: ../php/login.php?error=wronglogin");
        exit();
    }
    session_start();
    $_SESSION["userid"] = $userExists["id_clientes"];
    $_SESSION["username"] = $userExists["username"];
    header("location: ../php/index.php?error=none");
    exit();
}



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

    // Realize as verificações antes de atualizar os valores no banco de dados

    // Verificação de username inválido
    if (invalidUsername($username) !== false) {
        header("location: ../php/editProfile.php?error=invalidusername");
        exit();
    }

    // Verificação de phoneNumber inválido
    if (invalidPhone($phoneNumber) !== false) {
        header("location: ../php/editProfile.php?error=invalidphone");
        exit();
    }

    // Verificação se o usuário já existe (além do próprio usuário)
    if (userExistsProfile($db, $userId, $username, $phoneNumber, $email) !== false) {
        header("location: ../php/editProfile.php?error=userexists2");
        exit();
    }

    // Atualize os valores no banco de dados apenas se necessário
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

    $imagem_perfil = $user['imagem_perfil'];  // Adicione esta linha para definir a variável
    // Atualize os valores no banco de dados
    updateUser($db, $userId, $username, $nome, $email, $phoneNumber, $imagem_perfil);

    // Retorne false, pois a função está apenas atualizando os valores, não verificando a entrada vazia
    return false;
}






function userExistsProfile($db, $id_clientes, $username, $phoneNumber, $email)
{
    if (empty($email)) {
        return false;
    }
    $sql = "SELECT * FROM Clientes WHERE (username = :username OR contacto = :phoneNumber OR email = :email) AND id_clientes != :id_clientes";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        $errorMessage = $db->lastErrorMsg();  // Obter mensagem de erro específica do SQLite
        header("location: ../php/editProfile.php?error=stmtfailed&message=" . urlencode($errorMessage));
        exit();
    }

    $stmt->bindParam(':username', $username, SQLITE3_TEXT);
    $stmt->bindParam(':phoneNumber', $phoneNumber, SQLITE3_TEXT);
    $stmt->bindParam(':email', $email, SQLITE3_TEXT);  // Use SQLITE3_TEXT para o email
    $stmt->bindParam(':id_clientes', $id_clientes, SQLITE3_INTEGER);
    $result = $stmt->execute();

    $row = $result->fetchArray(SQLITE3_ASSOC);

    $stmt->close();

    if ($row) {
        // Usuário com o mesmo username, phoneNumber ou email já existe, mas não é o próprio usuário
        return $row;
    } else {
        // Nenhum conflito, pode atualizar o perfil
        return false;
    }
}
// Função para atualizar apenas a imagem na base de dados
function updateImage($db, $id_clientes, $imagem_perfil_caminho)
{
    $sql = "UPDATE clientes SET imagem_perfil = ? WHERE id_clientes = ?";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        header("location: ../php/editProfile.php?error=stmtfailed&msg=" . $db->lastErrorMsg());
        exit();
    }

    // Bind dos parâmetros usando variáveis
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
    // ...

    $sql = "UPDATE clientes SET username = ?, nome_cliente = ?, contacto = ?, email = ? WHERE id_clientes = ?";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        header("location: ../php/editProfile.php?error=stmtfailed&msg=" . $db->lastErrorMsg());
        exit();
    }

    // Bind dos parâmetros usando variáveis
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

    // Após o fechamento da declaração, você pode redirecionar
    header("location: ../php/editProfile.php?error=none");
    exit();
}























function createOrder($db, $id_clientes, $metodo_pagamento, $metodo_entrega, $mensagem)
{
    $data_encomenda = date('Y-m-d'); // Obtém a data e hora atual

    // Mapear os valores de $metodo_pagamento e $metodo_entrega para 0 ou 1
    $metodo_pagamento = ($metodo_pagamento == 'mbway') ? 1 : 0;
    $metodo_entrega = ($metodo_entrega == 'domicilio') ? 1 : 0;

    // Utilizando placeholders "?" para os valores na consulta SQL
    $sql = "INSERT INTO Encomendas (id_clientes, data_encomenda, metedo_pagamento, metedo_entrega, mensagem) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        // Tratar erro na preparação da declaração
        die("Erro na preparação da declaração: " . $db->lastErrorMsg());
    }

    // Bind dos parâmetros usando variáveis
    $stmt->bindParam(1, $id_clientes, SQLITE3_INTEGER);
    $stmt->bindParam(2, $data_encomenda, SQLITE3_TEXT);
    $stmt->bindParam(3, $metodo_pagamento, SQLITE3_INTEGER);
    $stmt->bindParam(4, $metodo_entrega, SQLITE3_INTEGER);
    $stmt->bindParam(5, $mensagem, SQLITE3_TEXT);

    $result = $stmt->execute();

    if (!$result) {
        // Tratar erro na execução da declaração
        die("Erro na execução da declaração: " . $db->lastErrorMsg());
    }

    // Obter o ID da última inserção
    $lastId = $db->lastInsertRowID();

    $stmt->close();

    // Retornar o ID da encomenda recém-criada
    return $lastId;
}



function getProductIdByName($db, $nomeProduto)
{
    // Remova espaços em branco extras
    $nomeProduto = trim($nomeProduto);

    // Consultar o ID do produto com base no nome
    $sql = "SELECT id_produto FROM Produtos WHERE nome_produto = ?";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        die("Erro na preparação da declaração: " . $db->lastErrorMsg());
    }

    // Bind do parâmetro usando variável
    $stmt->bindParam(1, $nomeProduto, SQLITE3_TEXT);

    $result = $stmt->execute();

    if (!$result) {
        die("Erro na execução da declaração: " . $db->lastErrorMsg());
    }

    // Obter o ID do produto
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row === false) {
        echo "Produto não encontrado para o nome: $nomeProduto<br>";
        $stmt->close();
        return null;
    }

    $idProduto = $row['id_produto'];

    // Adicione alguns echo para debug
    echo "ID do Produto: $idProduto<br>";

    $stmt->close();

    return $idProduto;
}











function createOrderLine($db, $encomendaId, $tipoRissois, $cartDetails)
{
    echo "Chamando createOrderLine<br>";

    // Preparação da declaração fora do loop
    $sql = "INSERT INTO Linha_de_Encomenda (Encomendas_id_Encomendas, Produtos_id_produto, congelados, quantidade, preco_produto) VALUES (?, ?, ?, ?, (SELECT preco FROM Produtos WHERE id_produto = ?))";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        echo "Erro: Preparação da declaração falhou.<br>";
        return false;
    }

    foreach ($cartDetails as $item) {
        // Verifique se todas as informações necessárias estão presentes
        if (!isset($item['title'], $item['quantity'], $tipoRissois)) {
            echo "Erro: Variáveis necessárias ausentes em um item do carrinho.<br>";
            continue; // Pule para a próxima iteração do loop
        }

        $nomeProduto = $item['title'];
        $quantidade = $item['quantity'];

        echo "Nome do Produto: $nomeProduto<br>";
        echo "Quantidade: $quantidade<br>";
        echo "Tipo: $tipoRissois<br>";
        echo "$tipoRissois <br>";

        // Consultar o ID do produto com base no nome do produto
        $idProduto = getProductIdByName($db, $nomeProduto);

        if ($idProduto === null) {
            // Se o produto não for encontrado, continue para o próximo item
            continue;
        }

        // Adicione o valor de $tipoRissois à coluna 'congelados'
        $congelado = ($tipoRissois == 'congelado') ? 1 : 0;

        // Bind dos parâmetros usando variáveis
        $stmt->bindParam(1, $encomendaId, SQLITE3_INTEGER);
        $stmt->bindParam(2, $idProduto, SQLITE3_INTEGER);
        $stmt->bindParam(3, $congelado, SQLITE3_INTEGER);
        $stmt->bindParam(4, $quantidade, SQLITE3_INTEGER);
        $stmt->bindParam(5, $idProduto, SQLITE3_INTEGER); // O mesmo ID do produto para a subconsulta

        // Execute a declaração dentro do loop
        $result = $stmt->execute();

        if (!$result) {
            echo "Erro: Execução da declaração falhou para o item: $nomeProduto.<br>";
            continue; // Pule para a próxima iteração do loop
        }
    }

    // Feche a declaração fora do loop
    $stmt->close();

    // Retornar o ID da encomenda ou false em caso de erro
    return $encomendaId;
}
