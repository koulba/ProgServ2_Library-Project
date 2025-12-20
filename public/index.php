<?php
require_once '../src/auth_required.php';
require_once '../src/functions.php';
require_once '../src/config/database.php';

// Connexion à la DB
$db = new Database();
$pdo = $db->getPdo();

// Récupère tous les livres de l'utilisateur connecté
$userId = $_SESSION['user_id'];
$books = getBooksByUserId($userId);


include '../src/partials/header.php';

?>

<main>
    <h1><?= $translations['home_title'] ?></h1>
    <p><?= $translations['home_description'] ?></p>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'access_denied') : ?>
        <div class="error-message">
            <p><strong><?= $translations['access_denied'] ?></strong></p>
        </div>
    <?php endif; ?>

    <h2><?= $translations['books_list'] ?></h2>

    <p><a href="create.php"><button><?= $translations['add_book'] ?></button></a></p>

    <table>
        <thead>
            <tr>
                <th><?= $translations['name'] ?></th>
                <th><?= $translations['author'] ?></th>
                <th><?= $translations['publication_date'] ?></th>
                <th><?= $translations['editor'] ?></th>
                <th><?= $translations['note'] ?></th>
                <th><?= $translations['actions'] ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book) { ?>
                <tr>
                    <td><?= htmlspecialchars($book['name']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= htmlspecialchars($book['releasedate']) ?></td>
                    <td><?= htmlspecialchars($book['editor']) ?></td>
                    <td><?= htmlspecialchars($book['note']) ?></td>
                    <td>
                        <a href="delete.php?id=<?= htmlspecialchars($book['id']) ?>"><button><?= $translations['delete'] ?></button></a>
                        <a href="edit.php?id=<?= htmlspecialchars($book['id']) ?>"><button><?= $translations['edit'] ?></button></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>

<?php include '../src/partials/footer.php'; ?>