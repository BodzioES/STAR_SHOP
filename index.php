<?php
    session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
    
    <meta charset="UTF-8">
    <title>StarShop</title>
    <link rel="stylesheet" href="CSS/styl.css">
    <link rel="shortcut icon" href="photos/BodShop.png">
    <link href="https://fonts.cdnfonts.com/css/star-wars" rel="stylesheet">
</head>
<body>
    <div id="header">
        <img src="photos/shop.png" class="shop">
        <img src="photos/order.png" class="order"> 
        <?php 
            if (isset($_SESSION["login"])) 
            {
                echo "<div class='name'>Welcome! </div>";
                echo "<div class='login'>".$_SESSION['login']."</div>";
                echo "<a href='includes/logut.php'> <span class='logout'>LOG-OUT</span></a>";
                echo "<a href='PHP/AboutUs.php'><span class='aboutus'>ABOUT US</span></a>";        
                echo "<a href='PHP/store.php'><span class='store'>STORE</span></a>";
                echo "<a href='PHP/cart.php'><span class='cart'>CART</span></a>";
                echo "<span id='cart-item'></span>";
            }else{
                echo "<a href='PHP/login.php'> <span class='account'>ACCOUNT</span></a>";
                echo  "<a href='PHP/AboutUs.php'><span class='aboutuss'>ABOUT US</span></a>";        
                echo  "<a href='PHP/store.php'><span class='storee'>STORE</span></a>";
            } 
        ?>
    </div>
    <div id="message"></div>
    <div id="mid">
        <span class="starshop">star shop</span>
        <span class="fans">by fans</br>for fans</span>
    </div>
    <div id="footer">
      <div id="line"></div>
      <?php require "includes/dbh.inc.php";?>
            <div id="mycart">MY CART</div>
<!----------------------------------------------------------------------------------------------------------------->
            <div id="line1"></div>
<!----------------------------------------------------------------------------------------------------------------->
            <div id="products">
                  <?php              
                    if(isset($_SESSION["id"]))
                    {                                          
                      $iduser = $_SESSION["id"];
                      $stmt = $conn->prepare("SELECT * FROM `cart` WHERE id_user='$iduser';");
                      $stmt->execute();
                      $result = $stmt->get_result(); 
                      if(!$result) 
                      echo "<div id='empty-product'>Your shopping cart is empty</div>";
                      else{      
                        while ($row = $result->fetch_assoc()):               
                        echo "<div id='imagee'><img src='$row[product_index_image]' alt='produkt' width='110' height='110'></div>";                       
                        echo "<div id='remove-product'>";
                        echo "<a href='includes/action.php?remove= $row[id]' onclick='return confirm('Are you sure want to remove this item?');'><img src='photos/krzyzyk.png' height='20'></a>";
                        echo "</div>";
                      endwhile;
                      }
                    }
                    else{
                      echo "<div id='notlogged'>You're not logged in</div>";
                    }  
                    
                  ?>  
            </div>
<!----------------------------------------------------------------------------------------------------------------->
            <div id="line2"></div>
<!----------------------------------------------------------------------------------------------------------------->
            <div id="hmi">
                  <?php 
                  if(isset($_SESSION["id"]))
                  { 
                    echo "<div id='product-footer'>Product</div>";
                    $questions = mysqli_query($conn,"SELECT sum(qty)  FROM `cart` WHERE id_user='$iduser';");
                    $hmi =  mysqli_fetch_row($questions);
                    if(!$hmi[0]) 
                    echo "<div id='hmi-zero'>x0</div>";
                    else echo "<div id='hmi-good'>x$hmi[0]</div>";
                  }
                  else{
                    echo "<div id='products'><img src='photos/minka.png' class='minka'></div>";
                  } 
                  ?>
            </div>
<!----------------------------------------------------------------------------------------------------------------->            
            <div id="line3"></div>
<!----------------------------------------------------------------------------------------------------------------->
            <div id="hmc">                  
              <?php
                if(isset($_SESSION["id"]))
                { 
                  echo "<div id='price-footer'>Total</div>"; 
                  $question = mysqli_query($conn,"SELECT sum(total_price)  FROM `cart` WHERE id_user='$iduser';");
                  $hmc =  mysqli_fetch_row($question);
                  if(!$hmc) 
                    echo "<div id='hmc-zero'>0.00 PLN</div>";
                  else{ 
                    $hmc_format = number_format($hmc[0],2);
                    echo "<div id='hmc-good'>$hmc_format PLN</div>";
                  }
                }
                else{
                  echo "<div id='products'><img src='photos/minka.png' class='minka'></div>";
                }
              ?>
            </div>
<!----------------------------------------------------------------------------------------------------------------->            
            <?php
              if(isset($_SESSION["id"]))
              { 
                echo "<a href='PHP/cart.php' id='checkout'><h1>CHECK OUT</h1></a>";
              }
              else{
                echo "<a href='#' onclick='return test_click();' id='checkout'><h1>CHECK OUT</h1></a>";
              }
            ?>
            <script type="text/javascript">
              function test_click(event){
              alert("You are not logged in to go to the basket");
              return true;
              }
            </script>
    </div>
    

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>
    
    <script type="text/javascript">
  $(document).ready(function() { 
    // Load total no.of items added in the cart and display in the navbar
    load_cart_item_number();
    
    function load_cart_item_number() {
      $.ajax({
        url: 'includes/action.php',
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