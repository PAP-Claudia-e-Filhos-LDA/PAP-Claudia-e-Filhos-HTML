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
          <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
            rel="stylesheet" />

          <!–– CDN para os icons ––>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
              integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />

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
              <form action='../includes/process_encomenda.php' method='post' class='contact-form' id='encomendaForm'>
                <?php
                if (isset($_GET['details']) && isset($_GET['total'])) {
                  $cartDetails = json_decode($_GET['details'], true);
                  $total = $_GET['total'];

                  foreach ($cartDetails as $index => $item) {
                    echo "
  <div class='cart-item'>
      <div class='cart-img-box' class='fancybox'>
          <img src='{$item['imgSrc']}' alt='{$item['title']}' class='cart-img'>
      </div>
      <div class='detail-box'>
          <div class='cart-product-title'><h3>{$item['title']} {$item['price']}€</h3></div>
          <div class='cart-quantity-box'>
              <label for='quantity'>Quantidade:</label>
              <input type='text' value='{$item['quantity']}' class='cart-quantity' id='quantity' disabled>
          </div>";

                    if (strpos(strtolower($item['title']), 'rissol') !== false) {
                      echo "
          <div class='cart-quantity-box'>
            <select required name='rissois[$index]' class='cart-preference'>
      <option value='' disabled selected>Escolha</option>
      <option value='frito'>Frito ♨️</option>
      <option value='congelado'>Congelado ❄️</option>
  </select>
          </div>";
                    }

                    echo "
      </div>
  </div><br>
  ";
                  }

                  // Imprimir o total formatado
                  echo "
  <div class='total'>
      <div class='total-title'></div>
      <div>{$total}€</div>
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
              <div class="input-control i-c-2">
                <select required id="escolha-levantameto" name="levantameto" onchange="toggleMorada()">
                  <option value="" disabled selected data-icon="⬇️">⬇️ Levantamento</option>
                  <option value="domicilio" data-icon="🏠">🏠 Entrega domicilio</option>
                  <option value="pick" data-icon="📦">📦 Pick up</option>
                </select>
                <select required id="escolha-pagamento" name="pagamento">
                  <option value="" disabled selected data-icon="⬇️">⬇️ Método de pagamento</option>
                  <option value="mao" data-icon="💵">💵 Em mão</option>
                  <option value="mbway" data-icon="💳">💳 Mb way</option>
                </select>
              </div>
              <div class="input-control i-c-2" id="moradaContainer" style="display: none;">
                <textarea required name="distrito" id="distrito" cols="10" rows="1" placeholder="Distrito"></textarea>
                <textarea required name="cod" id="cod" cols="10" rows="1" placeholder="Codigo-Postal"></textarea>
              </div>
              <div class="input-control" id="moradaContainer2" style="display: none;">
                <textarea required name="morada" id="morada" cols="10" rows="1" placeholder="Morada"></textarea>
              </div>

              <div class="input-control">
                <textarea name="mensagem" id="" cols="10" rows="8" placeholder="O que te intriga?"></textarea>
              </div>

              <div class="">
                <i class="fa fa-phone"> </i>
                <span class="mb-way-number">960 017 557</span><br><br>
                <i class="fa fa-home"> </i>
                <span class="mb-way-number">Amor , Rua Da Fé Nº56</span>
              </div>

              <style>
              .mb-logo {
                width: 100px;
                height: 100px;
                margin-right: 20px;
                vertical-align: middle;
              }
              </style>

              <div class="submit-btn">
                <a href="#" class="main-btn" onclick="enviarEncomendaForm()">
                  <span type="submit" class="btn-text">Encomendar</span>
                </a>
              </div>
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
    <script>
    function toggleMorada() {
      const levantamentoSelect = document.getElementById('escolha-levantameto');
      const moradaContainer = document.getElementById('moradaContainer');
      const moradaContainer2 = document.getElementById('moradaContainer2');
      const distritoInput = document.getElementById('distrito');
      const codInput = document.getElementById('cod');
      const moradaInput = document.getElementById('morada');

      if (levantamentoSelect.value === 'domicilio') {
        moradaContainer.style.display = 'flex';
        moradaContainer2.style.display = 'flex';

        // Tornar os campos visíveis requeridos
        distritoInput.required = true;
        codInput.required = true;
        moradaInput.required = true;
      } else {
        moradaContainer.style.display = 'none';
        moradaContainer2.style.display = 'none';

        // Ocultar os campos não visíveis e remover a obrigatoriedade
        distritoInput.required = false;
        codInput.required = false;
        moradaInput.required = false;
      }
    }


    function enviarEncomendaForm() {
      document.getElementById('encomendaForm').submit();
    }
    </script>

    <!–– CDN do toastr ––>
      <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>

      <!–– Javascript ––>
        <script src="../js/main.js"></script>
        <script src="../js/app.js"></script>

  </body>

  </html>