<?php
    ini_set('display_startup_errors', 1); // Report compiler (syntax) errors
    ini_set('display_errors', 1); // Report run-time errors
    error_reporting(E_ALL); // Do not hide any errors
    session_start();
    require_once "/home/common/mail.php";
    processPageRequest();

function addMovieToCart($movieID) {
    $movies = readMovieData();
    array_push($movies, $movieID);
    writeMovieData($movies);
    displayCart();
}

function checkout($name, $address) {
    //TODO implement
    $html = "<table>";
    $movies = readMovieData();
    foreach ($movies as $movieId) {
        $movie = file_get_contents("https://www.omdbapi.com/?apikey=240096c9&i={$movieId}&type=movie&r=json");
        $array = json_decode($movie, true);
        $poster = $array["Poster"];
        $title = $array["Title"];
        $year = $array["Year"];
        $titleYear = ($title . ' (' . $year . ')');


        $html .= "<tr>";
        $html .= "<td><img src='{$poster}' height='100'></td>";
        $html .= "<td><a target='_blank' href='https://www.imdb.com/title/{$movieId}'>{$titleYear}</a></td>";
        $html .= "</tr>";
    }
    $html .= "</table>";
    echo $html;
    $result = sendMail("308033762", $address, $name, "Your Receipt from myMovies!", $html);
}

function displayCart() {
    $movies = readMovieData();
    echo "<script src='./script.js'></script>";
    require_once "./templates/cart_form.html";
}

function processPageRequest() {
    if (!isset($_GET["action"])) {
       displayCart();
    } else {
        $action = $_GET["action"];
        if ($action == "add") {
            addMovieToCart($_GET["movie_id"]);
        } else if ($action == "checkout") {
            checkout($_SESSION["displayname"], $_SESSION["email"]);
        } else if ($action == "remove") {
            removeMovieFromCart($_GET["movie_id"]);
        }
    }
}

function readMovieData() {
    $file = fopen("./data/cart.db", "r");
    if (!$file) {
        echo "Error opening the file!";
        exit();
    }
    $movies = fgets($file);
    if (!$movies) {
        $movies = array();
    } else {
        $movies = explode(",", $movies);
    }
    fclose($file);
    return $movies;
}

function removeMovieFromCart($movieID) {
    $movies = readMovieData();
    for ($i = 0; $i < count($movies); $i++) {
        if ($movies[$i] == $movieID) {
            array_splice($movies, $i, 1);
        }
    }
    writeMovieData($movies);
    displayCart();
}

function writeMovieData($array) {
    $file = fopen("./data/cart.db", "w");
    fwrite($file, implode(",",$array));
}

