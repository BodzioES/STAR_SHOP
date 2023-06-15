<?php
	require "../includes/dbh.inc.php";

	$grand_total = 0;
	$allItems = '';
	$items = [];

  
	$sql = "SELECT CONCAT(product_name, '(',qty,')') AS ItemQty, total_price FROM cart";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
	  $grand_total += $row['total_price'];
	  $items[] = $row['ItemQty'];
	}
	$allItems = implode(', ', $items);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../CSS/checkout.css">
  <link rel="shortcut icon" href="../photos/BodShop.png">
  <link href="https://fonts.cdnfonts.com/css/star-wars" rel="stylesheet">
  <title>Checkout</title>
</head>
<body>
  <div id="container">
      <div id="order">
        <h1 class="text-complete">Complete your order</h1>
        <div id="information">
          <h3 class="lead"><b>Product(s) : </b><?= $allItems; ?></h3>
          <h3 class="lead"><b>Delivery Charge : </b>Free</h3>
          <h2><b>Total Amount Payable : </b><?= number_format($grand_total,2) ?> PLN</h2>
        </div>
        <form action="" method="post" id="placeOrder">
          <input type="hidden" name="products" value="<?= $allItems; ?>">
          <input type="hidden" name="grand_total" value="<?= $grand_total; ?>">
          <div id="form-group">
            <input type="text" name="street" class="form-control" placeholder="Enter Street" required>
          </div>
          <div id="form-group">
            <input type="text" name="number-street" class="form-control" placeholder="Enter House Number" required>
          </div>
          <div id="form-group">
            <input type="text" inputmode="numeric" name="zip-code" class="form-control" placeholder="Enter Zip Code" required>
          </div>
          <div id="form-group">
            <input type="text" name="city" class="form-control" placeholder="Enter City" required>
          </div>
          <div id="form-group">
            <input type="email" name="email" class="form-control" placeholder="Enter E-Mail" required>
          </div>
          <div id="form-group">
            <input type="tel" name="phone" class="form-control" placeholder="Enter Phone" required>
          </div>          
          <h3 class="lead">Select Payment Mode</h3>
          <div id="form-group">
            <select name="pmode" class="form-control" required>
              <option value="" selected disabled>-Choose Payment Method-</option>
              <option value="Cash_On_Delivery">Cash On Delivery</option>
              <option value="BLIK">BLIK</option>
              <option value="Debit/Credit_Card">Debit/Credit Card</option>
            </select>
          </div>
          <div id="form-button">
            <input type="submit" name="submit" value="Place Order" class="button">
          </div>
        </form>
        <div id="cancel-order">
          <a href="cart.php" class="cancel-order" >Cancel the Order</a>
        </div>
      </div>
  </div>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>

  <script type="text/javascript">
  $(document).ready(function() {

    // Sending Form data to the server
    $("#placeOrder").submit(function(e) {
      e.preventDefault();
      $.ajax({
        url: '../includes/action.php',
        method: 'post',
        data: $('form').serialize() + "&action=order",
        success: function(response) {
          $("#order").html(response);
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