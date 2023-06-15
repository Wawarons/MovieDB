<?php
if((isset($_SESSION["currentUrl"]))){
    $currentUrl = $_SESSION["currentUrl"];
}else{
    $currentUrl = "https://api.themoviedb.org/3/discover/movie?api_key=7ae5b548b2b7688fe71f95dadd7b7b1d";
}

if(isset($_SESSION["currentPage"]) && isset($_POST["next"])){
    $currentPage = $_SESSION["currentPage"];
    $currentPage++;
}else if(isset($_SESSION["currentPage"]) && isset($_POST["previous"])){
    $currentPage = $_SESSION["currentPage"];
    if($currentPage > 1)
    $currentPage--;
}else{
    $currentPage = 1;
}
$_SESSION["currentPage"] = $currentPage;

function showMovies($output, $category){
    global $pageNumber;
    global $nextPage;
    $output = json_decode($output, true);
    $urlPoster="https://image.tmdb.org/t/p/original";
    if(empty($output["results"])){
        echo "<h1>Data not found.</h1>";
    }else{
        if($category == "person"){
            $res = $output["results"][0]["known_for"];
        }else{
            $res = $output["results"];
        }
        echo "<div id='container-cards'>";
        foreach($res as $array){
            $poster = $array["poster_path"] == null ? "../Images/no-photo.jpg":$urlPoster.$array["poster_path"];
            $title = isset($array['title']) ? $array['title']:(isset($array['original_name']) ? $array['original_name']:'Title not found');
            echo <<<HTML
            <div data-movie="$title" class="cardMovie" id="{$array['id']}" style="background:url({$poster});background-size: cover;background-repeat: no-repeat;">
                <h3>$title</h3>
            </div>
            HTML;
        };
        echo "</div>";
        echo "<form id='pagination' method='post' action='Home.php'>";
        if(isset($output["page"]) && $output["page"] > 1){
            echo"<button type='submit' id='previous' name='previous' class='round'>&#8249</button>";
        }
        if(isset($output["total_pages"]) && $output["total_pages"] > 1){
            echo "<button type='submit' id='next' name='next' class='round'>&#8250</button>";
        }
        echo "</form>";
    }
}

function getNextPages($url){
    global $currentPage;
    $curlPage = curl_init();
    curl_setopt($curlPage, CURLOPT_URL, $url."&page=".$currentPage);
    curl_setopt($curlPage, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlPage, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curlPage, CURLOPT_SSL_VERIFYPEER, FALSE);
    $output = curl_exec($curlPage);
    showMovies($output, null);
    $_SESSION["currentUrl"] = $url;
    curl_close($curlPage);
}
?>