<?php 
function dbConnect(){
    return new PDO("sqlite:../bdd/bdd.sqlite");
}

function search($category, $query){
    $query = str_replace(' ', '+', $query);
    $url =  empty($query) ? "https://api.themoviedb.org/3/search/$category?api_key=7ae5b548b2b7688fe71f95dadd7b7b1d&language=en-US&page=1&include_adult=false":"https://api.themoviedb.org/3/search/$category?api_key=7ae5b548b2b7688fe71f95dadd7b7b1d&query=$query";
    $curlPage = curl_init();
    global $currentPage;
    curl_setopt($curlPage, CURLOPT_URL, $url);
    curl_setopt($curlPage, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlPage, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curlPage, CURLOPT_SSL_VERIFYPEER, FALSE);
    $output = curl_exec($curlPage);
    showMovies($output, $category);
    curl_close($curlPage);
    $_SESSION["currentUrl"] = $url;
}
function getPass($user){
    /* Récupère l'id de $user dans la base de données */
    $bdd = dbConnect();
    $res = $bdd->prepare('SELECT pass FROM user WHERE username = ?');
    $res->execute(array($user));
    return $res->fetch();
}

function checkExist($idMovie, $idUser){
    //Vérifie si le film existe dans la base de donnée.
    $bdd = dbConnect();
    $res = $bdd->prepare('SELECT * FROM rating WHERE id_user = ? AND id_movie = ?');
    $output = $res->execute(array($idUser, $idMovie));
    $output = $res->fetch();
    return $output == null ? false:true;
}

function getInfoMovie($id){
    /* retourne les données d'un film à partir de son id */
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://api.themoviedb.org/3/movie/$id?api_key=7ae5b548b2b7688fe71f95dadd7b7b1d");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    $output = curl_exec($curl);
    $res = json_decode($output, true);
    return $res;
}

function getMovieData($id, $res){
    /* Range les données d'un film dans un array 
    $info = [genres, languages, duree, title, companie, date, description, poster, backdrop];
    */
    $urlPoster = "https://image.tmdb.org/t/p/original";
    $genres = isset($res["genres"]) && !empty($res["genres"]) ? $res["genres"] : array(["name"=>"unknown"]);
    $languages = isset($res["spoken_languages"]) && !empty($res["spoken_languages"])? $res["spoken_languages"] : array(["english_name" => "unknown"]);
    $duree = isset($res["runtime"]) && !empty($res["runtime"]) ? date("H\hi", $res["runtime"] * 60):"00h00";
    $title = isset($res['title']) ? $res['title'] : (isset($res['original_name']) ? $res['original_name'] : 'unknown');
    $companie = isset($res["production_companies"][0]["name"]) ? $res["production_companies"][0]["name"] :"unknown";
    $date = isset($res["release_date"]) && !empty($res["release_date"]) ? $res["release_date"] : "unknown";
    $description = !empty($res['overview']) ? $res["overview"] : "unknown";
    $poster = isset($res["poster_path"]) && !empty($res["poster_path"]) ? $urlPoster.$res["poster_path"]:"../Images/no-photo.jpg";
    $backdrop = isset($res['backdrop_path']) && !empty($res['backdrop_path']) ? $urlPoster.$res['backdrop_path']:"../Images/no-photo.jpg";
    $infos = array("id"=>$res["id"],"genres" => $genres, "languages" => $languages, "duree" => $duree, "title" => $title, "companie" => $companie, "date" => $date, "poster" => $poster, "backdrop_poster" => $backdrop, "description" => $description);
    return $infos;
}

function getIdUser($username){
    /* retourne l'id d'un utilisateur */
    $bdd = dbConnect();
    $idUser = $bdd->prepare("SELECT user_id FROM user WHERE username = ?");
    $idUser->execute(array($username));
    $outputUserId = $idUser->fetch();
    $idUser = $outputUserId["user_id"];
    return $idUser;
}

function rateMovie($username, $noteScript, $noteRealisation, $title, $idMovie,  $genre, $duree,  $date, $language, $companie){
    //Note un film scénario et réalisation.
    $bdd = dbConnect();
    $res = $bdd->prepare('SELECT * FROM movie WHERE movie_id = ?');
    $res->execute(array($idMovie));
    $output = $res->fetch();
    $idUser= getIdUser($username);

    if($output == null){ //Si film n'existe pas dans la base de donné on l'ajoute
        $query = $bdd->prepare("INSERT INTO movie (movie_id,movie_name,movie_type,movie_date,movie_language, movie_companie) VALUES (?,?,?,?,?,?)");
        $query->execute(array($idMovie, $title, $genre[0]["name"], $date, $language[0]["english_name"], $companie));
        $query->fetch();

        $rating = $bdd->prepare("INSERT INTO rating (rating_realisation, rating_script, id_user, id_movie) VALUES (?,?,?,?)");
        $rating->execute(array($noteRealisation, $noteScript, $idUser, $idMovie));
        $rating->fetch();
    }else if(checkExist($idMovie, $idUser)){ //Si le film a déjà été noté
        $query = $bdd->prepare("UPDATE rating SET rating_realisation = ?, rating_script = ? WHERE id_user = ? AND id_movie = ?");
        $query->execute(array($noteRealisation, $noteScript ,$idUser, $idMovie));
        $query->fetch();
    }else{//Sinon
        $rating = $bdd->prepare("INSERT INTO rating (rating_realisation, rating_script, id_user, id_movie) VALUES (?,?,?,?)");
        $rating->execute(array($noteRealisation, $noteScript, $idUser, $idMovie));
        $rating->fetch();
    }
    echo <<<HTML
        <script>
            alert("Film noté :)");
        </script>
    HTML;
}

function getMoviesRated($username, $trie=null, $genre=null){
    /* Retourne la liste des films notés de l'utilisateur en fonction du tri et du genre si différent de null */
    $bdd = dbConnect();
    if($genre && !$trie){
        $res = $bdd->prepare('SELECT * FROM movie m, rating r WHERE id_user = ? AND m.movie_id = r.id_movie AND movie_type = ?');
        $res->execute(array(getIdUser($username), $genre));
    }else if($genre && $trie){
        $res = $bdd->prepare('SELECT * FROM movie m, rating r WHERE id_user = ? AND m.movie_id = r.id_movie AND movie_type = ? ORDER BY r.rating_script + r.rating_realisation ' .  $trie);
        $res->execute(array(getIdUser($username), $genre));
    }else if(!$genre && $trie){
        $res = $bdd->prepare('SELECT * FROM movie m, rating r WHERE id_user = ? AND m.movie_id = r.id_movie ORDER BY r.rating_script + r.rating_realisation ' . $trie);
        $res->execute(array(getIdUser($username)));
    }else{
        $res = $bdd->prepare('SELECT * FROM rating WHERE id_user = ?');
        $res->execute(array(getIdUser($username)));
    }
    $output = $res->fetchAll();
    $size = sizeof($output);
    echo "<h3><em>Film noté(s): $size</em></h3>";
    if($size == 0){
        echo "<h3><em>Pas de film noté :)</em></h3>";
    }else{

        foreach($output as $array){
            $noteScript = $array["rating_script"];
            $noteRealisation = $array["rating_realisation"];
            $noteTotal = round(($noteRealisation+$noteScript)/2);
        $infos = getMovieData($array["id_movie"], getInfoMovie($array["id_movie"]));
        echo <<<HTML
            <div id="{$infos['id']}" class="movie-rated" style="background: url({$infos['backdrop_poster']});background-size: cover;">
                <img src="{$infos['poster']}" style="width:120px;height:auto;"/>
                <h3>{$infos["title"]}</h3>
                <div class="notes">
                <table>
                    <tr>
                        <th>Script</th>
                        <td>$noteScript</td>
                    </tr>
                    <tr>
                        <th>Real.</th>
                        <td>$noteRealisation</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>$noteTotal</td>
                    </tr>
                </table>
            </div>
        </div>
        HTML;
    }
}
}

?>