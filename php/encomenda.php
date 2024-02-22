<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Claudia & Filhos</title>

  <!–– link para style.css ––>
    <link rel="stylesheet" href="../styles/style.css" />

    <!–– link para favicon––>
      <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

      <!–– link para as fonts ––>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!–– CDN para os icons ––>
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

          <!–– CDN do toastr ––>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

            <!–– JQUERY ––>
              <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body class="main-content">
  <main>
    <section class="container  active" id="encomenda">
      <div class="contact-container">
        <div class="main-title">
          <h2> <span>Checkout</span><span class="bg-text">Checkout</span></h2>
        </div>
        <div class="contact-content-con">
          <div class="left-contact hidden">
            <?php
            if (isset($_GET['details']) && isset($_GET['total'])) {
              $cartDetails = json_decode($_GET['details'], true);
              $total = $_GET['total'];

              foreach ($cartDetails as $item) {
                echo "
            <div class='cart-item'>
                <div class='cart-img-box'  class='fancybox' >
                    <img src='{$item['imgSrc']}' alt='{$item['title']}' class='cart-img'>
                </div>
                <div class='detail-box'>
                    <div class='cart-product-title'><h3>{$item['title']} {$item['price']}€</h3></div>

                    <div class='cart-quantity-box'>
                        <label for='quantity'>Quantidade:</label>
                        <input type='text' value='{$item['quantity']}' class='cart-quantity' id='quantity' disabled>
                    </div>
                </div>
               
            </div><br>
            
            
        ";
              }

              // Imprimir o total formatado
              echo "
    <div class='total'>
        <div class='total-title'></div>
        <div >{$total}€</div>
      </div>
    ";
            } else if (!empty($_GET['details']) && !empty($_GET['total']) && count(json_decode($_GET['details'])) > 0) {
              // Não imprima a mensagem se houver itens no carrinho
            } else {
              echo "<div class='empty-cart'>Nenhum item no carrinho.</div>";
            }
            ?>
          </div>
          <div class="right-contact hidden">
            <form action="../includes/process_encomenda.php" method="post" class="contact-form" id="encomendaForm">
              <div class="input-control i-c-2">
                <select required id="tipo-rissois" name="rissois">
                  <option value="" disabled selected>Tipo rissois</option>
                  <option value="no">Não tenho rissois</option>
                  <option value="frito">Frito</option>
                  <option value="congelado">Congelado</option>
                </select>
                <select required id="escolha-levantameto" name="levantameto">
                  <option value="" disabled selected>Levantamento</option>
                  <option value="domicilio">Entrega domicilio</option>
                  <option value="pick">Pick up</option>
                </select>
              </div>
              <div class="input-control">
                <select required id="escolha-pagamento" name="pagamento">
                  <option value="" disabled selected>Metodo pagamento</option>
                  <option value="mao">Em mão</option>
                  <option value="mbway">Mb way</option>
                </select>
              </div>
              <div class="input-control">
                <textarea name="mensagem" id="" cols="15" rows="8" placeholder="O que te intriga?"></textarea>
              </div>
              <div class="submit-btn">
                <a href="#" class="main-btn" onclick="enviarEncomendaForm()">
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
    <a href="catalogo.php">
      <div class="control">
        <i class="fas fa-times"></i>
      </div>
    </a>



  </div>

  <!–– CDN do toastr ––>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>

    <!–– Javascript ––>
      <script src="../js/app.js"></script>

</body>

</html>