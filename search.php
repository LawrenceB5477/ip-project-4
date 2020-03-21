<?php
ini_set('display_startup_errors', 1); // Report compiler (syntax) errors
ini_set('display_errors', 1); // Report run-time errors
error_reporting(E_ALL); // Do not hide any errors
session_start();

processPageRequest();

function displaySearchForm() {
    echo "<script src='./script.js'></script>";
    require_once "./templates/search_form.html";
}

function displaySearchResults($searchString) {
    $results = file_get_contents('http://www.omdbapi.com/?apikey=240096c9&s='.urlencode($searchString).'&type=movie&r=json');
    $array = array();
    $results = json_decode($results, true);
    if ($results["Response"] != "False") {
        $array = $results["Search"];
    }
    echo "<script src='./script.js'></script>";
    require_once "./templates/results_form.html";
}

function processPageRequest() {
    if (empty($_POST)) {
        displaySearchForm();
    } else {
        displaySearchResults($_POST["keyword"]);
    }
}