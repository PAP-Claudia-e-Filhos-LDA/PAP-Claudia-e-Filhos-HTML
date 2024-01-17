$(document).ready(function() {
    $('.input-search').on('input', function() {
      var searchTerm = $(this).val().toLowerCase();

      $('.catalogo').each(function() {
        var itemName = $(this).find('.catalogo-text h4').text().toLowerCase();
        var display = itemName.includes(searchTerm) || searchTerm === '';

        $(this).toggle(display);
      });
    });
  });

  let cartIcon = document.querySelector('#cart-icon');
  let cart = document.querySelector('.cart');
  let closeCart = document.querySelector('#close-cart');


  cartIcon.onclick = () => {
    if (cart.classList.contains("active")) {
      cart.classList.remove("active");
    } else {
      cart.classList.add("active");
    }
  };

  closeCart.onclick = () => {
    cart.classList.remove("active");
  };


  if (document.readyState == "loading") {
    document.addEventListener("DOMContentLoaded", ready);
  } else {
    ready();
  }

  function ready() {
    var removeCartButtons = document.getElementsByClassName('cart-remove');
    console.log(removeCartButtons);
    for (var i = 0; i < removeCartButtons.length; i++) {
      var button = removeCartButtons[i];
      button.addEventListener('click', removeCartItem);
    }
    var quantityInputs = document.getElementsByClassName('cart-quantity');
    for (var i = 0; i < quantityInputs.length; i++) {
      var input = quantityInputs[i];
      input.addEventListener("change", quantityChange);
    }
    var addCart = document.getElementsByClassName('add-cart');
    for (var i = 0; i < addCart.length; i++) {
      var button = addCart[i];
      button.addEventListener('click', addCartClicked);
    }
  }

  function removeCartItem() {
    var buttonClicked = event.target;
    var cartBox = buttonClicked.parentElement;
    cartBox.remove();
    updateTotal();
    updateCartBadge()
  }

  function quantityChange(event) {
    var input = event.target;
    if (isNaN(input.value) || input.value <= 0) {
      input.value = 1;
    }
    updateTotal(); 
  }

  function addCartClicked() {
    var button = event.target;
    var shopProducts = button.closest('.catalogo');
    var title = shopProducts.querySelector('.catalogo-text h4').innerText;

    // Verificar se o produto já está no carrinho
    var cartTitles = document.querySelectorAll('.cart-product-title');
    for (var i = 0; i < cartTitles.length; i++) {
      if (cartTitles[i].innerText === title) {
        alert("Este produto já está no carrinho!");
        return;
      }
    }

    var priceText = shopProducts.querySelector('.catalogo-text h3').innerText;
    var price = parseFloat(priceText.replace('€', '').replace(',', '.')) || 0;
    var productImg = shopProducts.querySelector('img').src;
    addProductToCart(title, price, productImg);
    updateTotal();

    updateCartBadge();
  }

  function updateCartBadge() {
    var cartItems = document.querySelectorAll('.cart-box');
    var cartBadge = document.querySelector('.cart-badge');

    // Remover o elemento vazio do carrinho antes de contar
    cartItems = Array.from(cartItems).filter(cartItem => cartItem.children.length > 0);

    var itemCount = cartItems.length;

    if (itemCount > 0) {
      cartBadge.style.display = 'block';
      cartBadge.innerText = itemCount;
    } else {
      cartBadge.style.display = 'none';
    }
  }



  function addProductToCart(title, price, productImg) {
    var cartShopBox = document.createElement('div');
    cartShopBox.classList.add('cart-box');

    // Corrija a referência a 'shopProducts' para 'cartShopBox'
    var cartBoxContent = `
  <img src="${productImg}" alt="" class="cart-img">
  <div class="detail-box">
    <div class="cart-product-title">${title}</div>
    <div class="cart-price">${price} €</div>
    <input type="number" value="1" class="cart-quantity">
  </div>
  <i class="fas fa-trash-alt cart-remove"></i>
`;

    cartShopBox.innerHTML = cartBoxContent;
    var cartItems = document.querySelector('.cart-content');
    cartItems.appendChild(cartShopBox);
    cartShopBox.querySelector('.cart-remove').addEventListener('click', removeCartItem);
    cartShopBox.querySelector('.cart-quantity').addEventListener('change', quantityChange);

  }

  function updateTotal() {
    var cartBoxes = document.querySelectorAll('.cart-box');
    var total = 0;

    cartBoxes.forEach(function(cartBox) {
      var priceElement = cartBox.querySelector('.cart-price');
      var quantityElement = cartBox.querySelector('.cart-quantity');

      if (priceElement && quantityElement) {
        var priceText = priceElement.innerText.trim();
        var price = parseFloat(priceText.replace('€', '').replace(',', '.')) || 0;

        var quantity = parseInt(quantityElement.value, 10) || 1;

        if (!isNaN(price) && !isNaN(quantity)) {
          total += price * quantity;
        }
      }
    });

    document.querySelector('.total-price').innerText = '€' + total.toFixed(2);
  }

