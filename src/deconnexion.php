<?php 
//Deconnecte l'utilisateur et le renvoie sur la page de connexion
session_start();
session_destroy();
header("location: login.php");
?>