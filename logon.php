<?php
ini_set('display_startup_errors', 1); // Report compiler (syntax) errors
ini_set('display_errors', 1); // Report run-time errors
error_reporting(E_ALL); // Do not hide any errors
session_start();

processPageRequest();

function authenticateUser($username, $password) {
    $file = fopen("./data/credentials.db", "r");
    if (!$file) {
        echo "Error reading the credentials file!";
        exit();
    }

    $creds = fgets($file);
    $creds = explode(",", $creds);
    if (count($creds) != 4) {
        echo "Error! Credientials invalid.";
        exit(1);
    }

    if ($creds[0] == $username and $creds[1] == $password) {
        $_SESSION["displayname"] = $creds[2];
        $_SESSION["email"] = $creds[3];
        echo "<script>window.location.href = 'index.php';</script>";
    } else {
        displayLoginForm("Invalid login credentials!");
    }

    fclose($file);

}

function displayLoginForm($message="") {
    echo "<script src='./script.js'></script>";
    require_once "./templates/logon_form.html";
}

function processPageRequest() {
    session_unset();
    if (empty($_POST)) {
        displayLoginForm("");
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];
        authenticateUser($username, $password);
    }
}
