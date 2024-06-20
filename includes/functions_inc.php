<?php

use LDAP\Result;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../vendor/autoload.php';

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

function createUser($db, $username, $nome, $email, $phoneNumber, $password)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'claudia.filhos.lda@gmail.com';
        $mail->Password = 'dzhy nwoe iqox zjqp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;



        $mail->CharSet = 'UTF-8';

        $mail->setFrom('claudia.filhos.lda@gmail.com', 'Claudia');
        $mail->addAddress($email, $nome);

        $mail->isHTML(true);
        $mail->Subject = 'Assunto do Email';
        $url = 'http://127.0.0.1/PAP-Claudia-e-Filhos-LDA/php/index.php';
        $body = '
        <!DOCTYPE html>
        <html lang="pt">
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Confirmação de Encomenda</title>
        </head>
        <body style="font-family: \'Poppins\', sans-serif; line-height: 1.6; margin: 0; padding: 20px; color: white; background-color: #ffffff;">
            <div class="container" style="max-width: 600px; margin: 20px auto; background-color: #17191f; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <h2 style="color: #fd9c3a; font-size: 28px; margin-bottom: 20px; text-align: center;">Olá, ' . $nome . ' 🎉</h2>
                <p style="font-size: 16px; line-height: 1.8; margin-bottom: 20px; color: white; text-align: justify;">O teu registo foi concluído com sucesso no nosso site!😊</p>
                <p style="font-size: 16px; line-height: 1.8; margin-bottom: 20px; color: white; text-align: justify;">
                    Agora podes começar a explorar e a fazer encomendas deliciosas através do nosso site. Temos uma variedade incrível de sobremesas e salgados para todos os gostos! 🍰🥐
                </p>
                <p style="font-size: 16px; line-height: 1.8; margin-bottom: 20px; color: white; text-align: justify;">
                    Para começares a tua jornada culinária connosco, clica no botão abaixo:
                </p>
                <center><b><a href="' . $url . '" class="btn" style="display: inline-block; background-color: #fd9c3a; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 5px; font-weight: bold; text-align: center; transition: background-color 0.3s ease;">Explorar Website 🚀</a></b></center>
                <p class="footer" style="text-align: center; font-size: 14px; color: white; margin-top: 20px;">
                    Obrigado por te juntares <a href="#" style="color: #fd9c3a; text-decoration: none;"><b>a nós! </b></a>
    
                </p>
                        </div>
                    </body>
                    </html>
                        ';

        $mail->Body = $body;

        // Envia o email
        if ($mail->send()) {
        } else {
            echo 'Erro ao enviar o email: ' . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo "Erro ao enviar o email: {$mail->ErrorInfo}";
    }




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

    foreach ($cartDetails as $index => $item) {
        if (!isset($item['title'], $item['quantity'])) {
            echo "Erro: Variáveis necessárias ausentes em um item do carrinho.<br>";
            continue;
        }

        $nomeProduto = $item['title'];
        $quantidade = $item['quantity'];

        $idProduto = getProductIdByName($db, $nomeProduto);
        if (strpos(strtolower($nomeProduto), 'rissol') !== false) {
            $congelado = isset($tipoRissois[$index]) && $tipoRissois[$index] == 'congelado' ? 1 : 0;
        } else {
            $congelado = null;
        }

        $stmt->bindParam(1, $encomendaId, PDO::PARAM_INT);
        $stmt->bindParam(2, $idProduto, PDO::PARAM_INT);
        $stmt->bindParam(3, $congelado, PDO::PARAM_INT);
        $stmt->bindParam(4, $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(5, $idProduto, PDO::PARAM_INT);

        $result = $stmt->execute();

        if (!$result) {
            echo "Erro: Execução da declaração falhou para o item: $nomeProduto.<br>";
            continue;
        }
    }

    $stmt->close();
    $userId = $_SESSION["userid"];


    $query = "SELECT * FROM Clientes WHERE id_clientes = :userid";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':userid', $userId, SQLITE3_INTEGER);
    $result = $stmt->execute();


    $user = array();


    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $user[] = array($row['username'], $row['nome_cliente'], $row['contacto'], $row['email'], $row['imagem_perfil']);
    }

    foreach ($user as $info) {
        $nome = $info[1];
        $email = $info[3];
    }


    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'claudia.filhos.lda@gmail.com';
        $mail->Password = 'dzhy nwoe iqox zjqp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;



        $mail->CharSet = 'UTF-8';

        $mail->setFrom('claudia.filhos.lda@gmail.com', 'Claudia');
        $mail->addAddress($email, $nome);


        $mail->isHTML(true);
        $mail->Subject = 'Assunto do Email';
        $url = 'http://127.0.0.1/PAP-Claudia-e-Filhos-LDA/php/historico.php';
        $userOrders = getUserOrders($userId, $db);
        $lastOrder = end($userOrders);


        $total = 0;

        $orderHtml = "<div class='order' style='color: white;'>";
        $orderHtml .= "<center><h1 style='color: white; font-size: 2rem;'>Número da Encomenda: " . $lastOrder['id_Encomendas'] . "</h1></center>";

        foreach ($lastOrder['items'] as $item) {
            $cid = 'produto_' . uniqid();

            $mail->AddEmbeddedImage($item['caminho_imagem'], $cid);
            $orderHtml .= "<div class='cart-item' style='display: grid; grid-template-columns: 100px 1fr auto; align-items: center; gap: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.35); padding-top: 1rem; padding-bottom: 1rem;'>";
            $orderHtml .= "<div class='cart-product-title' style='font-size: 1rem; text-transform: uppercase; color: white;'><h3>{$item['nome_produto']} {$item['preco']}€</h3></div>";
            $orderHtml .= "<div class='cart-img-box' style='width: 150px; height: 150px; overflow: hidden; margin-bottom: 40px;border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);'>";
            $orderHtml .= "<img src='cid:{$cid}' alt='{$item['nome_produto']}' class='cart-img' style='width: 100%; height: 100%; object-fit: cover;'>";
            $orderHtml .= "</div>";
            $orderHtml .= "<div class='detail-box' style='display: grid; row-gap: 0.5rem;'>";

            $orderHtml .= "<div class='cart-quantity-box' style='color: white;'>";
            $orderHtml .= "<label for='quantity' style='font-size: 1rem; font-weight: 500; color:white;'>Quantidade:</label>";
            $orderHtml .= "<input type='text' value='{$item['quantidade']}' class='cart-quantity' id='quantity' disabled style='border: 1px solid #fd9c3a; background-color: #17191f; color: white; width: 2.4rem; text-align: center; font-size: 1rem; padding: 0.5rem; border-radius: 5px; margin-left: 0.5rem;'>";
            $orderHtml .= "</div>";
            $orderHtml .= "</div>";
            $orderHtml .= "</div>";
            $subtotal = $item['preco'] * $item['quantidade'];
            $total += $subtotal;
        }

        $orderHtml .= "<div style='display: flex; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1rem; transition: all 0.3s ease; border-top: 1px solid #fd9c3a; color: white;'>";
        $orderHtml .= "<div style='font-size: 1rem; font-weight: 600; color: white;'>Total: {$total}€</div>";
        $orderHtml .= "</div>";

        $orderHtml .= "</div>";

        echo $orderHtml;
        // die;              


        $body = '
    <!DOCTYPE html>
    <html lang="pt">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Confirmação de Encomenda</title>
    </head>
    <body style="font-family: \'Poppins\', sans-serif; line-height: 1.6; margin: 0; padding: 20px; color: white; background-color: #ffffff;">
        <div class="container" style="max-width: 600px; margin: 20px auto; background-color: #17191f; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <h2 style="color: #fd9c3a; font-size: 28px; margin-bottom: 20px; text-align: center;">Olá, ' . $nome . ' 🎉</h2>
            <p style="font-size: 16px; line-height: 1.8; margin-bottom: 20px; color: white; text-align: justify;">A tua encomenda foi realizada com sucesso! 😊</p>
            <p style="font-size: 16px; line-height: 1.8; margin-bottom: 20px; color: white; text-align: justify;">
                Obrigado por fazeres uma encomenda connosco. Estamos ansiosos para preparar e entregar os teus itens deliciosos! 🍰🥐
            </p>
            <p style="font-size: 16px; line-height: 1.8; margin-bottom: 20px; color: white; text-align: justify;">
                Podes acompanhar o estado da tua encomenda clicando no botão abaixo:
            </p>
            <center><a href="' . $url . '" class="btn" style="display: inline-block; background-color: #fd9c3a; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 5px; font-weight: bold; text-align: center; transition: background-color 0.3s ease;">Acompanhar Encomenda 🚀</a></center>
            <p class="footer" style="text-align: center; font-size: 14px; color: white; margin-top: 20px;">
                Se tiveres alguma dúvida, não hesites em <a href="#" style="color: #fd9c3a; text-decoration: none;"><b>contactar-nós! </b></a>

            </p>
            ' . $orderHtml . '
        </div>
    </body>
</html>
';

        $mail->Body = $body;

        $mail->send();
        echo 'Mensagem enviada com sucesso';
    } catch (Exception $e) {
        echo "A mensagem não pôde ser enviada. Mailer Error: {$mail->ErrorInfo}";
    }
    return $encomendaId;
}



// ------------------  Function get orders ---------------------
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
function messageSend($db, $userId, $assuntoId, $mensagem)
{
    $data_mensagem = date('Y-m-d');

    $sql = "INSERT INTO Mensagens_Clientes (id_cliente, id_assunto, mensagem, data_mensagem) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);

    if (!$stmt) {
        die("Erro na preparação da declaração: " . $db->lastErrorMsg());
    }

    $stmt->bindParam(1, $userId, SQLITE3_INTEGER);
    $stmt->bindParam(2, $assuntoId, SQLITE3_INTEGER);
    $stmt->bindParam(3, $mensagem, SQLITE3_TEXT);
    $stmt->bindParam(4, $data_mensagem, SQLITE3_TEXT);

    $result = $stmt->execute();

    if (!$result) {
        die("Erro na execução da declaração: " . $db->lastErrorMsg());
    }

    $stmt->close();
    header("location: ../php/contact.php?error=none");
    exit();
}
