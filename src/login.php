<?php 
session_start();
require_once("Bdd.php");

if(isset($_POST) && isset($_POST["password"])){
    $username = htmlentities($_POST["username"]);
    $pass = htmlentities($_POST["password"]);
    if(getPass($username)){
        if(isset(getPass($username)["pass"]) && password_verify($pass, getPass($username)["pass"])){
            $mpd = true;
            $_SESSION["username"] = $username;
            header("location:Home.php");
        }else{
            $mdp = false;
        }
    }else{
        $knowMail = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="../Images/icon.png" type="image/png">
    <link rel="stylesheet" href="../style/login.css"></link>
    <title>Connection</title>
</head>
<body>
    <h1>MovieDB</h1>
    <div class="container">
        <form action="login.php" method="post">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" minlength=3 required="required"/>
            <p id="testEmail" class="hidden wrong">enter valid email</p>
            <p id="unknownEmail" class="hidden wrong">unknown email</p>
            <label for="Password">Password</label>
            <input type="password" name="password" id="password" required="required"/>
            <p id="testPass" class="hidden wrong">wrong password</p>
            <input id="loginSubmit" type="submit" id="submit" value="login"/>
        </form>
    </div>
    <script src="js/script.js"></script>
    <?php 
    if(isset($mdp) && !$mdp){
            echo <<<HTML
                <script>
                    document.getElementById("testPass").classList.remove("hidden");
                </script>
            HTML;
    }else if(isset($knowMail) && !($knowMail)){
        echo <<<HTML
                <script>
                    document.getElementById("unknownEmail").classList.remove("hidden");
                </script>
            HTML;
    }
    ?>
</body>
</html>