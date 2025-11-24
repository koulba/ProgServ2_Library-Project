<?php
$lang = $_GET['lang'] ?? 'fr';

if (in_array($lang, ['fr', 'en'])) {
    setcookie('lang', $lang, time() + (365 * 24 * 60 * 60), '/');
}

$allowedPages = ['index.php', 'login.php', 'register.php', 'create.php'];
$redirect = 'login.php';

if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
    $page = basename($referer);
    if (in_array($page, $allowedPages)) {
        $redirect = $page;
    }
}

header("Location: $redirect");
exit();
