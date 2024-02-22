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
  <!–– link para o style.css ––>
    <link rel="stylesheet" href="../styles/style.css" />

    <!–– link para o favicon––>
      <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

      <!–– link para as fonts––>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!–– link para os icons>
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="main-content">
  <main>
    <section class="container active " id="profile">
      <div class="main-title">
        <h2>O teu <span>Perfil</span><span class="bg-text">Perfil</span></h2>
      </div>
      <?php
      include('../includes/profile_inc.php');

      foreach ($user as $info) {
        $username = $info[0];
        $nomeCliente = $info[1];
        $contacto = $info[2];
        $email = $info[3];
        $imagem_perfil = $info[4];
      ?>
        <div class="profile-page hidden">
          <div class="content">
            <div class="content__cover">
              <div class="content__avatar">
                <img src="<?php echo $imagem_perfil; ?>" alt="">
              </div>
              <div class="content__bull">
              </div>
            </div>
            <div class="content__actions">
              <a href="editProfile.php"><i class="fas fa-pencil-alt"></i></a>
            </div>
            <div class="content__title">
              <div class="content__header">
                <h1><?php echo $username; ?></h1>
              </div>
              <div class="content__description">
                <p>Nome: <b><?php echo $nomeCliente; ?></b></p>
                <p>Contacto: <b><?php echo $contacto; ?></b></p>
                <p>Email: <b><?php echo $email; ?></b></p>
              </div>
            <?php
          }
            ?>

            <ul class="content__list">
              <li><span><?php echo $numEncomendas; ?></span>Encomendas</li>
            </ul>
            <div class="content__description">
              <a href="historico.php">Ver suas encomendas</a>
            </div>

            <div class="content__button"><a class="button" href="../includes/logout_inc.php">
                <div class="button__border"></div>
                <div class="button__bg"></div>
                <p class="button__text">Logout</p>
              </a></div>
            </div>

          </div>
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

    <div class="control active-btn" data-id="home">
      <i class="fas fa-user"></i>
    </div>

  </div>

  <!–– jquery––>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!–– CDN toastr>
      <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>

      <!––javascrpit––>
        <script src="../js/main.js"></script>

</body>

</html>