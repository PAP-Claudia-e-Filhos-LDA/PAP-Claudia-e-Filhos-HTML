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
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!–– CDN para o toastr ––>
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

          <!–– CDN para os icons ––>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

            <!–– SWEET ALERT––>
              <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">
              <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>

</head>

<body class="main-content">
  <main>
    <section class="container contact active" id="contact">
      <div class="contact-container">
        <div class="main-title">
          <h2>contacta<span>-nos</span><span class="bg-text">Contacto</span></h2>
        </div>
        <div class="contact-content-con">
          <div class="left-contact hidden">
            <h4>Entra em contacto</h4>
            <p>
              Aqui é o lugar ideal para nos contactares caso algo tenha corrido mal ou se precisares de ajuda com alguma coisa !
            </p>
            <div class="contact-info">
              <div class="contact-item">
                <div class="icon">
                  <i class="fas fa-map-marker-alt"></i>
                  <span>Localização:</span>
                </div>
                <p> Portugal, Leiria</p>
              </div>
              <div class="contact-item">
                <div class="icon">
                  <i class="fas fa-envelope"></i>
                  <span>Email:</span>
                </div>
                <p>
                  <span> Claudia&Filhos@gmail.com</span>
                </p>
              </div>
              <div class="contact-item">
                <div class="icon">
                  <i class="fas fa-phone"></i>
                  <span>Número de Telemovel:</span>
                </div>
                <p>
                  <span> 912 345 678</span>
                </p>
              </div>
            </div>
            <div class="contact-icons">
              <div class="contact-icon">
                <a href="www.facebook.com" target="_blank">
                  <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" target="_blank">
                  <i class="fab fa-twitter"></i>
                </a>
                <a href="#" target="_blank">
                  <i class="fab fa-instagram"></i>
                </a>
                <a href="#" target="_blank">
                  <i class="fab fa-youtube"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="right-contact hidden">
            <form action="../includes/process_mensagens.php" method="post" class="contact-form" id="mensagem">
              <div class="input-control">
                <input type="text" name="assunto" required placeholder="Assunto" />
              </div>
              <div class="input-control">
                <textarea name="mensagem" id="" cols="15" rows="8" placeholder="A tua mensagem..."></textarea>
              </div>
              <div class="submit-btn">
                <a href="#" class="main-btn" onclick="enviarFormularioMensagem()">
                  <span type="submit" class="btn-text">Send</span>
                </a>
              </div>
            </form>
          </div>
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

    <div class="control  active-btn" data-id="contact">
      <i class="far fa-envelope-open"></i>
    </div>

    <a href="profile.php">
      <div class="control" data-id="home">
        <i class="fas fa-user"></i>
      </div>
    </a>
  </div>



  <!–– CDN para o JQUERY ––>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!–– CDN para o toast ––>
      <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>

      <!–– javasprit ––>
        <script src="../js/main.js"></script>

        <!–– scrpit para o sweetalert ––>
        <script>
          $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const errorParam = urlParams.get("error");

            if (errorParam === "none") {
              Swal.fire({
                icon: "success",
                title: "Mensagem enviada com sucesso",
                showConfirmButton: false,
                timer: 2500,
                background: "#17191f",
                iconColor: "#fd9c3a",
                customClass: {
                  title: 'text-white'
                }
              });
            }
          });
        </script>
</body>

</html>