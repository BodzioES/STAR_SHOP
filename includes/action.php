<?php
	session_start();
	require 'dbh.inc.php';

		// Add products into the cart table
	if (isset($_POST['pid'])) {
		
		$pid = $_POST['pid'];
		$pname = $_POST['pname'];
		$pprice = $_POST['pprice'];
		$pimage = $_POST['pimage'];
		$ppimage = $_POST['ppimage'];
		$pcode = $_POST['pcode'];
		$pqty = $_POST['pqty'];
		$total_price = $pprice * $pqty;
		$iduser = $_SESSION["id"];

		$stmt = $conn->prepare("SELECT `product_code` FROM `cart` WHERE `product_code`=?;");
		$stmt->bind_param('s',$pcode);
		$stmt->execute();
		$res = $stmt->get_result();
		$r = $res->fetch_assoc();
		$code = $r['product_code'] ?? '';
		
		if (!$code) {
		  $query = $conn->prepare("INSERT INTO `cart` (product_name,product_price,product_image,qty,total_price,product_code,id_user,id_product,product_index_image) VALUES (?,?,?,?,?,?,?,?,?);");
		  $query->bind_param('sssssssss',$pname,$pprice,$pimage,$pqty,$total_price,$pcode,$iduser,$pid,$ppimage);
		  $query->execute();
  
		  echo "<script>alert('Item added to your cart!');</script>";
		} else {
		  echo "<script>alert('Item arleady added to your cart');</script>";
		}
	  }

	// Get no.of items available in the cart table
	if (isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart_item') {
	  $stmt = $conn->prepare('SELECT * FROM cart');
	  $stmt->execute();
	  $stmt->store_result();
	  $rows = $stmt->num_rows;
	  echo $rows;
	}

	// Remove single items from cart
	if (isset($_GET['remove'])) {
	  $id = $_GET['remove'];

	  $stmt = $conn->prepare('DELETE FROM `cart` WHERE `id`=?;');
	  $stmt->bind_param('i',$id);
	  $stmt->execute();

	  $_SESSION['showAlert'] = 'block';
	  $_SESSION['message'] = 'Item removed from the cart!';
	  header('location:../PHP/cart.php');
	}

	// Remove single items from index
	if (isset($_GET['remove'])) {
		$id = $_GET['remove'];
  
		$stmt = $conn->prepare('DELETE FROM `cart` WHERE `id`=?;');
		$stmt->bind_param('i',$id);
		$stmt->execute();
  
		$_SESSION['showAlert'] = 'block';
		$_SESSION['message'] = 'Item removed from the cart!';
		header('location:../index.php');
	  }

	// Remove all items at once from cart
	if (isset($_GET['clear'])) {
	  $stmt = $conn->prepare('DELETE FROM `cart`');
	  $stmt->execute();
	  $_SESSION['showAlert'] = 'block';
	  $_SESSION['message'] = 'All Item removed from the cart!';
	  header('location:../PHP/cart.php');
	}

	// Set total price of the product in the cart table
	if (isset($_POST['qty'])) {
	  $qty = $_POST['qty'];
	  $pid = $_POST['pid'];
	  $pprice = $_POST['pprice'];

	  $tprice = $qty * $pprice;

	  $stmt = $conn->prepare("UPDATE `cart` SET `qty`=$qty, `total_price`=$pprice WHERE `id_product`=$pid;");
	  $stmt->bind_param('isi',$qty,$tprice,$pid);
	  $stmt->execute();
	}

	// Checkout and save customer info in the orders table
	if (isset($_POST['action']) && isset($_POST['action']) == 'order') {
	  $ulica = $_POST['street'];
      $nrulicy = $_POST['number-street'];
      $kodp = $_POST['zip-code'];
      $miasto = $_POST['city'];
	  $email = $_POST['email'];
	  $telefon = $_POST['phone'];	  
	  $iduser = $_SESSION['id'];	
	  $pmode = $_POST['pmode'];

	  $data = '';

	  $stmt = $conn->prepare('INSERT INTO `zamowienia` (ulica,nr_ulicy,kod_pocztowy,miasto,telefon,id_uzytkownika,email,metoda_platnosci)VALUES(?,?,?,?,?,?,?,?);');
	  $stmt->bind_param('ssssssss',$ulica,$nrulicy,$kodp,$miasto,$telefon,$iduser,$email,$pmode);
	  $stmt->execute();
	  $stmt2 = $conn->prepare('DELETE FROM `cart`;');
	  $stmt2->execute();
	  $data .= '<div class="text-center">
								<h1 class="display-4 mt-2 text-danger">Thank You!</h1>
								<h2 class="text-success">Your Order Placed Successfully!</h2>							
								<h4>Your Phone : ' . $telefon . '</h4>
								<h4>Your Email : ' . $email . '</h4>
								<h4><a href="../index.php">Back to main site</a></h4>
						  </div>';
	  echo $data;
	}
	
?>