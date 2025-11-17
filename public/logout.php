<?php
require_once '../src/functions.php';

// Déconnecter l'utilisateur
logoutUser();

// Rediriger vers la page de connexion
header('Location: login.php');
exit();
