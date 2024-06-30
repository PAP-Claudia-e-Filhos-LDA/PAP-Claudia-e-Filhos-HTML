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


        <!–– CDN para os icons ––>
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
            integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
          <!–– CDN para o swiper ––>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body class="main-content">
  <main>
    <section class="container about active" id="about">
      <div class="main-title ">
        <h2>Sobre <span>Nós</span><span class="bg-text">SOBRE</span></h2>
      </div>
      <div class="about-container ">
        <div class="left-about hidden">
          <h4>Quem somos?</h4>
          <p>
            Somos uma família apaixonada pela arte de cozinhar e encantar paladares com os nossos deliciosos rissois.
            Cada membro da nossa equipa traz consigo uma paixão única pela culinária, dedicando-se a proporcionar
            experiências gastronómicas memoráveis a todos os nossos clientes.
            <br />
            <br />
            A nossa jornada na cozinha vai além de um simples trabalho; é uma expressão de amor e dedicação. Acreditamos
            que a comida tem o poder de criar laços e momentos especiais, e é por isso que nos esforçamos por fazer cada
            cliente feliz através dos sabores irresistíveis dos nossos rissois.
          </p>
          <div class="btn-con">
            <a href="catalogo.php" class="main-btn">
              <span class="btn-text">Catalogo</span>
              <span class="btn-icon"><i class="fas fa-utensils"></i></span>
            </a>
          </div>
        </div>
        <div class="right-about hidden">
          <div class="about-item">
            <div class="abt-text">
              <p class="large-text">3000+</p>
              <p class="small-text">
                Rissois <br />
                feitos
              </p>
            </div>
          </div>
          <div class="about-item">
            <div class="abt-text">
              <p class="large-text">10+</p>
              <p class="small-text">
                Anos de <br />
                experiência
              </p>
            </div>
          </div>
          <div class="about-item">
            <div class="abt-text">
              <p class="large-text">300+</p>
              <p class="small-text">
                Clientes <br />
                Felizes
              </p>
            </div>
          </div>
          <div class="about-item">
            <div class="abt-text">
              <p class="large-text">50+</p>
              <p class="small-text">
                Opiniões de <br />
                Clientes
              </p>
            </div>
          </div>
        </div>
      </div>
      <?php include('../includes/destaques_inc.php'); ?>
      <div class="main-title hidden">
        <h2>Best <span>Sellers</span><span class="bg-text"></span></h2>
      </div>


      <div class="catalogos hidden">
        <?php foreach ($topOrderedProducts as $product) : ?>
        <div class="catalogo hidden">
          <a href="../php/catalogo.php" class="fancybox" data-caption="">
            <img src="<?php echo htmlspecialchars($product['caminho_imagem']); ?>"
              alt="<?php echo htmlspecialchars($product['nome_produto']); ?>" />
          </a>
          <div class="catalogo-text">
            <h4><?php echo htmlspecialchars($product['nome_produto']); ?></h4>
            <h3>Total Encomendado: <?php echo $product['total_encomendado']; ?></h3>
            <div class="btn-con">
              <!-- Se houver necessidade de botões ou links adicionais, podem ser adicionados aqui -->
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div><br><br>
      <div class="testimonals hidden">
        <div class="container-testimonal">
          <div class="stat-title testimonal-header">
            <div class="main-title">
              <h2>o que falam de <span>Nós</span><span class="bg-text"></span></h2>
            </div>
          </div>
          <div class="testimonal-content">
            <div class="swiper testimonals-slider  js-testimonal-slider">
              <div class="swiper-wrapper">
                <div class="swiper-slide testimonals-item">
                  <div class="info">
                    <img src="../img/testimonals/eric.jpeg" alt="">
                    <div class="text-box">
                      <h3 class="name">Eric Fragona</h3>
                      <span class="job">trabalhador/Estudante</span>
                    </div>
                  </div>
                  <p>Tive uma experiência incrível com o atendimento da empresa. A equipa demonstrou um compromisso
                    genuíno com a satisfação do cliente, fazendo-me sentir valorizado. A comida também foi uma agradável
                    surpresa, superando todas as minhas expectativas. Recomendo vivamente!</p>
                  <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                  </div>
                </div>
                <div class="swiper-slide testimonals-item">
                  <div class="info">
                    <img src="../img/testimonals/reis.jpeg" alt="">
                    <div class="text-box">
                      <h3 class="name">João Reis</h3>
                      <span class="job">Estudante</span>
                    </div>
                  </div>
                  <p>Gostei bastante do rissol de camrão porem não recomendo a ninguem, quando comi queimei me e fui
                    para o hospital, mas do resto tudo muito bom !</p>
                  <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                  </div>
                </div>
                <div class="swiper-slide testimonals-item">
                  <div class="info">
                    <img src="../img/testimonals/tiagoN.jpeg" alt="">
                    <div class="text-box">
                      <h3 class="name">Tiago Nazário</h3>
                      <span class="job">trabalhador/Estudante</span>
                    </div>
                  </div>
                  <p>Embora tenha tido uma experiência geral positiva, acho que há margem para melhorias. O atendimento
                    foi bom, mas poderia ser mais eficiente em alguns aspectos. A qualidade da comida estava acima da
                    média, mas algumas opções no menu poderiam ser mais diversificadas</p>
                  <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>

                  </div>
                </div>
                <div class="swiper-slide testimonals-item">
                  <div class="info">
                    <img src="../img/testimonals/pedro.jpeg" alt="">
                    <div class="text-box">
                      <h3 class="name">Pedro Azevedo</h3>
                      <span class="job">Estudante</span>
                    </div>
                  </div>
                  <p>O atendimento foi excepcional, refletindo o comprometimento genuíno da equipa com a satisfação do
                    cliente. A comida surpreendeu com sabores incríveis, superando todas as expectativas. Recomendo
                    vivamente, uma experiência que vale cada estrela</p>
                  <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                  </div>
                </div>
                <div class="swiper-slide testimonals-item">
                  <div class="info">
                    <img src="../img/testimonals/tiagoS.jpeg" alt="">
                    <div class="text-box">
                      <h3 class="name">Tiago Figueredo</h3>
                      <span class="job">Estudante</span>
                    </div>
                  </div>
                  <p>Os rissóis são simplesmente fenomenais! A crocância da casca faz uma combinação perfeita com os
                    recheios que são de deixar água na boca. É como uma explosão de sabores em cada mordida. Fiquei
                    realmente impressionado!</p>
                  <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                  </div>
                </div>
                <div class="swiper-slide testimonals-item">
                  <div class="info">
                    <img src="../img/testimonals/tyzh.jpeg" alt="">
                    <div class="text-box">
                      <h3 class="name">Tyzh</h3>
                      <span class="job">Estudante</span>
                    </div>
                  </div>
                  <p>As sobremesas são um verdadeiro deleite. Cada pedaço é uma tentação irresistível, com texturas que
                    se complementam e sabores que conquistam o coração. Não consigo escolher minha favorita, todas são
                    simplesmente divinas. Uma experiência gastronómica que definitivamente vale a pena experimentar de
                    novo!</p>
                  <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                  </div>
                </div>
                <div class="swiper-slide testimonals-item">
                  <div class="info">
                    <img src="../img/testimonals/luis.jpeg" alt="">
                    <div class="text-box">
                      <h3 class="name">Luis Inacio</h3>
                      <span class="job">Estudante</span>
                    </div>
                  </div>
                  <p>Para começar, gostei bastante do atendimento. Nota-se que são pessoas que se importam com o
                    atendimento aos clientes. Comida também bastante boa. Todas as expectativas que possam ter vão ser
                    certamente superadas!</p>
                  <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-pagination js-testimonals-pagination"></div>
        </div>
      </div>
      </div>





      <div class="main-title hidden">
        <h2>Melhores <span>Compradores</span><span class="bg-text"></span></h2>
      </div>



      <table class="leaderboard hidden">
        <thead>
          <tr>
            <th>Posição</th>
            <th>Foto</th>
            <th>Nome</th>
            <th>Encomendas</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $position = 1;
          foreach ($topBuyers as $buyer) {
            echo "<tr>";
            echo "<td>{$position}</td>";
            echo "<td><img src='" . htmlspecialchars($buyer['imagem_perfil']) . "' alt='" . htmlspecialchars($buyer['nome_cliente']) . "'></td>";
            echo "<td>" . htmlspecialchars($buyer['nome_cliente']);

            // Adicionando coroa ao primeiro colocado
            if ($position === 1) {
              echo " 👑"; // Emoji de coroa
            }

            echo "</td>";
            echo "<td>" . htmlspecialchars($buyer['numEncomendas']) . "</td>";
            echo "</tr>";
            $position++;
          }
          ?>
        </tbody>
      </table>
    </section>
  </main>


  <div class="controls">
    <a href="index.php">
      <div class="control" data-id="home">
        <i class="fas fa-home"></i>
      </div>
    </a>

    <div class="control active-btn" data-id="about">
      <i class="fas fa-book"></i>
    </div>

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
      <div class="control" data-id="home">
        <i class="fas fa-user"></i>
      </div>
    </a>
  </div>
  <!–– script para o swiper ––>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!–– link para costumizar o swiper ––>
      <script src="../js/app.js"></script>
      <!–– script para o javascript>
        <script src="../js/main.js"></script>
</body>

</html>