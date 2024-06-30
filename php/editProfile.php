<?php
// verificar se esta logado //
session_start();
if (!isset($_SESSION["username"])) {
  header("location: login.php");
  exit();
}
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

    <!–– link para o favicon ––>
      <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

      <!–– link para as fonts ––>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
          rel="stylesheet" />

        <!–– CDN para o toastr ––>
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

          <!–– CDN para os icons––>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
              integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="main-content">
  <section class="container contact active" id="contact">
    <div class="contact-container">
      <div class="main-title">
        <h2>Editar <span>Perfil</span><span class="bg-text">Perfil</span></h2>
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
      <form action="../includes/editProfile_inc.php" method="post" class="contact-form" id="editProfileForm"
        enctype="multipart/form-data">
        <div class="contact-content-con">
          <div class="left-profile">
            <div class="profileImage-container">
              <input type="file" name="profile_image" id="fileInput" class="hidden-input" accept="image/*" />
              <label for="fileInput" class="profileImage-label">
                <img src="<?php echo $imagem_perfil; ?>" alt="Profile Image">
                <span class="change-badge"><i class="fas fa-plus"></i></span> <!-- Ícone de mais da Font Awesome -->
              </label>
            </div>
          </div>

          <div class="right-contact hidden">

            <div class="input-control i-c-2">
              <input type="text" name="username" required placeholder="<?php echo $username; ?>" />
              <input type="text" name="nome" required placeholder="<?php echo $nomeCliente; ?>" />
            </div>
            <div class="input-control i-c-2">

            </div>
            <div class="input-control">
              <input type="text" name="phone_number" required placeholder="<?php echo $contacto; ?>" />
            </div>
            <div class="input-control">
              <input type="email" name="email" required placeholder="<?php echo $email; ?>" />
            </div>
            <div class="submit-btn">
              <a href="#" class="main-btn" onclick="enviarFormulario()">
                <span type="submit" class="btn-text">Atualizar Perfil</span>
              </a>
            </div>
      </form>
    </div>
    </div>
    <?php
      }
  ?>
    </div>
  </section>
  <?php
  if (isset($_GET["error"])) {
    $errorMessage = "";
    $errorType = "Atenção";

    switch ($_GET["error"]) {
      case "invalidusername":
        $errorMessage = "Username inválido!";
        break;
      case "invalidphone":
        $errorMessage = "Número de telemovel inválido!";
        break;
      case "userexists2":
        $errorMessage = "Username ou Telemovel ou Email já existem!";
        break;
      case "emptyinput":
        $errorMessage = "Preencha todos os campos!";
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


  <?php
  if (isset($_GET["error"])) {
    $errorMessage = "";
    $errorType = "Sucesso";

    switch ($_GET["error"]) {
      case "none":
        $errorType = "Sucesso";
        $errorMessage = "Alterações feitas com sucesso!";
        break;
      default:
        break;
    }

    if (!empty($errorMessage)) {
      echo '<script>';
      echo 'document.addEventListener("DOMContentLoaded", function() {';
      echo 'toastr.success("' . $errorMessage . '", "' . $errorType . '", {
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

  <!–– CDN para o JQUERY ––>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!–– CDN para o toast ––>
      <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>

      <!–– Javascript da pagina ––>
        <script src="../js/main.js"></script>

        <!–– Script para enviar form quando innserção de imagem ––>
          <script>
          function handleImageSelection() {
            var fileInput = document.getElementById('fileInput');
            fileInput.addEventListener('change', function() {
              if (fileInput.files.length > 0) {
                enviarFormulario()

              }
            });
          }
          handleImageSelection();
          </script>
</body>

</html>