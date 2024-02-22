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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>

</head>

<body class="main-content">
    <main>
        <section class="container contact active" id="contact">
            <div class="contact-container">
                <div class="main-title">
                    <h2>enco<span>mendas</span><span class="bg-text">Rissois</span></h2>
                </div>

                <?php
include('../includes/historico_inc.php');

foreach (array_reverse($userOrders) as $order) {
    $total = 0; // Inicializa o total para cada pedido
    echo "<div class='order'>";
    echo "<h1>Número da Encomenda: " . $order['id_Encomendas'] . "</h1>";

    foreach ($order['items'] as $item) {
        echo "<div class='cart-item'>";
        echo "<div class='cart-img-box' class='fancybox'>";
        echo "<img src='{$item['caminho_imagem']}' alt='{$item['nome_produto']}' class='cart-img'>";
        echo "</div>";
        echo "<div class='detail-box'>";
        echo "<div class='cart-product-title'><h3>{$item['nome_produto']} {$item['preco']}€</h3></div>";
        echo "<div class='cart-quantity-box'>";
        echo "<label for='quantity'>Quantidade:</label>";
        echo "<input type='text' value='{$item['quantidade']}' class='cart-quantity' id='quantity' disabled>";
        echo "</div>";
        echo "</div>"; // fecha div 'detail-box'
        echo "</div>"; // fecha div 'cart-item'

        // Atualiza o total com o preço multiplicado pela quantidade do item
        $total += $item['preco'] * $item['quantidade'];
    }

   // fecha div 'order'

    // Mostra o total
    echo "
    <div class='total'>
        <div class='total-title'>Total</div>
        <div> €{$total}</div>
    </div>
    ";

    echo "</div>"; 
}
?>



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

        <div class="control  active-btn" data-id="contact">
            <i class="far fa-envelope-open"></i>
        </div>

        <a href="profile.php">
            <div class="control" data-id="home">
                <i class="fas fa-user"></i>
            </div>
        </a>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
<script src="../js/main.js"></script>
     <script>
    $(document).ready(function() {
      const urlParams = new URLSearchParams(window.location.search);
      const errorParam = urlParams.get("error");

      if (errorParam === "none") {
        Swal.fire({
          icon: "success",
          title: "Encomenda feita com sucesso!",
          showConfirmButton: false,
          timer: 2500,
          background: "#17191f",
          iconColor: "#fd9c3a",
          customClass: {
            title: 'text-white' // Adiciona a classe 'text-white' para definir a cor do texto como branco
          }
        });
      }
    });
  </script>

</html>