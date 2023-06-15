<?php
session_start();
$_SESSION["currentUrl"] = "https://api.themoviedb.org/3/discover/movie?api_key=7ae5b548b2b7688fe71f95dadd7b7b1d";
header("location:Home.php");
?>
