<?php
require_once '../src/auth_required.php';
require_once '../src/config/database.php';
require_once '../src/functions.php';


// On vérifie si l'ID du livre est passé dans l'URL
if (isset($_GET["id"])) {
    // On récupère l'ID du livre et l'utilisateur connecté
    $bookId = $_GET["id"];
    $userId = $_SESSION['user_id'];

    // On supprime (seulement si l'utilisateur en est le propriétaire, sauf admin)
    if (isAdmin()) {
        // Admin peut tout supprimer
        removeBook($bookId);
    } else {
        // Utilisateur normal ne peut supprimer que ses propres livres
        removeBook($bookId, $userId);
    }

    header("Location: index.php");
    exit();
} else {
    // Si l'ID n'est pas passé dans l'URL, on redirige vers la page d'accueil
    header("Location: index.php");
    exit();
}