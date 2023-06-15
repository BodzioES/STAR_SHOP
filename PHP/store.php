<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Store</title>
    <link rel="stylesheet" href="../CSS/store.css">
    <link rel="shortcut icon" href="../photos/BodShop.png">
    <link href="https://fonts.cdnfonts.com/css/star-wars" rel="stylesheet">
    
</head>
<body>
  <div id="header">
          <?php
              session_start(); 
          ?>
          <a href="../index.php"><img src="../photos/shop.png" class="shop"></a>
          <?php 
              if (isset($_SESSION["login"])) 
              {
                  echo  "<a href='AboutUs.php'><span class='aboutus'>ABOUT US</span></a>";
                  echo  "<a href='cart.php'><span id='item-cart' class='cart'>CART</span></a>";
                  echo  "<span id='cart-item'></span>";
                  echo "<a href='../includes/logut.php'> <span class='logout'>LOG-OUT</span></a>";
              }else{
                  echo "<a href='../PHP/login.php'> <span class='account'>ACCOUNT</span></a>";
                  echo  "<a href='AboutUs.php'><span class='aboutuss'>ABOUT US</span></a>";
              } 
          ?>

  </div>
    <!---------------------------------------------------->
  <div id="line"></div>
  <!-- Displaying Products Start -->
  <div class="container">
    <div id="message"></div>
    <div class="all">
      <?php
  			include "../includes/dbh.inc.php";
  			$stmt = $conn->prepare("SELECT * FROM products");
  			$stmt->execute();
  			$result = $stmt->get_result();
  			while ($row = $result->fetch_assoc()):
  		?>
      <div class="products">
          <div class="card">
            <img src="<?=$row['product_image'] ?>" class="card-img-top" height="190">
            <div class="card-body p-1">
              <h4 class="card-name">
                &nbsp;&nbsp;<?= $row["name"]; ?>
              </h4>
              <h5 class="card-text">
                &nbsp;&nbsp;<?= number_format($row['price'],2,); ?> PLN
              </h5>
            </div>
            <div class="card-footer">
              <form class="addcart"  class="form-submit">

                <input type="hidden" class="pid" value="<?= $row['id_products'] ?>">
                <input type="hidden" class="pname" value="<?= $row['name'] ?>">
                <input type="hidden" class="pprice" value="<?= $row['price'] ?>">
                <input type="hidden" class="pimage" value="<?= $row['product_image'] ?>">
                <input type="hidden" class="ppimage" value="<?= $row['product_index_image'] ?>">
                <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
                <?php
                  if(isset($_SESSION["id"]))
                  {                   
                    echo "<div class='row p-2'>";
                    echo    "<div class='quantity'>";
                    echo        "<b>Quantity : </b>";
                    echo    "</div>";
                    echo    "<div id='quantity-number'>";
                    echo        "<input type='number' class='pqty' value='1' min='1' name='quantity'>";
                    echo    "</div>";
                    echo "</div>";
                    echo  "<div id='add-to-cart'>";
                    echo "<input type='submit' class='add-to-cart' value='Add to cart'>";
                    echo  "</div>";
                  }                  
                ?>
              </form>
            </div>
          </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
<!-- Displaying Products End -->
  <div id="footer">
    <div id="info">INFO</div>
        <div id="line-cart"></div>
        <div id="line1"></div>
        <div id="my-information">
              Phone number: 573 269 215</br></br>
              E-mail: <a href="https://mail.google.com/mail/u/?authuser=rogozinskialbert@gmail.com" target="_blank" class="email">rogozinskialbert@gmail.com</a>
        </div>
        <div id="line-cart2"></div>
        <div id="icon">
                  <span class="snap"><a href="https://www.snapchat.com/add/bodziuszek5?share_id=oJdG4-QqI4o&locale=en-GB" target="_blink"><img width="70px" height="70px" src="../photos/snapicon.png"></a></span>
                  <span class="fb"><a href="https://www.facebook.com/profile.php?id=100017190855716" target="_blank"><img width="80px" height="80px" src="../photos/facebookicon.png"></a></span>
                  <span class="ig"><a href="https://pl.wikipedia.org/wiki/Adolf_Hitler" target="_blank"><img width="80px" height="80px" src="../photos/igicon.png"></a></span>
          </div>
  </div>
 

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>

  <script type="text/javascript">
  $(document).ready(function() {

    // Send product details in the server
    $(".addcart").submit(function(e) {
      e.preventDefault();
      var $form = $(this);  
      var pid = $form.find(".pid").val();
      var pname = $form.find(".pname").val();
      var pprice = $form.find(".pprice").val();
      var pimage = $form.find(".pimage").val();
      var ppimage = $form.find(".ppimage").val();
      var pcode = $form.find(".pcode").val();
      var pqty = $form.find(".pqty").val();


      $.ajax({
        url: "../includes/action.php",
        method: "POST",
        data:{
          pid: pid,         /*to po lewej to zmienna do php a ta po prawej to do JS */
          pname: pname,     /*to po lewej to zmienna do php a ta po prawej to do JS */
          pprice: pprice,   /*to po lewej to zmienna do php a ta po prawej to do JS */
          pqty: pqty,       /*to po lewej to zmienna do php a ta po prawej to do JS */
          pimage: pimage,   /*to po lewej to zmienna do php a ta po prawej to do JS */
          ppimage: ppimage, /*to po lewej to zmienna do php a ta po prawej to do JS */
          pcode: pcode      /*to po lewej to zmienna do php a ta po prawej to do JS */
        },
        success: function(response) {
          $("#message").html(response);
          window.scrollTo(0, 0);
          load_cart_item_number();
        }
      });
    });

    // Load total no.of items added in the cart and display in the navbar
    load_cart_item_number();

    function load_cart_item_number() {
      $.ajax({
        url: '../includes/action.php',
        method: 'GET',
        data: {
          cartItem: "cart_item"
        },
        success: function(response) {
          $("#cart-item").html(response);
        }
      });
    }
  });
  </script>
</body>
</html>