<?php
include('conn.php'); // Certifique-se de incluir o arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o formulário foi enviado via método POST

    // Obtém os valores dos campos do formulário
    $emailOrPhone = $_POST['email_or_phone'];
    $password = $_POST['password'];

    // Consulta na tabela "Clientes" para verificar os dados de login
    $query = "SELECT * FROM Clientes WHERE (nome_cliente = '$emailOrPhone' OR contacto = '$emailOrPhone') AND pass = '$password'";
    $result = $db->query($query);

    if ($result) {
        if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "Login realizado com sucesso!";
        } else {
            echo "Credenciais inválidas. Por favor, tente novamente.";
        }
    } else {
        echo "Erro ao processar a solicitação. Por favor, tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Claudia & Filhos</title>
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
</head>

<body>

    <div class="container-login active">
        <div class="wrapper">
            <div class="title"><span>LOGIN</span></div>
            <form action="login.php" method="post"> <!-- Adiciona action e method ao formulário -->
                <div class="row">
                    <i class="fas fa-user"></i>
                    <input type="text" name="email_or_phone" placeholder="Email or Phone" required>
                </div>
                <div class="row">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="pass"><a href="#">Forgot password?</a></div>
                <div class="row button">
                    <input type="submit" value="Login">
                </div>
                <div class="signup-link">Not a member? <a href="register.php">Signup now</a></div>
            </form>
        </div>
    </div>
    <div class="controls">
        <a href="index.php">
            <div class="control" data-id="home">
                <i class="fas fa-home"></i>
            </div>
        </a>
        <a href="about.php">
            <div class="control" data-id="about">
                <i class="fas fa-user"></i>
            </div>
        </a>
        <a href="catalogo.php">
            <div class="control" data-id="catalogo">
                <i class="fas fa-book"></i>
            </div>
        </a>
        <a href="contact.php">
            <div class="control " data-id="contact">
                <i class="far fa-envelope-open"></i>
            </div>
        </a>
        <div class="control" data-id="home">
            <i class="fas fa-shopping-cart"></i>
        </div>
    </div>

</body>

</html>