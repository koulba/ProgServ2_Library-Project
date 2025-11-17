<?php
require_once '../src/config/database.php';
require_once '../src/functions.php';


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

    require_once __DIR__ . '/../src/i18n/load-translation.php';
    $lang = $_COOKIE['lang'] ?? 'fr';
    $translations = loadTranslation($lang);

    // validation
    $errors = [];
    if (!$name) $errors[] = $translations['book_name_required'];
    if (!$author) $errors[] = $translations['book_author_required'];

    if (empty($errors)) {
        // add le livre dans la base
        addBook($name, $author, $releasedate, $isbn, $editor, $saga, $note);
    $success = "Votre livre a bien été ajouté !";
    // réinitialiser
    $name = $author = $releasedate = $isbn = $editor = $saga = $note = '';
}
}

include '../src/partials/header.php';
?>

<main class="container">
    <h1>Ajouter un nouveau livre</h1>
    <p><a href="index.php">Retour à l'accueil</a></p>

    <?php if (!empty($errors)) : ?>
        <p style="color:red;">Le formulaire contient des erreurs :</p>
        <ul>
            <?php foreach ($errors as $e) echo "<li>$e</li>"; ?>
        </ul>
    <?php endif; ?>

    <form action="create.php" method="POST">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($name ?? '') ?>">

        <label for="author">Auteur :</label>
        <input type="text" id="author" name="author" required value="<?= htmlspecialchars($author ?? '') ?>">

        <label for="releasedate">Date de sortie :</label>
        <input type="date" id="releasedate" name="releasedate" value="<?= htmlspecialchars($releasedate ?? '') ?>">

        <label for="isbn">ISBN :</label>
        <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($isbn ?? '') ?>">

        <label for="editor">Éditeur :</label>
        <input type="text" id="editor" name="editor" value="<?= htmlspecialchars($editor ?? '') ?>">

        <label for="saga">Saga (numéro) :</label>
        <input type="number" id="saga" name="saga" value="<?= htmlspecialchars($saga ?? '') ?>">

        <label for="note">Note :</label>
        <input type="number" id="note" name="note" min="0" max="10" value="<?= htmlspecialchars($note ?? '') ?>">

        <button type="submit">Ajouter le livre</button>
        <button type="reset">Réinitialiser</button>
    </form>
</main>

<?php include '../src/partials/footer.php'; ?>
