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

function userExists($db, $username, $phoneNumber)
{
    $sql = "SELECT * FROM Clientes WHERE username = :username OR contacto = :phoneNumber";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        header("location: ../php/register.php?error=stmtfailed");
        exit();
    }

    $stmt->bindParam(':username', $username, SQLITE3_TEXT);
    $stmt->bindParam(':phoneNumber', $phoneNumber, SQLITE3_TEXT);
    $stmt->bindParam(':phoneNumber', $phoneNumber, SQLITE3_TEXT);

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
    header("location: ../php/register.php?error=none");
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
