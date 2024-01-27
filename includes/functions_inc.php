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



function emptyInputProfile($username, $nome, $phoneNumber, $email)
{
    $result = false;

    // Verificar se todos os campos estão vazios
    if (empty($username) && empty($nome) && empty($phoneNumber) && empty($email)) {
        $result = true;
    }

    return $result;
}



function userExistsProfile($db, $id_clientes, $username, $phoneNumber)
{
    $sql = "SELECT * FROM Clientes WHERE (username = :username OR contacto = :phoneNumber) AND id_clientes != :id_clientes";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        $errorMessage = $db->lastErrorMsg();  // Obter mensagem de erro específica do SQLite
        header("location: ../php/editProfile.php?error=stmtfailed&message=" . urlencode($errorMessage));
        exit();
    }

    $stmt->bindParam(':username', $username, SQLITE3_TEXT);
    $stmt->bindParam(':phoneNumber', $phoneNumber, SQLITE3_TEXT);
    $stmt->bindParam(':id_clientes', $id_clientes, SQLITE3_INTEGER);

    $result = $stmt->execute();

    $row = $result->fetchArray(SQLITE3_ASSOC);

    $stmt->close();

    if ($row) {
        // Usuário com o mesmo username ou phoneNumber já existe, mas não é o próprio usuário
        return $row;
    } else {
        // Nenhum conflito, pode atualizar o perfil
        return false;
    }
}

function updateUser($db, $id_clientes, $username, $nome, $email, $phoneNumber)
{

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
