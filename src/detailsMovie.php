<?php 
session_start();
require_once("Bdd.php");
if (isset($_POST, $_POST["rateRealisation"], $_POST["rateScript"])) {
    global $infos;
    $infos = unserialize($_SESSION["movieInfo"]);
    $noteScript = htmlentities($_POST["rateScript"]);
    $noteRealisation = htmlentities($_POST["rateRealisation"]);
    rateMovie($_SESSION["username"], $noteScript, $noteRealisation, $infos["title"], $infos["id"], $infos["genres"], $infos["duree"], $infos["date"], $infos["languages"], $infos["companie"]);
}

function getMovieDetails($id)
{
    global $infos;
    $res = getInfoMovie($id);
    if(isset($res["status_code"]) && $res["status_code"] == 34){
        echo "<h1>No movie here</h1>";
    }else{
        $infos = getMovieData($id, $res);
        $note = isset($res["vote_average"]) && !empty($res["vote_average"]) ? floatval($res['vote_average'] * 10):"0";
        $_SESSION["movieInfo"] = serialize($infos);
        $urlPoster = "https://image.tmdb.org/t/p/original";
        
        if($infos["backdrop_poster"] != "../Images/no-photo.jpg"){
            echo <<<HTML
                <style>
                    body {    
                        background:url({$infos["backdrop_poster"]});
                        background-position: center;
                        background-size: cover;
                    }
                    </style>
            HTML;
        }
        echo <<<HTML
            <div id="container-movieDetails">
            <div id="top">
                <div id="picTitle">
                    <img id="poster_movie" src="{$infos['poster']}"/>
                    <div id="description">
                        <h2>{$infos["title"]}</h2>
                            <p>{$infos["description"]}</p>
                        </div>
                    </div>
                </div>
                <table>
                    <tr>
                        <th>Compagnie</th>
                        <th>Genre(s)</th>
                        <th>Date</th>
                        <th>Durée</th>
                        <th>Langage(s)</th>
                        <th>Notes</th>
                    </tr>
                    <tr>
                        <td>{$infos["companie"]}</td>
                        <td>
                <ul>
        HTML;
        foreach ($infos["genres"] as $genre) {
                echo "<li>{$genre['name']}</li>";
            };
        echo <<<HTML
                </ul></td>
                <td><p>{$infos["date"]}</p></td>
                <td><p>{$infos["duree"]}</p></td>
                <td><ul>
        HTML;
        foreach ($infos["languages"] as $lang) {
            echo "<li>{$lang['english_name']}</li>";
        }
        ;
        echo <<<HTML
            </ul></td>
            <td><div id="rate">
                <p>$note%</p>
                <p>{$res['vote_count']}<img src="../Images/users.png" style="width: 16px;margin-left:5px;"/></p></td>
            </div></td>
            </tr>
            </table>
            <div id="userNote">
                    <form id="noteForm" action="" method="post">
                        <label for="rateRealisation">Realisation</label>
                        <input type="number" class="noteFormInput" name="rateRealisation" value="0" step="0.1" min=0 max=10 placeholder="8.5"/>
                        <label for="rateRealisation">Script</label>
                        <input type="number" class="noteFormInput" name="rateScript" value="0" step="0.1" min=0 max=10 placeholder="8.5"/>
                        <input type="submit" id="noteSubmit" value="noter"/>
                    </form>
                </div>
                </div>
        HTML;
        
    }
}
?>
<html>
    <head>
        <title>Movie</title>
        <link rel="shortcut icon" href="../Images/icon.png" type="image/png">
        <link rel="stylesheet" href="../style/Home.css"></link>
    </head>
    <body>
        <?php require_once("navBar.php") ?>
        <?php getMovieDetails($_GET['id']) ?>
    </body>
</html>