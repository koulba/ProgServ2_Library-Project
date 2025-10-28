<?php
require_once '../src/config/database.php';
require_once '../src/functions.php';

// On vérifie si l'ID du livre est passé dans l'URL
if (isset($_GET["id"])) {
    // On récupère l'ID du livre
    $bookId = $_GET["id"];

    // On supprime 
    removeBook($bookId);

    header("Location: index.php");
    exit();
} else {
    // Si l'ID n'est pas passé dans l'URL, on redirige vers la page d'accueil
    header("Location: index.php");
    exit();
}