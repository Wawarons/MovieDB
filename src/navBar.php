<?php
echo <<<HTML
    <style>
       @import url(https://fonts.googleapis.com/css?family=Staatliches:regular);
nav {
    font-family: "Staatliches";
    letter-spacing: 2px;
    position: relative;
    top: 0;
    width: 100%;
    background-color: #7f00ff4d; 
}

nav ul {
    margin: 0;
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    text-align: center;
}

nav ul li {
    padding: 10px;
    width: 100%;
    color: white;
    font-weight: bold;
    transition: .3s
}

nav ul li:hover {
    box-shadow: inset 0 0 6px 2px #3e015be3;
}

nav ul li a {
    text-decoration: none;
    cursor: pointer;
    color: #fff;
    display: inline-block;
}

.active {
    border-bottom: solid 2px #f648f2;
}
    </style>
    <nav>
        <ul>
            <li><a href="goHome.php" id="homeNavBar" class="">Home</a></li>
            <li><a href="profile.php" id="profileNavBar"class="">Profile</a></li>
        </ul>
    </nav>
HTML;
?>