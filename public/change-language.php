<?php
// Récupère la langue demandée
$lang = $_GET['lang'] ?? 'fr';

// Vérifie que la langue est valide (fr ou en)
if (in_array($lang, ['fr', 'en'])) {
    // Définir un cookie qui expire dans 1 an (365 jours)
    setcookie('lang', $lang, time() + (365 * 24 * 60 * 60), '/');
}

// Redirige vers la page précédente ou l'accueil
$redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $redirect");
exit();
