// Aguarda o carregamento completo do documento HTML
$(document).ready(function () {
  // Manipula a entrada no campo de pesquisa
  $(".input-search").on("input", function () {
    var searchTerm = $(this).val().toLowerCase();

    // Itera sobre os elementos com a classe 'catalogo'
    $(".catalogo").each(function () {
      var itemName = $(this).find(".catalogo-text h4").text().toLowerCase();
      var display = itemName.includes(searchTerm) || searchTerm === "";

      $(".btn-buy").on("click", function () {
        orderNow();
      });

      // Exibe ou oculta o elemento com base no termo de pesquisa
      $(this).toggle(display);
    });
  });

  // Inicializa um conjunto para armazenar os títulos dos produtos no carrinho
  var cartProductTitles = new Set();

  // Seleciona os elementos relevantes no DOM
  let cartIcon = $("#cart-icon");
  let cart = $(".cart");
  let closeCart = $("#close-cart");

  cartIcon.on("click", function () {
    cart.toggleClass("active");
  });

  closeCart.on("click", function () {
    cart.removeClass("active");
  });

  ready();

  function ready() {
    // Seleciona os botões de remoção no carrinho e adiciona ouvintes de evento
    var removeCartButtons = document.getElementsByClassName("cart-remove");
    for (var i = 0; i < removeCartButtons.length; i++) {
      var button = removeCartButtons[i];
      button.addEventListener("click", removeCartItem);
    }

    // Seleciona os campos de quantidade no carrinho e adiciona ouvintes de evento
    var quantityInputs = document.getElementsByClassName("cart-quantity");
    for (var i = 0; i < quantityInputs.length; i++) {
      var input = quantityInputs[i];
      input.addEventListener("change", quantityChange);
    }

    // Seleciona os botões de adicionar ao carrinho e adiciona ouvintes de evento
    var addCart = document.getElementsByClassName("add-cart");
    for (var i = 0; i < addCart.length; i++) {
      var button = addCart[i];
      button.addEventListener("click", addCartClicked);
    }
  }

  // Função chamada ao remover um item do carrinho
  function removeCartItem(event) {
    var buttonClicked = event.target;
    var cartBox = $(buttonClicked).closest(".cart-box"); // Encontra o ancestral .cart-box

    // Encontra o título do produto dentro do cartBox
    var title = cartBox.find(".cart-product-title").text();

    // Adiciona classe de animação de saída
    cartBox.addClass("slide-out");

    // Aguarda o término da animação e remove o elemento do DOM
    cartBox.on("animationend", function () {
      cartBox.remove();
      updateTotal();
      updateCartBadge();

      // Remove o título do produto do conjunto
      cartProductTitles.delete(title);
    });
  }

  // Função chamada ao alterar a quantidade de um item no carrinho
  function quantityChange(event) {
    var input = event.target;
    if (isNaN(input.value) || input.value <= 0) {
      input.value = 1;
    }
    updateTotal();
  }

  // Função chamada ao clicar no botão de adicionar ao carrinho
  function addCartClicked() {
    var button = event.target;
    var shopProducts = $(button).closest(".catalogo");
    var title = shopProducts.find(".catalogo-text h4").text();

    if (cartProductTitles.has(title)) {
      // Utiliza Toastr.js para exibir uma notificação toast personalizada
      toastr.warning("Este produto já está no carrinho!", "Atenção", {
        closeButton: false, // Mostrar botão de fechar
        progressBar: true, // Mostrar barra de progresso
        positionClass: "toast-bottom-left", // Posição da notificação (ver opções na documentação)
        timeOut: 3000, // Tempo de exibição em milissegundos
        extendedTimeOut: 1000, // Tempo adicional para a barra de progresso em milissegundos
        preventDuplicates: false, // Evitar notificações duplicadas
        newestOnTop: false, // Adicionar novas notificações no topo
        showDuration: 300, // Duração da animação de exibição
        hideDuration: 300, // Duração da animação de ocultação
        showEasing: "swing", // Efeito de exibição
        hideEasing: "linear", // Efeito de ocultação
        showMethod: "slideDown", // Slide para baixo
        hideMethod: "slideUp",
        toastClass: "custom-toast-class",
      });
      return;
    }

    var priceText = shopProducts.find(".catalogo-text h3").text();
    var price = parseFloat(priceText.replace("€", "").replace(",", ".")) || 0;
    var productImg = shopProducts.find("img").attr("src");

    addProductToCart(title, price, productImg);
    updateTotal();
    updateCartBadge();

    cartProductTitles.add(title);
    cart.addClass("active");
  }

  // Função para atualizar o número exibido no distintivo do carrinho
  function updateCartBadge() {
    var cartItems = $(".cart-box");
    var cartBadge = $(".cart-badge");

    // Filtra os elementos vazios do carrinho antes de contar
    cartItems = cartItems.filter(function () {
      return $(this).children().length > 0;
    });

    var itemCount = cartItems.length;

    // Atualiza o número no distintivo do carrinho
    if (itemCount > 0) {
      cartBadge.css("display", "block");
      cartBadge.text(itemCount);
    } else {
      cartBadge.css("display", "none");
    }
  }

  // Função para adicionar um produto ao carrinho
  function addProductToCart(title, price, productImg) {
    var cartShopBox = $("<div>").addClass("cart-box slide-in");

    // Conteúdo HTML da caixa do carrinho
    var cartBoxContent = `
          <div class="cart-item">
              <div class="cart-img-box">
                  <img src="${productImg}" alt="" class="cart-img">
              </div>
              <div class="detail-box">
                  <div class="cart-product-title">${title}</div>
                  <div class="cart-price">${price}€</div>
                  <div class="cart-quantity-box">
                      <label for="quantity">Quantidade:</label>
                      <input type="text" value="1" class="cart-quantity" id="quantity">
                  </div>
              </div>
              <div class="cart-remove-box">
                  <i class="fas fa-trash-alt cart-remove"></i>
              </div>
          </div>
      `;

    // Adiciona o conteúdo à caixa do carrinho e insere no DOM
    cartShopBox.html(cartBoxContent);
    $(".cart-content").append(cartShopBox);

    // Adiciona ouvintes de evento para remoção e alteração de quantidade
    cartShopBox.find(".cart-remove").on("click", removeCartItem);
    cartShopBox.find(".cart-quantity").on("change", quantityChange);
  }

  // Função para atualizar o total do carrinho
  function updateTotal() {
    var cartBoxes = $(".cart-box");
    var total = 0;

    // Itera sobre as caixas do carrinho
    cartBoxes.each(function () {
      var priceElement = $(this).find(".cart-price");
      var quantityElement = $(this).find(".cart-quantity");

      // Obtém preço e quantidade do elemento atual
      if (priceElement.length && quantityElement.length) {
        var priceText = priceElement.text().trim();
        var price =
          parseFloat(priceText.replace("€", "").replace(",", ".")) || 0;
        var quantity = parseInt(quantityElement.val(), 10) || 1;

        // Calcula o total do produto
        if (!isNaN(price) && !isNaN(quantity)) {
          total += price * quantity;
        }
      }
    });

    // Atualiza o elemento que exibe o total
    $(".total-price").text("€" + total.toFixed(2));
  }
});
