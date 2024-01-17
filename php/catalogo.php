
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
    <section class="container active" id="catalogo">
      <div class="catalogos-content">
        <div class="main-title">
          <h2>Our <span>Catalogo</span><span class="bg-text">Catalogo</span></h2>
        </div>
        <div class="cart">
          <h2 class="cart-title">Your cart</h2>

          <div class="cart-content">
            <div class="cart-box">
              <img src="../img/1.jpg" alt="" class="cart-img">
              <div class="detail-box">
                <div class="cart-product-title">earbuds</div>
                <div class="cart-price">25€</div>
                <input type="number" value="1" class="cart-quantity">
              </div>
              <i class="fas fa-trash-alt cart-remove"></i>
            </div>
          </div>
          <div class="total">
              <div class="total-title">Total</div>
              <div class="total-price">€0</div>
          </div>
          <button type="button" class="btn-buy">Buy now</button>
          <i class="fas fa-times" id="close-cart"></i>

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
                  <h4><?php echo $catalogo[1]; ?> <?php echo $catalogo[3] ?>€</h4>
                  <p><?php echo $catalogo[2]; ?></p>
                  <div class="btn-con">
                    <a href="#" class="main-btn">
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
 
    <div class="profile-btn"  id="cart-icon">
      <i class="fas fa-shopping-cart"> </i>
    </div>
    <script src="../js/app.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  
<script>
    $(document).ready(function () {
        $('.input-search').on('input', function () {
            var searchTerm = $(this).val().toLowerCase();

            $('.catalogo').each(function () {
                var itemName = $(this).find('.catalogo-text h4').text().toLowerCase();
                var display = itemName.includes(searchTerm) || searchTerm === '';

                $(this).toggle(display);
            });
        });
    });
</script>

<script>
  
let  cartIcon = document.querySelector('#cart-icon');
let  cart = document.querySelector('.cart');
let  closeCart = document.querySelector('#close-cart');


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


if(document.readyState == "loading"){
  document.addEventListener("DOMContentLoaded",ready);
}else{
  ready();
}

function ready(){
  var removeCartButtons = document.getElementsByClassName('cart-remove');
  console.log(removeCartButtons);
  for(var i = 0;i<removeCartButtons.length;i++){
    var button = removeCartButtons[i];
    button.addEventListener('click', removeCartItem);
  }
  var quantityInputs = document.getElementsByClassName('cart-quantity');
  for(var i = 0;i<quantityInputs.length;i++){
    var input = quantityInputs[i];
    input.addEventListener("change",quantityChange)
  }
}

function removeCartItem(){
    var buttonClicked = event.target;
    buttonClicked.parentElement.remove(); 
    updatetotal()
}

function quantityChange(event){
  var input = event.target
  if(NaN(input.value) || input.value <= 0){
    input.value=1;
  }
  updatetotal();
}

function updatetotal(){
  var cartContent = document.getElementsByClassName('cart-content')[0];
  var cartBoxes = document.getElementsByClassName('cart-box');
  var total = 0;
  for(var i = 0;i<cartBoxes.length;i++){
    var cartbox = cartBoxes[i];
    var priceElement = cartBox.getElementsByClassName('cart-price')[0];
    var quantityElement = cartBox.getElementsByClassName('cart-quantity')[0];
    var price = parseFloat(priceElement.innerText.replace('€',""));
    var quantity = quantityElement.value;
    total = total + (price * quantity);

    document.getElementsByClassName('total-price')[0].innerText='€' + total;
  }
}
</script>
</body>

</html>