<?php
require_once '../src/admin_required.php';
require_once '../src/functions.php';
require_once '../src/config/database.php';
require_once __DIR__ . '/../src/i18n/load-translation.php';

$lang = $_COOKIE['lang'] ?? 'fr';
$translations = loadTranslation($lang);

// Connexion à la DB
$db = new Database();
$pdo = $db->getPdo();

// Récupère tous les livres avec des informations détaillées (incluant le nom d'utilisateur)
$stmt = $pdo->query("
    SELECT books.*, users.username
    FROM books
    LEFT JOIN users ON books.user_id = users.id
    ORDER BY books.id DESC
");
$books = $stmt->fetchAll();

include '../src/partials/header.php';
?>

<main class="container">
    <h1><?= $translations['admin_panel'] ?> - <?= $translations['admin_books'] ?></h1>

    <p>
        <a href="index.php"><button><?= $translations['back_home'] ?></button></a>
        <a href="admin-users.php"><button><?= $translations['admin_users'] ?></button></a>
        <a href="create.php"><button><?= $translations['add_book'] ?></button></a>
    </p>

    <h2><?= $translations['all_books'] ?> (<?= count($books) ?> livre<?= count($books) > 1 ? 's' : '' ?>)</h2>

    <?php if (empty($books)) : ?>
        <p class="empty-state">
            <?= $lang === 'fr' ? 'Aucun livre dans la base de données.' : 'No books in the database.' ?>
        </p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th><?= $translations['name'] ?></th>
                    <th><?= $translations['author'] ?></th>
                    <th><?= $lang === 'fr' ? 'Propriétaire' : 'Owner' ?></th>
                    <th><?= $translations['publication_date'] ?></th>
                    <th><?= $translations['isbn'] ?></th>
                    <th><?= $translations['editor'] ?></th>
                    <th><?= $translations['saga'] ?></th>
                    <th><?= $translations['note'] ?></th>
                    <th><?= $translations['actions'] ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book) : ?>
                    <tr>
                        <td><?= htmlspecialchars($book['id']) ?></td>
                        <td><strong><?= htmlspecialchars($book['name']) ?></strong></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['username'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($book['releasedate']) ?></td>
                        <td><?= htmlspecialchars($book['isbn']) ?></td>
                        <td><?= htmlspecialchars($book['editor']) ?></td>
                        <td><?= htmlspecialchars($book['saga']) ?></td>
                        <td><?= htmlspecialchars($book['note']) ?>/10</td>
                        <td>
                            <a href="delete.php?id=<?= htmlspecialchars($book['id']) ?>">
                                <button class="btn-delete">
                                    <?= $translations['delete'] ?>
                                </button>
                            </a>
                            <a href="edit.php?id=<?= htmlspecialchars($book['id']) ?>">
                                <button><?= $translations['edit'] ?></button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="info-box">
        <p>
            <strong><?= $lang === 'fr' ? 'Info:' : 'Info:' ?></strong>
            <?= $lang === 'fr'
                ? 'Cette page affiche tous les livres de la bibliothèque. En tant qu\'administrateur, vous avez accès à toutes les fonctionnalités de gestion.'
                : 'This page displays all books in the library. As an administrator, you have access to all management features.'
            ?>
        </p>
    </div>
</main>

<?php include '../src/partials/footer.php'; ?>
