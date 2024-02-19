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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="main-content">
  <main>
    <section class="container  active" id="encomenda">
      <div class="contact-container">
        <div class="main-title">
          <h2> <span>Checkout</span><span class="bg-text">Checkout</span></h2>
        </div>
        <div class="contact-content-con">
          <div class="left-contact hidden" id="cartItemsContainer">
         


          </div>
          <div class="right-contact hidden">
            <form action="../includes/process_encomenda.php" method="post" class="contact-form" id="encomendaForm">
              <div class="input-control i-c-2">
                <select required id="tipo-rissois" name="rissois">
                  <option disabled selected>Tipo rissois</option>
                  <option value="no">Não tenho rissois</option>
                  <option value="frito">Frito</option>
                  <option value="congelado">Congelado</option>
                </select>
                <select required id="escolha-levantameto" name="levantameto">
                  <option disabled selected>Levantamento</option>
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
                <textarea required name="mensagem" id="" cols="15" rows="8" placeholder="O que te intriga?"></textarea>
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
      <div class="control" data-id="home">
        <i class="fas fa-user"></i>
      </div>
    </a>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="../js/app.js"></script>
  <script src="../js/main.js"></script>
  <script>
    // Lógica JavaScript para recuperar e exibir os itens do carrinho
    document.addEventListener('DOMContentLoaded', function () {
  // Recupera os itens do carrinho do Local Storage
  const cartItems = localStorage.getItem("cartItems");
  const parsedCartItems = cartItems ? JSON.parse(cartItems) : [];

  // Container para exibir os itens do carrinho
  const cartItemsContainer = document.getElementById("cartItemsContainer");

  // Itera sobre os itens do carrinho e os exibe
  parsedCartItems.forEach(function (item) {
    const cartItemDiv = document.createElement("div");
    cartItemDiv.className = 'cart-item';

    // Estrutura HTML para cada item do carrinho
    cartItemDiv.innerHTML = `
      <div class='cart-img-box'>
        <img src='${item['imgSrc']}' alt='${item['title']}' class='cart-img'>
      </div>
      <div class='detail-box'>
        <div class='cart-product-title'><h3>${item['title']} ${item['price']}€</h3></div>
        <div class='cart-quantity-box'>
          <label for='quantity'>Quantidade:</label>
          <input type='text' value='${item['quantity']}' class='cart-quantity' id='quantity' disabled>
        </div>
      </div>
      <div class='cart-remove-box'>
        <input type='checkbox' class='cart-checkbox' />
      </div>
    `;

    cartItemsContainer.appendChild(cartItemDiv);
  });
});
    // Seu restante do código JavaScript
  </script>
  <script>
    // Remove os parâmetros da URL após o carregamento da página
    history.replaceState({}, document.title, window.location.pathname);
  </script>
</body>

</html>