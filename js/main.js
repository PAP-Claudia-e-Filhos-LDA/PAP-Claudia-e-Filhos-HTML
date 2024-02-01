const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('show');
    } 
  });
});

const hiddenElements = document.querySelectorAll('.hidden');
hiddenElements.forEach((el) => observer.observe(el));

//esta funcao serve para enviar o formulario
function enviarFormulario() {
  var formulario = document.getElementById('editProfileForm');
  if (formulario) {
      formulario.submit();
  }
}



var cartDetails = [];

$(document).ready(function () {
  // Manipula a entrada no campo de pesquisa
  $(".input-search").on("input", function () {
    var searchTerm = $(this).val().toLowerCase();

    // Itera sobre os elementos com a classe 'catalogo'
    $(".catalogo").each(function () {
      var itemName = $(this).find(".catalogo-text h4").text().toLowerCase();
      var display = itemName.includes(searchTerm) || searchTerm === "";
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

  $(".btn-buy").on("click", function () {
    orderNow();
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
  
    var existingCartItem = $(".cart-box:contains('" + title + "')");
    if (existingCartItem.length > 0) {
      // Se o produto já estiver no carrinho, aumenta a quantidade em 1
      var quantityInput = existingCartItem.find(".cart-quantity");
      var currentQuantity = parseInt(quantityInput.val(), 10) || 1;
      quantityInput.val(currentQuantity + 1);
  
      // Imprime o título e a nova quantidade no console
    } else {
      // Se o produto não estiver no carrinho, adiciona como um novo item
      var priceText = shopProducts.find(".catalogo-text h3").text();
      var price = parseFloat(priceText.replace("€", "").replace(",", ".")) || 0;
      var productImg = shopProducts.find("img").attr("src");
  
      addProductToCart(title, price, productImg);
      cartProductTitles.add(title);
  
      // Imprime o título e a quantidade 1 no console
   
    }
  
    // Adiciona a classe 'active' ao carrinho
    cart.addClass("active");
  
    updateTotal();
    updateCartBadge();
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
                  <div class="cart-product-title"><h3>${title}</h3></div>
                  <div class="cart-price"><p>${price}€</p></div>
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

  function orderNow() {
    cartDetails = [];
  
    // Itera sobre os elementos no carrinho a partir do segundo elemento
    $(".cart-box:gt(0)").each(function () {
        var title = $(this).find(".cart-product-title").text();
        var priceText = $(this).find(".cart-price").text().replace("€", "").replace(",", ".");
        var quantity = parseInt($(this).find(".cart-quantity").val(), 10);
        var imgSrc = $(this).find(".cart-img").attr("src");

        // Adiciona os detalhes do produto ao array
        console.log("Produto:", title);
        console.log("Price Text:", priceText);
        console.log("Quantity:", quantity);
        console.log("Image Source:", imgSrc);

        var price = parseFloat(priceText);

        if (!isNaN(price) && !isNaN(quantity) && title.trim() !== "") {
            cartDetails.push({
                title: title,
                price: price,
                quantity: quantity,
                imgSrc: imgSrc
            });
        }
    });

    // Obtém o total do carrinho
    var total = parseFloat($(".total-price").text().replace("€", "").replace(",", "."));

    // Imprime no console os detalhes do carrinho e o total
    console.log("Total: €" + total.toFixed(2));

    // Codifica os detalhes do carrinho em JSON e redireciona para encomenda.php
    window.location.href = "encomenda.php?details=" + encodeURIComponent(JSON.stringify(cartDetails)) + "&total=" + total.toFixed(2);
}

  
});







// ---------------------Ecomenda ---------------------------------//

