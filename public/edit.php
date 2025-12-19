<?php
require_once '../src/auth_required.php';
require_once '../src/config/database.php';
require_once '../src/functions.php';


$db = new Database();
$pdo = $db->getPdo();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$bookId = $_GET['id'];


require_once __DIR__ . '/../src/i18n/load-translation.php';
$lang = $_COOKIE['lang'] ?? 'fr';
$translations = loadTranslation($lang);


$book = getBookById($bookId);

if (!$book) {
    header("Location: index.php");
    exit();
}

$errors = [];
$success = '';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"] ?? '';
    $author = $_POST["author"] ?? '';
    $releasedate = $_POST["releasedate"] ?? null;
    $isbn = $_POST["isbn"] ?? null;
    $editor = $_POST["editor"] ?? null;
    $saga = $_POST["saga"] ?? null;
    $note = $_POST["note"] ?? null;

    if (!$name) $errors[] = $translations['book_name_required'] ?? 'Le nom du livre est requis';
    if (!$author) $errors[] = $translations['book_author_required'] ?? "L'auteur est requis";

    if (empty($errors)) {
        // Mettre à jour le livre
        if (updateBook($bookId, $name, $author, $releasedate, $isbn, $editor, $saga, $note)) {
            $success = $translations['book_updated_success'] ?? "Le livre a été modifié avec succès !";
            // Recharger les données du livre
            $book = getBookById($bookId);
        } else {
            $errors[] = $translations['db_error'] ?? "Erreur lors de la modification";
        }
    }
}

include '../src/partials/header.php';
?>

<main class="container">
    <h1><?= $translations['edit_book'] ?? 'Modifier le livre' ?></h1>
    <p><a href="index.php"><?= $translations['back_to_home'] ?? 'Retour à l\'accueil' ?></a></p>

    <?php if (!empty($success)) : ?>
        <p style="color:green; background: #d4edda; padding: 10px; border-radius: 5px; border: 1px solid #c3e6cb;">
            <?= htmlspecialchars($success) ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($errors)) : ?>
        <div style="color:#721c24; background: #f8d7da; padding: 10px; border-radius: 5px; border: 1px solid #f5c6cb; margin-bottom: 20px;">
            <p><strong><?= $translations['form_errors'] ?? 'Erreurs dans le formulaire' ?> :</strong></p>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="edit.php?id=<?= htmlspecialchars($bookId) ?>" method="POST">
        <label for="name"><?= $translations['book_name'] ?? 'Nom' ?> :</label>
        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($book['name']) ?>">

        <label for="author"><?= $translations['book_author'] ?? 'Auteur' ?> :</label>
        <input type="text" id="author" name="author" required value="<?= htmlspecialchars($book['author']) ?>">

        <label for="releasedate"><?= $translations['book_releasedate'] ?? 'Date de sortie' ?> :</label>
        <input type="date" id="releasedate" name="releasedate" value="<?= htmlspecialchars($book['releasedate'] ?? '') ?>">

        <label for="isbn"><?= $translations['book_isbn'] ?? 'ISBN' ?> :</label>
        <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>">

        <label for="editor"><?= $translations['book_editor'] ?? 'Éditeur' ?> :</label>
        <input type="text" id="editor" name="editor" value="<?= htmlspecialchars($book['editor'] ?? '') ?>">

        <label for="saga"><?= $translations['book_saga'] ?? 'Saga (numéro)' ?> :</label>
        <input type="number" id="saga" name="saga" value="<?= htmlspecialchars($book['saga'] ?? '') ?>">

        <label for="note"><?= $translations['book_note'] ?? 'Note' ?> :</label>
        <input type="number" id="note" name="note" min="0" max="10" value="<?= htmlspecialchars($book['note'] ?? '') ?>">

        <button type="submit"><?= $translations['update_book'] ?? 'Modifier le livre' ?></button>
        <a href="index.php"><button type="button"><?= $translations['cancel'] ?? 'Annuler' ?></button></a>
    </form>
</main>

<?php include '../src/partials/footer.php'; ?>
