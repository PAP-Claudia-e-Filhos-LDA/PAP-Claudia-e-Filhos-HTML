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
    return !preg_match("/^[a-zA-Z0-9]*$/", $username);
}



function invalidPhone($phoneNumber)
{
    return !preg_match("/^[0-9]{9}$/", $phoneNumber);
}

function userExists($db, $username, $phoneNumber)
{
    $sql = "SELECT * FROM Clientes WHERE nome_cliente = :username OR contacto = :phoneNumber";
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


function createUser($db, $username, $phoneNumber, $password)
{
    $sql = "INSERT INTO clientes(nome_cliente, contacto, pass) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        header("location: ../php/register.php?error=stmtfailed&msg=" . $db->lastErrorMsg());
        exit();
    }


    $stmt->bindParam(1, $username, SQLITE3_TEXT);
    $stmt->bindParam(2, $phoneNumber, SQLITE3_TEXT);
    $stmt->bindParam(3, $password, SQLITE3_TEXT);

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
    $_SESSION["username"] = $userExists["nome_cliente"];
    header("location: ../php/index.php");
    exit();
}
