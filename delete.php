<?php
require 'functions.php';

// On vérifie si l'ID du livre est passé dans l'URL
if (isset($_GET["id"])) {
    // On récupère l'ID du livre de la superglobale `$_GET`
    $bookId = $_GET["id"];

    // On supprime le livre correspondant à l'ID
    removeBook($bookId);

    header("Location: index.php");
    exit();
} else {
    // Si l'ID n'est pas passé dans l'URL, on redirige vers la page d'accueil
    header("Location: index.php");
    exit();
}