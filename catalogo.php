<?php
include('conn.php');

// Consulta à base de dados para obter os produtos
$result = $db->query('SELECT * FROM Produtos');

// Array para armazenar os produtos obtidos do banco de dados
$catalogos = array();

// Loop para obter os resultados da consulta
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
  // Adiciona cada produto ao array $catalogos
  $catalogos[] = array($row['caminho_imagem'], $row['nome_produto'], $row['desc'],$row['preco']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Claudia & Filhos</title>
  <link rel="stylesheet" href="styles/style.css"/>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
</head>

<body class="main-content">
<main>
    <section class="container active" id="catalogo">
      <div class="catalogos-content">

        <div class="main-title">
          <h2>Our <span>Catalogo</span><span class="bg-text">Catalogo</span></h2>
        </div>
        <div class="catalogos">
          <?php
          // Loop para gerar os catálogos
          foreach ($catalogos as $catalogo) {
          ?>
            <div class="catalogo">
              <img src="<?php echo $catalogo[0]; ?>" alt="" />
              <div class="catalogo-text">
                <h4><?php echo $catalogo[1];?> <?php  echo $catalogo[3]?>€</h4>
                <p><?php echo $catalogo[2]; ?></p>
                <div class="btn-con">
                  <a href="#" class="main-btn">
                    <span class="btn-text">Add</span>
                  </a>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
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
        <i class="fas fa-user"></i>
      </div>
    </a>
    <a href="catalogo.php">
      <div class="control active-btn" data-id="catalogo">
        <i class="fas fa-book"></i>
      </div>
    </a>
    <a href="contact.php">
      <div class="control" data-id="contact">
        <i class="far fa-envelope-open"></i>
      </div>
    </a>
    <a href="profile.php">
      <div class="control" data-id="profile">
        <i class="fas fa-user"></i>
      </div>
    </a>
    <div class="control" data-id="home">
      <i class="fas fa-shopping-cart"></i>
    </div>
  </div>
  <div class="theme-btn">
    <i class="fas fa-adjust"></i>
  </div>
  <script src="js/app.js"></script>
</body>

</html>