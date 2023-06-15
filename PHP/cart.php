<?php
  session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cart</title>
  <link rel="stylesheet" href="../CSS/cart.css">
  <link rel="shortcut icon" href="../photos/BodShop.png">
  <link href="https://fonts.cdnfonts.com/css/star-wars" rel="stylesheet">
</head>

<body>
    <div id="header">
        <a href="../index.php"><img src="../photos/shop.png" class="shop"></a>
        <a href="AboutUs.php"><span class="aboutus">ABOUT US</span></a>
        <a href="store.php"><span class="store">STORE</span></a>
        <?php 
            if (isset($_SESSION["login"])) 
            {
                echo "<div class='name'>Your cart</div>";
                echo "<div class='login'>".$_SESSION['login']."!</div>";
                echo "<a href='../includes/logut.php'> <span class='logout'>LOG-OUT</span></a>";
            }else{
                echo "<a href='../PHP/login.php'> <span class='account'>ACCOUNT</span></a>";
            } 
        ?>
    </div>
<!----------------------------------------------------------------------------------->


  <div class="container">
        <div class="table-responsive">
        <h4 class="text-product">Products in your cart</h4> 
        <table border=0 class="table table-bordered ">
            <thead>                                                                           
              <tr>
                <th>PRODUCT</th>
                <th>IMAGE</th>
                <th>PRICE</th>
                <th>QUANTITY</th>
                <th>TOTAL PRICE</th>
                <th>
                  <div id="clear-cart">
                    <a href="../includes/action.php?clear=all" class="clear-cart" onclick="return confirm('Are you sure want to clear your cart?');">Clear Cart</a>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
                require "../includes/dbh.inc.php";
                $iduser = $_SESSION["id"];
                $stmt = $conn->prepare("SELECT * FROM `cart` WHERE id_user='$iduser';");
                $stmt->execute();
                $result = $stmt->get_result();
                $grand_total = 0;
                while ($row = $result->fetch_assoc()):
              ?>
              <tr>
                <td><?= $row['product_name'] ?></td>
                <td><img src="<?= $row['product_image'] ?>" width="200"></td>
                <td>
                  <i class="price-product"></i>&nbsp;&nbsp;<?= number_format($row['product_price'],2); ?> PLN
                </td>
                <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                <td>
                  <div id="itemQty">
                    <span class="next"></span>
                    <input type="number" class="form-control quantity" value="<?= $row['qty'] ?>" min="1" max="9" style="width:75px;">
                  </div>
                </td>
                <td><i class="totalprice"></i>&nbsp;&nbsp;<?= number_format($row['total_price'],2); ?> PLN</td>
                <td>
                  <div id="remove-item">
                    <a href="../includes/action.php?remove=<?= $row['id'] ?>" class="remove-item" onclick="return confirm('Are you sure want to remove this item?');">&times;</a>
                  </div>
                </td>
              </tr>
              <?php $grand_total += $row['total_price']; ?>
              <?php endwhile; ?>
            </tbody>
        </table>      
          <div id="button-continue">
            <a href="store.php" class="continue">Continue Shopping</a>
          </div>
          <div id="checkout">
            <a href="checkout.php" class="button-checkout <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Checkout</a>  
          </div> 
          <div id="grand-total">
            Grand Total:
            <b><?= number_format($grand_total,2); ?> PLN</b>
          </div>   
          
                                                                      
        </div>
  </div>
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

      // Change the item quantity
      $("itemQty").on('change', function() {
        var $el = $(this).closest('tr');
        var pid = $el.find("pid").val();
        var pprice = $el.find("pprice").val();
        var qty = $el.find("itemQty").val();
        location.reload(true);
        $.ajax({
          url: '../includes/action.php',
          method: 'post',
          cache: false,
          data: {
            qty: qty,
            pid: pid,
            pprice: pprice
          },
          success: function(response) {
            console.log(response);
          }
        });
      });

      // Load total no.of items added in the cart and display in the navbar
      load_cart_item_number();

      function load_cart_item_number() {
        $.ajax({
          url: '../includes/action.php',
          method: 'get',
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