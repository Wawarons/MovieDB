<?php 
session_start();
require_once("Movies.php");
require_once("Bdd.php");
if(!isset($_SESSION["username"])){
    header("location:Error.php");
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="../Images/icon.png" type="image/png">
    <link rel="stylesheet" href="../style/Home.css"></link>
    <title>Accueil</title>
</head>
<body>
    <?php require_once("navBar.php") ?>
    <h1>MovieDB</h1>
    <form id="searchBar" action="Home.php" method="get">
    <input type="search" name="query" id="query" required="required"/>
    <select name="category" id="category">
        <option name="movie" value="movie">Movies</option selected>
        <option name="person" value="person">People</option>
        <option name="collection" value="collection">Collections</option>
        <option name="keyword" value="keyword">Keywords</option>
        <option name="tv" value="tv">TV shows</option>
    </select>
    <input type="submit" id="submit" value="Search"/>
</form>
<div id="container">
    <?php if(isset($_GET["query"])){
        if(isset($_GET) && isset($_GET["category"]) && isset($_GET["query"])){
            search(htmlentities($_GET["category"]), htmlentities($_GET["query"]));
        };
    }else{
        global $currentUrl;
        getNextPages($currentUrl);
    }
    ?>
        <script src="js/script.js"></script>
</div>
</body>
</html>