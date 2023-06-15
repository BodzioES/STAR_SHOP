<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account</title>
    <link rel="shortcut icon" href="../photos/BodShop.png">
    <link rel="stylesheet" href="../CSS/signup.css">

    <?php
        session_start();

        require '../includes/dbh.inc.php';
        
        $name = $surname = $email = $uid = $pwd = $pwdrepeat = "";
        $name_err = $surname_err = $email_err = $login_err = $pwd_err = $pwdrepeat_err = "";

        if(isset($_POST["submit"]))
        {
            if(empty(trim($_POST["name"]))){
                $name_err = "*Please enter your name.";
            } else{
                $name = trim($_POST["name"]);
            }
            
            if(empty(trim($_POST["surname"]))){
                $surname_err = "*Please enter surname.";
            } else{
                $surname = trim($_POST["surname"]);
            }

            if(empty(trim($_POST["email"]))){
                $email_err = "*Please enter your email.";
            } else{
                $email = trim($_POST["email"]);
            }
            
            if(empty(trim($_POST["uid"]))){
                $login_err = "*Please enter your login.";
            } else{
                $login = trim($_POST["uid"]);
            }

            if(empty(trim($_POST["pwd"]))){
                $pwd_err = "*Please enter password.";
            } else{
                $pwd = trim($_POST["pwd"]);
            }
            
            if(empty(trim($_POST["pwdrepeat"]))){
                $pwdrepeat_err = "*Please repeat password.";
            } else{
                $pwdrepeat = trim($_POST["pwdrepeat"]);
            }

            if(empty($name_err) && empty($surname_err) && empty($email_err) && empty($login_err) && empty($pwd_err) && empty($pwdrepeat_err))
            {
                $duplicate = mysqli_query($conn,"SELECT * FROM `konto_uzytkownicy` WHERE `loginn` LIKE '$name' ;");
                $duplicate1 = mysqli_query($conn,"SELECT * FROM `konto_uzytkownicy` WHERE `email` LIKE '$email';");
                if(mysqli_num_rows($duplicate1) > 0)
                {
                    echo "<script> alert('Email has arleady taken'); </script>";
                }
                else if(mysqli_num_rows($duplicate) > 0)
                {
                    echo "<script> alert('Username has arleady taken'); </script>";
                }
                else{
                    if($pwd == $pwdrepeat)
                    {
                        $pwd = password_hash($pwdrepeat,PASSWORD_DEFAULT);
                        $query = "INSERT INTO konto_uzytkownicy VALUES('','$name','$surname','$email','$login','$pwd');";
                        mysqli_query($conn,$query);
                        echo 
                        "<script> alert('Registration Succesful!'); </script>";
                        header("location: ../PHP/signup.php");
                    }
                    else{
                        echo
                        "<script> alert('Password Does Not Match '); </script>";
                    }
                }
                
            }
        }
    ?>

</head>
<body>
    <video width="1920" height="1080" autoplay loop muted plays-inline class="video">
        <source src="../move/Cosplayers-Video.mp4" type="video/mp4">
    </video>

    <div id="header">
    
    </div>
    <!--------------------------------------------------->
    <section class = "sign-up-form">
        <div id ="signup-form-form">
            <h1>Sign-up</h1>
            <form action="" method="post">
                <input type="text" name="name" placeholder="Name" class="one"></br>
                    <?php echo "<span class='name_err'>".$name_err."</span>" ?>
                <input type="text" name="surname" placeholder="Surname" class="two"></br>
                    <?php echo "<span class='surname_err'>".$surname_err."</span>" ?>
                <input type="email" name="email" placeholder="Email" class="three"></br>
                    <?php echo "<span class='email_err'>".$email_err."</span>" ?>
                <input type="text" name="uid" placeholder="Username" class="four"></br>
                    <?php echo "<span class='login_err'>".$login_err."</span>" ?>
                <input type="password" name="pwd" placeholder="Password" class="five"></br>
                    <?php echo "<span class='pwd_err'>".$pwd_err."</span>" ?>
                <input type="password" name="pwdrepeat" placeholder="Repeat Password" class="six"></br>
                    <?php echo "<span class='pwdrepeat_err'>".$pwdrepeat_err."</span>" ?>
                <button type="submit" name="submit" class="button-login">SIGN-UP</button>
                </br><a href="login.php" class="return"><b>RETURN</b></a>
            </form>
        </div>
    </section>
    <!--------------------------------------------------->
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