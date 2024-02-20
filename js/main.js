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
  if (formulario.checkValidity()) {
    formulario.submit();
  } else {
    toastr.warning("Preencha todos os campos do checkup", "Atenção", {
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
    if ($(".cart-box").length > 0) {
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

  restoreCartFromLocalStorage();
  ready();

  function ready() {
    $(".cart-remove").on("click", removeCartItem);
    $(".cart-quantity").on("change", quantityChange);
    $(".add-cart").on("click", addCartClicked);
  }

  function restoreCartFromLocalStorage() {
    const storedCartItems = localStorage.getItem("cartItems");

    if (storedCartItems) {
      const cartItems = JSON.parse(storedCartItems);
      // Limpar o carrinho atual
      $(".cart-content").empty();

      // Adicionar itens do localStorage ao carrinho
      cartItems.forEach(function (item) {
        addProductToCart(item.title, item.price, item.imgSrc, item.quantity);
      });

      // Atualizar total e distintivos do carrinho
      updateTotal();
      updateCartBadge();
    } else {
      // Se não houver itens, exibir a mensagem
      $(".empty-cart").show();
    }
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

      // Atualize o localStorage após a remoção
      saveCartToLocalStorage();
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

    const existingCartItem = $(
      ".cart-box .cart-product-title:contains('" + title + "')"
    );
    if (existingCartItem.length > 0) {
      const quantityInput = existingCartItem
        .closest(".cart-box")
        .find(".cart-quantity");
      const currentQuantity = parseInt(quantityInput.val(), 10) || 1;
      quantityInput.val(currentQuantity + 1);
    } else {
      const priceText = shopProducts.find(".catalogo-text h3").text();
      const price =
        parseFloat(priceText.replace("€", "").replace(",", ".")) || 0;
      const productImg = shopProducts.find("img").attr("src");

      addProductToCart(title, price, productImg, 1);
      cartProductTitles.add(title);
    }

    cart.addClass("active");
    updateTotal();
    updateCartBadge();
    saveCartToLocalStorage();
  }

  function saveCartToLocalStorage() {
    const cartItems = [];

    $(".cart-box").each(function () {
      const title = $(this).find(".cart-product-title h3").text();
      const priceText = $(this)
        .find(".cart-price p")
        .text()
        .replace("€", "")
        .replace(",", ".");
      const quantity = parseInt($(this).find(".cart-quantity").val(), 10);
      const imgSrc = $(this).find(".cart-img").attr("src");

      if (
        !isNaN(parseFloat(priceText)) &&
        !isNaN(quantity) &&
        title.trim() !== ""
      ) {
        if (quantity > 0) {
          cartItems.push({
            title: title,
            price: parseFloat(priceText),
            quantity: quantity,
            imgSrc: imgSrc,
          });
        }
      }
    });

    localStorage.setItem("cartItems", JSON.stringify(cartItems));
  }

  function updateCartBadge() {
    const cartItems = $(".cart-box").filter(":has(*)");
    const cartBadge = $(".cart-badge");

    const itemCount = cartItems.length;

    cartBadge.css("display", itemCount > 0 ? "block" : "none");
    cartBadge.text(itemCount);
  }
  function addProductToCart(title, price, productImg, quantity) {
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
                  <input type="text" value="${quantity}" class="cart-quantity" id="quantity">
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

    cart.addClass("active");
    updateTotal();
    updateCartBadge();
    saveCartToLocalStorage();
  }

  function updateTotal() {
    let total = 0;

    $(".cart-box").each(function () {
      const priceElement = $(this).find(".cart-price");
      const quantityElement = $(this).find(".cart-quantity");

      if (priceElement.length && quantityElement.length) {
        const priceText = priceElement.text().trim();
        const price =
          parseFloat(priceText.replace("€", "").replace(",", ".")) || 0;
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

    $(".cart-box").each(function () {
      const title = $(this).find(".cart-product-title").text();
      const priceText = $(this)
        .find(".cart-price")
        .text()
        .replace("€", "")
        .replace(",", ".");
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

    // Calcular o total novamente no JavaScript
    const total = cartDetails.reduce(
      (acc, item) => acc + item.price * item.quantity,
      0
    );

    console.log("Total: €" + total.toFixed(2));

    window.location.href =
      "encomenda.php?details=" +
      encodeURIComponent(JSON.stringify(cartDetails)) +
      "&total=" +
      total.toFixed(2);

    $.ajax({
      type: "POST",
      url: "../includes/process_order.php",
      contentType: "application/json",
      data: JSON.stringify({
        cartDetails: cartDetails,
        total: total.toFixed(2),
      }),
      success: function (response) {
        console.log(response);
      },
      error: function (error) {
        console.error("Erro ao enviar dados para o servidor:", error);
      },
    });
  }
});
