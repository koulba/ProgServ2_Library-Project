<?php
require_once '../src/config/database.php';
require_once '../src/functions.php';
require_once __DIR__ . '/../src/i18n/load-translation.php';


$lang = $_COOKIE['lang'] ?? 'fr';
$translations = loadTranslation($lang);

// Connexion à la DB
$db = new Database();
$pdo = $db->getPdo();


$username = $email = '';
$errors = [];
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    if (empty($username)) {
        $errors[] = $translations['username_required'];
    }

    if (empty($email)) {
        $errors[] = $translations['email_required'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $translations['email_invalid'];
    }

    if (empty($password)) {
        $errors[] = $translations['password_required'];
    } elseif (strlen($password) < 6) {
        $errors[] = $translations['password_min_length'];
    }

    if ($password !== $confirm_password) {
        $errors[] = $translations['passwords_not_match'];
    }


    if (empty($errors)) {
        $result = registerUser($username, $email, $password);

        if ($result['success']) {
            $success = $translations['registration_success'];

            $username = $email = '';
        } else {
            $errors[] = $translations[$result['error']] ?? $translations['db_error'];
        }
    }
}

include '../src/partials/header.php';
?>

<main class="container">
    <h1><?= $translations['register_title'] ?></h1>

    <?php if (!empty($success)) : ?>
        <p class="success-message-alt">
            <?= htmlspecialchars($success) ?>
        </p>
        <p><a href="login.php"><button><?= $translations['login_here'] ?></button></a></p>
    <?php endif; ?>

    <?php if (!empty($errors)) : ?>
        <div class="error-message">
            <p><strong><?= $translations['form_errors'] ?></strong></p>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (empty($success)) : ?>
    <form action="register.php" method="POST">
        <label for="username"><?= $translations['username'] ?> :</label>
        <input type="text" id="username" name="username" required value="<?= htmlspecialchars($username) ?>">

        <label for="email"><?= $translations['email'] ?> :</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email) ?>">

        <label for="password"><?= $translations['password'] ?> :</label>
        <input type="password" id="password" name="password" required minlength="6">

        <label for="confirm_password"><?= $translations['confirm_password'] ?> :</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="6">

        <button type="submit"><?= $translations['register_button'] ?></button>
    </form>

    <p class="mt-20">
        <?= $translations['already_have_account'] ?>
        <a href="login.php"><?= $translations['login_here'] ?></a>
    </p>
    <?php endif; ?>
</main>

<?php include '../src/partials/footer.php'; ?>
