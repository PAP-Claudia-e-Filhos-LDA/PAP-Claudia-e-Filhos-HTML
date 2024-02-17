const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add("show");
    }
  });
});

const hiddenElements = document.querySelectorAll(".hidden");
hiddenElements.forEach((el) => observer.observe(el));

//esta funcao serve para enviar o formulario
function enviarFormulario() {
  var formulario = document.getElementById("editProfileForm");
  if (formulario) {
    formulario.submit();
  }
}

function enviarEncomendaForm() {
  var formulario = document.getElementById("encomendaForm");
  if (formulario) {
    formulario.submit();
  }
}

$(document).ready(function () {
  const inputSearch = $(".input-search");
  const cart = $(".cart");
  const cartIcon = $("#cart-icon");
  const closeCart = $("#close-cart");
  const buy = $(".btn-buy");

  inputSearch.on("input", function () {
    const searchTerm = $(this).val().toLowerCase();

    $(".catalogo").each(function () {
      const itemName = $(this).find(".catalogo-text h4").text().toLowerCase();
      const display = itemName.includes(searchTerm) || searchTerm === "";
      $(this).toggle(display);
    });
  });

  const cartProductTitles = new Set();

  cartIcon.on("click", function () {
    cart.toggleClass("active");
  });

  buy.on("click", function () {
    if ($(".cart-box").length > 1) {
      orderNow();
    } else {
      toastr.warning("Nenhum produto foi seleciona para encomedar", "Atenção", {
        closeButton: false,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 3000,
        extendedTimeOut: 1000,
        preventDuplicates: false,
        newestOnTop: false,
        showDuration: 300,
        hideDuration: 300,
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "slideDown",
        hideMethod: "slideUp",
        toastClass: "custom-toast-class",
      });
    }
  });

  closeCart.on("click", function () {
    cart.removeClass("active");
  });

  ready();

  function ready() {
    $(".cart-remove").on("click", removeCartItem);
    $(".cart-quantity").on("change", quantityChange);
    $(".add-cart").on("click", addCartClicked);
  }

  function removeCartItem(event) {
    const buttonClicked = event.target;
    const cartBox = $(buttonClicked).closest(".cart-box");

    const title = cartBox.find(".cart-product-title").text();
    cartBox.addClass("slide-out");

    cartBox.on("animationend", function () {
      cartBox.remove();
      updateTotal();
      updateCartBadge();
      cartProductTitles.delete(title);
    });
  }

  function quantityChange(event) {
    const input = event.target;
    if (isNaN(input.value) || input.value <= 0) {
      input.value = 1;
    }
    updateTotal();
  }

  function addCartClicked() {
    const button = event.target;
    const shopProducts = $(button).closest(".catalogo");
    const title = shopProducts.find(".catalogo-text h4").text();

    const existingCartItem = $(".cart-box:contains('" + title + "')");
    if (existingCartItem.length > 0) {
      const quantityInput = existingCartItem.find(".cart-quantity");
      const currentQuantity = parseInt(quantityInput.val(), 10) || 1;
      quantityInput.val(currentQuantity + 1);
    } else {
      const priceText = shopProducts.find(".catalogo-text h3").text();
      const price =
        parseFloat(priceText.replace("€", "").replace(",", ".")) || 0;
      const productImg = shopProducts.find("img").attr("src");

      addProductToCart(title, price, productImg);
      cartProductTitles.add(title);
    }

    cart.addClass("active");
    updateTotal();
    updateCartBadge();
  }

  function updateCartBadge() {
    const cartItems = $(".cart-box").filter(":has(*)");
    const cartBadge = $(".cart-badge");

    const itemCount = cartItems.length;

    cartBadge.css("display", itemCount > 0 ? "block" : "none");
    cartBadge.text(itemCount);
  }

  function addProductToCart(title, price, productImg) {
    const cartShopBox = $("<div>").addClass("cart-box slide-in");

    const cartBoxContent = `
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

    cartShopBox.html(cartBoxContent);
    $(".cart-content").append(cartShopBox);

    cartShopBox.find(".cart-remove").on("click", removeCartItem);
    cartShopBox.find(".cart-quantity").on("change", quantityChange);
  }

  function updateTotal() {
    let total = 0;

    $(".cart-box").each(function () {
      const priceElement = $(this).find(".cart-price");
      const quantityElement = $(this).find(".cart-quantity");

      if (priceElement.length && quantityElement.length) {
        const priceText = priceElement.text().trim();
        const price = parseFloat(priceText.replace("€", "").replace(",", ".")) || 0;
        const quantity = parseInt(quantityElement.val(), 10) || 1;

        if (!isNaN(price) && !isNaN(quantity)) {
          total += price * quantity;
        }
      }
    });

    $(".total-price").text("€" + total.toFixed(2));
  }

  function orderNow() {
    const cartDetails = [];

    $(".cart-box:gt(0)").each(function () {
      const title = $(this).find(".cart-product-title").text();
      const priceText = $(this).find(".cart-price").text().replace("€", "").replace(",", ".");
      const quantity = parseInt($(this).find(".cart-quantity").val(), 10);
      const imgSrc = $(this).find(".cart-img").attr("src");

      if (
        !isNaN(parseFloat(priceText)) &&
        !isNaN(quantity) &&
        title.trim() !== ""
      ) {
        cartDetails.push({
          title: title,
          price: parseFloat(priceText),
          quantity: quantity,
          imgSrc: imgSrc,
        });
      }
    });

    const total = parseFloat(
      $(".total-price").text().replace("€", "").replace(",", ".")
    );

//    console.log("Total: €" + total.toFixed(2));

 //   cartDetails.forEach(function (product) {
   //   console.log("Produto: " + product.title + ", Quantidade: " + product.quantity);
    //});
    window.location.href =
    "encomenda.php?details=" +
    encodeURIComponent(JSON.stringify(cartDetails)) +
    "&total=" +
    total.toFixed(2);
    
    $.ajax({
      type: "POST",
      url: "../includes/process_order.php", // Substitua isso pelo caminho real do seu arquivo PHP
      data: {
          cartDetails: JSON.stringify(cartDetails),
          total: total.toFixed(2),
      },
      success: function (response) {
          // Manipular a resposta do servidor, se necessário
          console.log(response);
       
      },
      error: function (error) {
          console.error("Erro ao enviar dados para o servidor:", error);
      },
  });
  }

  
});

