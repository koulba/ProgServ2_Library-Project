<?php
// Charger les traductions
require_once __DIR__ . '/../i18n/load-translation.php';

$lang = $_COOKIE['lang'] ?? 'fr';
$translations = loadTranslation($lang);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $translations['page_title'] ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <header>
        <nav>
            <h1><?= $translations['page_title'] ?></h1>
            <ul>
                <li><a href="index.php"><?= $lang === 'fr' ? 'Accueil' : 'Home' ?></a></li>
                <li><a href="create.php"><?= $translations['add_book'] ?></a></li>
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li><a href="admin-books.php" class="admin-link"><?= $translations['admin_books'] ?></a></li>
                        <li><a href="admin-users.php" class="admin-link"><?= $translations['admin_users'] ?></a></li>
                    <?php endif; ?>
                    <li><span class="user-info"><?= $translations['welcome_user'] ?>, <?= htmlspecialchars(getCurrentUser()['username']) ?> <?= isAdmin() ? '(' . $translations['admin'] . ')' : '' ?></span></li>
                    <li><a href="logout.php"><?= $translations['logout'] ?></a></li>
                <?php else: ?>
                    <li><a href="login.php"><?= $translations['login'] ?></a></li>
                    <li><a href="register.php"><?= $translations['register'] ?></a></li>
                <?php endif; ?>
            </ul>
            <div class="language-switcher">
                <a href="change-language.php?lang=fr" class="<?= $lang === 'fr' ? 'active' : '' ?>">FR</a>
                <span>|</span>
                <a href="change-language.php?lang=en" class="<?= $lang === 'en' ? 'active' : '' ?>">EN</a>
            </div>
        </nav>
    </header>