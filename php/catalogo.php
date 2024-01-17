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
      <div class="cart">
        <h2 class="cart-title">Carrinho</h2>

        <div class="cart-content">
          <div class="cart-box">

          </div>
        </div>
        <div class="total">
          <div class="total-title">Total</div>
          <div class="total-price">€0</div>
        </div>
        <button type="button" class="btn-buy">Encomendar Agora</button>
        <i class="fas fa-times" id="close-cart"></i>

      </div>
      <section class="container active" id="catalogo">
        <div class="catalogos-content">
          <div class="main-title">
            <h2>O nosso <span>Catalogo</span><span class="bg-text">Catalogo</span></h2>
          </div>

          <div class="search-box">
            <form method="GET" action="catalogo.php">
              <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
              <input type="text" name="search" class="input-search" placeholder="Procure alguma coisa..." />
            </form>
          </div>
          <div class="catalogos">
            <?php
            include('../includes/catalogo_inc.php');

            // Obtém o valor do parâmetro de pesquisa, se estiver presente
            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

            // Loop para gerar os catálogos com filtro de pesquisa
            foreach ($catalogos as $catalogo) {
              // Se não houver termo de pesquisa ou o termo estiver contido no nome do item
              if (empty($searchTerm) || stripos($catalogo[1], $searchTerm) !== false) {
            ?>
                <div class="catalogo">
                  <img src="<?php echo $catalogo[0]; ?>" alt="" />
                  <div class="catalogo-text">
                    <h4><?php echo $catalogo[1]; ?></h4>
                    <h3><?php echo $catalogo[3] ?>€</h3>
                    <p><?php echo $catalogo[2]; ?></p>
                    <div class="btn-con">
                      <a class="main-btn add-cart">
                        <span class="btn-text">Add</span>
                      </a>
                    </div>
                  </div>
                </div>
            <?php
              }
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
          <i class="fas fa-book"></i>
        </div>
      </a>
      <a href="catalogo.php">
        <div class="control active-btn" data-id="catalogo">
          <i class="fas fa-lemon"></i>
        </div>
      </a>
      <a href="contact.php">
        <div class="control" data-id="contact">
          <i class="far fa-envelope-open"></i>
        </div>
      </a>
      <a href="profile.php">
        <div class="control" data-id="home">
          <i class="fas fa-user"></i>
        </div>
      </a>
    </div>

    <div class="profile-btn" id="cart-icon">
      <i class="fas fa-shopping-cart"> </i>
      <div style="display: none;" class="cart-badge"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../js/main.js"></script>
  </body>

  </html>