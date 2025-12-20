<?php
require_once '../src/auth_required.php';
require_once '../src/config/database.php';
require_once '../src/functions.php';

// Connexion à la DB
$db = new Database();
$pdo = $db->getPdo();

// Charger les traductions
require_once __DIR__ . '/../src/i18n/load-translation.php';
$lang = $_COOKIE['lang'] ?? 'fr';
$translations = loadTranslation($lang);

// Vérifier si un ID est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin-books.php');
    exit();
}

$bookId = $_GET['id'];
$userId = $_SESSION['user_id'];

// Récupérer le livre (vérifier que l'utilisateur en est le propriétaire, sauf admin)
if (isAdmin()) {
    $book = getBookById($bookId);
} else {
    $book = getBookById($bookId, $userId);
}

if (!$book) {
    header('Location: index.php');
    exit();
}

// Soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"] ?? '';
    $author = $_POST["author"] ?? '';
    $releasedate = $_POST["releasedate"] ?? null;
    $isbn = $_POST["isbn"] ?? null;
    $editor = $_POST["editor"] ?? null;
    $saga = $_POST["saga"] ?? null;
    $note = $_POST["note"] ?? null;

    // validation
    $errors = [];
    if (!$name) $errors[] = $translations['book_name_required'];
    if (!$author) $errors[] = $translations['book_author_required'];

    if (empty($errors)) {
        // Mettre à jour le livre dans la base (vérifier la propriété, sauf admin)
        $updateSuccess = false;
        if (isAdmin()) {
            $updateSuccess = updateBook($bookId, $name, $author, $releasedate, $isbn, $editor, $saga, $note);
        } else {
            $updateSuccess = updateBook($bookId, $name, $author, $releasedate, $isbn, $editor, $saga, $note, $userId);
        }

        if ($updateSuccess) {
            $success = $lang === 'fr' ? "Le livre a bien été modifié !" : "The book has been successfully updated!";
            // Recharger les données du livre
            if (isAdmin()) {
                $book = getBookById($bookId);
            } else {
                $book = getBookById($bookId, $userId);
            }
        } else {
            $errors[] = $lang === 'fr' ? "Erreur lors de la modification du livre." : "Error updating the book.";
        }
    }
}

include '../src/partials/header.php';
?>

<main class="container">
    <h1><?= $lang === 'fr' ? 'Modifier le livre' : 'Edit book' ?></h1>
    <p>
        <a href="admin-books.php"><?= $lang === 'fr' ? 'Retour à la liste des livres' : 'Back to book list' ?></a>
    </p>

    <?php if (!empty($errors)) : ?>
        <div class="error-message">
            <p><strong><?= $lang === 'fr' ? 'Le formulaire contient des erreurs :' : 'The form contains errors:' ?></strong></p>
            <ul>
                <?php foreach ($errors as $e) echo "<li>$e</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (isset($success)) : ?>
        <div class="success-message">
            <p><?= $success ?></p>
        </div>
    <?php endif; ?>

    <form action="edit.php?id=<?= htmlspecialchars($bookId) ?>" method="POST">
        <label for="name"><?= $translations['name'] ?> :</label>
        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($book['name'] ?? '') ?>">

        <label for="author"><?= $translations['author'] ?> :</label>
        <input type="text" id="author" name="author" required value="<?= htmlspecialchars($book['author'] ?? '') ?>">

        <label for="releasedate"><?= $translations['publication_date'] ?> :</label>
        <input type="date" id="releasedate" name="releasedate" value="<?= htmlspecialchars($book['releasedate'] ?? '') ?>">

        <label for="isbn"><?= $translations['isbn'] ?> :</label>
        <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>">

        <label for="editor"><?= $translations['editor'] ?> :</label>
        <input type="text" id="editor" name="editor" value="<?= htmlspecialchars($book['editor'] ?? '') ?>">

        <label for="saga"><?= $translations['saga'] ?> :</label>
        <input type="number" id="saga" name="saga" value="<?= htmlspecialchars($book['saga'] ?? '') ?>">

        <label for="note"><?= $translations['note'] ?> :</label>
        <input type="number" id="note" name="note" min="0" max="10" value="<?= htmlspecialchars($book['note'] ?? '') ?>">

        <button type="submit"><?= $lang === 'fr' ? 'Modifier le livre' : 'Update book' ?></button>
        <a href="admin-books.php"><button type="button"><?= $lang === 'fr' ? 'Annuler' : 'Cancel' ?></button></a>
    </form>
</main>

<?php include '../src/partials/footer.php'; ?>
