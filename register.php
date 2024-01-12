<?php
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $phoneNumber = $_POST['phone_number'];
    $password = $_POST['password'];

    $insertQuery = "INSERT INTO Clientes (nome_cliente, contacto, pass) VALUES ('$username', '$phoneNumber', '$password')";
    $result = $db->exec($insertQuery);

    if ($result) {
        header("location:index.php");
    } else {
        echo "Erro ao registrar. Por favor, tente novamente.";
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
            <div class="title"><span>REGISTER</span></div>
            <form action="register.php" method="post">
                <div class="row">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="row">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="phone_number" placeholder="Phone Number" required>
                </div>
                <div class="row">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="pass"><a href="#">Forgot password?</a></div>
                <div class="row button">
                    <input type="submit" value="Register">
                </div>
                <div class="signup-link">A member? <a href="login.php">Login now</a></div>
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