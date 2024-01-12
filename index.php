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

<body class="main-content">
  <header class="container header active" id="home">
    <div class="header-content">
      <div class="left-header">
        <div class="h-shape"></div>
        <div class="image">
          <img src="img/ewq.png" alt="" />
        </div>
      </div>
      <div class="right-header">
        <h1 class="name">
          Welcome to
          <span>
            Claudia & Filhos</span>
        </h1>
        <p>
          I'm a Web Developer, I love to create beautiful and functional
          websites. Lorem ipsum dolor sit amet consectetur adipisicing elit.
          Officia, libero? Lorem ipsum dolor sit amet consectetur adipisicing
          elit. Neque blanditiis sed aut!
        </p>
        <div class="btn-con">
          <a href="catalogo.php" class="main-btn">
            <span class="btn-text">Catalogo</span>
            <span class="btn-icon"><i class="fas fa-download"></i></span>
          </a>
        </div>
      </div>
    </div>
  </header>

  <div class="controls">
    <a href="index.php">
      <div class="control active-btn" data-id="home">
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
      <div class="control" data-id="contact">
        <i class="far fa-envelope-open"></i>
      </div>
    </a>
    </a>
    <div class="control" data-id="home">
      <i class="fas fa-shopping-cart"></i>
    </div>
  </div>

  <script src="js/app.js"></script>
</body>

</html>