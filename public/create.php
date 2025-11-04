<?php
require_once '../src/config/database.php';
require_once '../src/functions.php';
require_once __DIR__ . '/../src/i18n/load-translation.php';

// Charger les traductions
$lang = $_COOKIE['lang'] ?? 'fr';
$translations = loadTranslation($lang);

// Connexion à la DB
$db = new Database();
$pdo = $db->getPdo();

// Soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"] ?? '';
    $author = $_POST["author"] ?? '';
    $releasedate = $_POST["releasedate"] ?? null;
    $isbn = $_POST["isbn"] ?? null;
    $editor = $_POST["editor"] ?? null;
    $saga = $_POST["saga"] ?? null;
    $note = $_POST["note"] ?? null;

    // Validation
    $errors = [];
    if (!$name) $errors[] = $translations['book_name_required'];
    if (!$author) $errors[] = $translations['book_author_required'];

    if (empty($errors)) {
        // Ajouter le livre dans la base
        addBook($name, $author, $releasedate, $isbn, $editor, $saga, $note);
    $success = $translations['book_added_success'];
    // Réinitialiser les champs
    $name = $author = $releasedate = $isbn = $editor = $saga = $note = '';
}
}

include '../src/partials/header.php';
?>

<main class="container">
    <h1><?= $translations['add_new_book'] ?></h1>
    <p><a href="index.php"><?= $translations['back_home'] ?></a></p>

    <?php if (!empty($success)) : ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)) : ?>
        <p style="color:red;"><?= $translations['form_errors'] ?></p>
        <ul>
            <?php foreach ($errors as $e) echo "<li>$e</li>"; ?>
        </ul>
    <?php endif; ?>

    <form action="create.php" method="POST">
        <label for="name"><?= $translations['name'] ?> :</label>
        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($name ?? '') ?>">

        <label for="author"><?= $translations['author'] ?> :</label>
        <input type="text" id="author" name="author" required value="<?= htmlspecialchars($author ?? '') ?>">

        <label for="releasedate"><?= $translations['release_date'] ?> :</label>
        <input type="date" id="releasedate" name="releasedate" value="<?= htmlspecialchars($releasedate ?? '') ?>">

        <label for="isbn"><?= $translations['isbn'] ?> :</label>
        <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($isbn ?? '') ?>">

        <label for="editor"><?= $translations['editor'] ?> :</label>
        <input type="text" id="editor" name="editor" value="<?= htmlspecialchars($editor ?? '') ?>">

        <label for="saga"><?= $translations['saga'] ?> :</label>
        <input type="number" id="saga" name="saga" value="<?= htmlspecialchars($saga ?? '') ?>">

        <label for="note"><?= $translations['note'] ?> :</label>
        <input type="number" id="note" name="note" min="0" max="10" value="<?= htmlspecialchars($note ?? '') ?>">

        <button type="submit"><?= $translations['submit'] ?></button>
        <button type="reset"><?= $translations['reset'] ?></button>
    </form>
</main>

<?php include '../src/partials/footer.php'; ?>
