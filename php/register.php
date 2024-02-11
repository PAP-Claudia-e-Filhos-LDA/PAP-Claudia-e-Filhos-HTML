<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Claudia & Filhos</title>
    <link rel="stylesheet" href="../styles/login.css" />
    <link rel="stylesheet" href="../styles/style.css" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
</head>

<body class="body-login">

    <div class="container-loggin side hidden">
        <img src="./images/img.svg" alt="">
    </div>

    <div class="container-loggin main hidden">
        <div class="login-container">
            <p class="title">Regista-te agora</p>
            <div class="separator"></div>
            <p class="welcome-message">Preenche os campos com a tua informação</p>

            <form action="../includes/process_register.php" class="login-form" method="post">
                <div class="form-control">
                    <input type="text" name="username" placeholder="Username">
                    <i class="fas fa-user"></i>
                </div>
                <div class="form-control">
                    <input type="text" name="nome" placeholder="Nome">
                    <i class="fas fa-user"></i>
                </div>
                <div class="form-control">
                    <input type="text" name="phone_number" placeholder="Telemóvel">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="form-control">
                    <input type="text" name="email" placeholder="Email (opcional)">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="form-control">
                    <input type="password" name="password" placeholder="Password">
                    <i class="fas fa-lock"></i>
                </div>
                <p class="welcome-message">Tens conta? <a href="login.php">Login!</a></p>
                <button class="submit">Register</button>
            </form>
        </div>
    </div>

    <?php
    if (isset($_GET["error"])) {
        $errorMessage = "";
        $errorType = "Atenção";
        switch ($_GET["error"]) {
            case "ivalidusername":
                $errorMessage = "Escolha um username válido!";
                break;
            case "emptyinput":
                $errorMessage = "Preencha todos os campos!";
                break;
            case "ivalidphone":
                $errorMessage = "Número de telefone inválido!";
                break;
            case "userexists":
                $errorMessage = "Dados inseridos já existem!";
                break;
            case "stmtfailded":
                $errorMessage = "Erro desconhecido!";
                break;
            default:
                break;
        }

        if (!empty($errorMessage)) {
            echo '<script>';
            echo 'document.addEventListener("DOMContentLoaded", function() {';
            echo 'toastr.warning("' . $errorMessage . '", "' . $errorType . '", {
            closeButton: false,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 3000,
            extendedTimeOut: 1000,
            preventDuplicates: false,
            newestOnTop: false,
            showDuration: 300,
            hideDuration: 300,
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "slideDown",
            hideMethod: "slideUp",
            toastClass: "custom-toast-class"
        });';
            echo '});';
            echo '</script>';
        }
    }
    ?>

    <div class="controls">
        <a href="index.php">
            <div class="control" data-id="home">
                <i class="fas fa-home"></i>
            </div>
        </a>
        <a href="about.php">
            <div class="control" data-id="about">
                <i class="fas fa-book"></i>
            </div>
        </a>
        <a href="catalogo.php">
            <div class="control" data-id="catalogo">
                <i class="fas fa-lemon"></i>
            </div>
        </a>
        <a href="contact.php">
            <div class="control" data-id="contact">
                <i class="far fa-envelope-open"></i>
            </div>
        </a>
        <a href="profile.php">
            <div class="control active-btn" data-id="home">
                <i class="fas fa-user"></i>
            </div>
        </a>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
    <script src="../js/main.js"></script>
</body>

</html>