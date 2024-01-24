<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION["username"])) {
  // Se não estiver logado, redirecione para a página de login
  header("location: login.php");
  exit();
}

// Se chegou aqui, o usuário está logado e pode acessar a página
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Claudia & Filhos</title>
  <link rel="stylesheet" href="../styles/style.css" />
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
</head>

<body class="main-content">
  <main>
    <section class="container contact active" id="contact">
      <div class="contact-container">
        <div class="main-title">
          <h2>Editar <span>Perfil</span><span class="bg-text">Perfil</span></h2>
        </div>
        <?php
        include('../includes/profile_inc.php');

        // Loop para obter os resultados da consulta
        foreach ($user as $info) {
          // Informações do cliente recebidas do banco de dados
          $username = $info[0];
          $nomeCliente = $info[1];
          $contacto = $info[2];
          $email = $info[3];
          $imagem_perfil = $info[4];
        ?>
          <div class="contact-content-con">
            <div class="left-profile ">
              <div class="profileImage-container">
                <input type="file" id="fileInput" class="hidden-input" accept="image/*" />
                <label for="fileInput" class="profileImage-container">
                  <img src="<?php echo $imagem_perfil; ?>" alt="Profile Image">
              </div>
            </div>
            <div class="right-contact hidden">
              <form action="" class="contact-form">
                <div class="input-control">
                  <input type="text" required placeholder="<?php echo $username; ?>" />
                </div>
                <div class="input-control">
                  <input type="text" required placeholder="<?php echo $nomeCliente; ?>" />
                </div>
                <div class="input-control">
                  <input type="text" required placeholder="<?php echo $contacto; ?>" />
                </div>
                <div class="input-control">
                  <input type="text" required placeholder="<?php echo $email; ?>" />
                </div>
                <div class="submit-btn">
                  <a href="#" class="main-btn">
                    <span class="btn-text">Send</span>
                  </a>
                </div>
              </form>
            </div>
          </div>
      </div>
    <?php
        }
    ?>
    </section>
  </main>

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
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="../js/app.js"></script>
  <script src="../js/main.js"></script>
</body>

</html>