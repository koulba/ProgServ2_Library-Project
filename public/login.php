<?php
require_once '../src/config/database.php';
require_once '../src/functions.php';
require_once __DIR__ . '/../src/i18n/load-translation.php';


$lang = $_COOKIE['lang'] ?? 'fr';
$translations = loadTranslation($lang);

// Connexion à la DB
$db = new Database();
$pdo = $db->getPdo();


if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$username = '';
$errors = [];
$success = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';

    if (empty($username)) {
        $errors[] = $translations['username_required'] . ' / ' . $translations['email_required'];
    }

    if (empty($password)) {
        $errors[] = $translations['password_required'];
    }

    if (empty($errors)) {
        $result = loginUser($username, $password);

        if ($result['success']) {

            header('Location: index.php?login=success');
            exit();
        } else {
            $errors[] = $translations[$result['error']] ?? $translations['login_failed'];
        }
    }
}

include '../src/partials/header.php';
?>

<main class="container">
    <h1><?= $translations['login_title'] ?></h1>

    <?php if (!empty($errors)) : ?>
        <div style="color:#721c24; background: #f8d7da; padding: 10px; border-radius: 5px; border: 1px solid #f5c6cb; margin-bottom: 20px;">
            <p><strong><?= $translations['form_errors'] ?></strong></p>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="username"><?= $translations['username'] ?> / <?= $translations['email'] ?> :</label>
        <input type="text" id="username" name="username" required value="<?= htmlspecialchars($username) ?>" placeholder="<?= $translations['username'] ?> <?= $lang === 'fr' ? 'ou' : 'or' ?> <?= $translations['email'] ?>">

        <label for="password"><?= $translations['password'] ?> :</label>
        <input type="password" id="password" name="password" required>

        <button type="submit"><?= $translations['login_button'] ?></button>
    </form>

    <p style="margin-top: 20px;">
        <?= $translations['no_account_yet'] ?>
        <a href="register.php"><?= $translations['register_here'] ?></a>
    </p>

    <p><a href="index.php"><?= $translations['back_home'] ?></a></p>
</main>

<?php include '../src/partials/footer.php'; ?>
