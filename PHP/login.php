<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sign-Up</title>
    <link rel="stylesheet" href="../CSS/login.css">
    <link rel="shortcut icon" href="../photos/BodShop.png">

    <?php

session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: ../index.php");
    exit;
}

require_once "../includes/dbh.inc.php";

$username = $password = "";
$username_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    if(empty(trim($_POST["login"]))){
        $username_err = "*Please enter username.";
    } else{
        $username = trim($_POST["login"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "*Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($username_err) && empty($password_err)){
       
        $sql = "SELECT id, loginn, haslo FROM konto_uzytkownicy WHERE loginn = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            
            mysqli_stmt_bind_param($stmt, "s", $param_username);          
           
            $param_username = $username;          
            
            if(mysqli_stmt_execute($stmt)){
                
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            
                            session_start();
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["login"] = $username;                            
                            
                            header("location: ../index.php");
                        } else{
                            
                            $login_err = "*Invalid username or password.<br>";
                        }
                    }
                } else{
                    
                    $login_err = "*Invalid username or password.<br>";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>
    
</head>
<body>
    <div id="video">
        <video width="1920" height="1080" autoplay loop muted plays-inline>
            <source src="../move/Cosplayers-Video.mp4" type="video/mp4" >
        </video>
    </div>

        <section class = "signup-form">
    <!------------------------------------------------->  
            <div id ="signup-form-form">
            <h1>LOG-IN</h1>

                <form action="" method="post">
                    <input type="text" name="login" placeholder="Username" class="one" ></br>
                            <?php echo "<span class='username_err'>".$username_err."</span>";?>
                    <input type="password" name="password" placeholder="Password" class="two"></br>
                        <?php echo "<span class='password_err'>".$password_err."</span>";?>    
                        <?php echo "<span class='login_err'>".$login_err."</span>"; ?>             
                    <button type="submit" name="submit-login" class="button-login" >Log In</button>
                </form>
                <span 
                    class="info">You do not have an account?</br> Please
                </span> <a href="signup.php" class="signup"> <b>SIGN-UP</b></a></br>
                <a href="../index.php" class="write-home"><b>GO TO MAIN CART</b></a>
        </section>
    </div>
    <!------------------------------------------------->
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
</body>
</html>
