<?php 
session_start(); 
require_once("Bdd.php");
if(!isset($_SESSION["username"])){
    header("location:Error.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/profile.css"></link>
    <link rel="shortcut icon" href="../Images/icon.png" type="image/png">
    <title>Profil</title>
</head>
<body>
    <?php require_once("navBar.php") ?>
            <div id="container">
            <div id ="profile-container">
                <img src="../Images/profile.png"/>
                <a id="logout" href="deconnexion.php" title="Oh non :(">Log out</a>
                <h3><?= $_SESSION["username"] ?></h3>
                <div id="filters">
                <form action="profile.php" method="post"> 
                <select name="trie">
                    <option value="ASC">Croissant</option>
                    <option value="DESC" selected>Decroissant</option>
                </select>
                <select name="genre">
                    <option value="">All</option>
                    <option value="Action">Action</option>
                    <option value="Animation">Animation</option>
                    <option value="Adventure">Aventure</option>
                    <option value="Comedy">Comedie</option>
                    <option value="Family">Famille</option>
                    <option value="Drame">Drame</option>
                </select>
                <input type="submit" value="filtrer"/>
            </form>
            </div>
            </div>
            <?php
            if(isset($_POST,$_POST["trie"], $_POST["genre"])){
                if($_POST["genre"] == ""){
                    $_POST["genre"] = null;
                }
                getMoviesRated($_SESSION["username"], $_POST["trie"], $_POST["genre"]);
            }else{
                getMoviesRated($_SESSION["username"]); 
            }
            ?>
    </div>
    <script src="js/script.js"></script>
</body>
</html>