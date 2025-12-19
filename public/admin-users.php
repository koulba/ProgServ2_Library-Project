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

$errors = [];
$success = '';

// Traitement des actions admin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'change_role':
                $userId = $_POST['user_id'] ?? 0;
                $newRole = $_POST['new_role'] ?? '';

                $result = updateUserRole($userId, $newRole);
                if ($result['success']) {
                    $success = $translations['user_role_updated'];
                } else {
                    $errors[] = $translations[$result['error']] ?? $translations['db_error'];
                }
                break;

            case 'delete_user':
                $userId = $_POST['user_id'] ?? 0;

                $result = deleteUser($userId);
                if ($result['success']) {
                    $success = $translations['user_deleted'];
                } else {
                    $errors[] = $translations[$result['error']] ?? $translations['db_error'];
                }
                break;
        }
    }
}

// Récupérer tous les utilisateurs
$users = getAllUsers();

include '../src/partials/header.php';
?>

<main class="container">
    <h1><?= $translations['admin_panel'] ?> - <?= $translations['user_management'] ?></h1>

    <p>
        <a href="index.php"><button><?= $translations['back_home'] ?></button></a>
        <a href="admin-books.php"><button><?= $translations['admin_books'] ?></button></a>
    </p>

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

    <?php if ($success) : ?>
        <div class="success-message">
            <p><?= htmlspecialchars($success) ?></p>
        </div>
    <?php endif; ?>

    <h2><?= $translations['all_users'] ?></h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th><?= $translations['username'] ?></th>
                <th><?= $translations['email'] ?></th>
                <th><?= $translations['role'] ?></th>
                <th><?= $translations['created_at'] ?></th>
                <th><?= $translations['actions'] ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <strong class="<?= $user['role'] === 'admin' ? 'role-admin' : 'role-user' ?>">
                            <?= $user['role'] === 'admin' ? $translations['admin'] : $translations['user'] ?>
                        </strong>
                    </td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['user_id']) : ?>
                            <!-- Formulaire pour changer le rôle -->
                            <form method="POST" class="inline-form" onsubmit="return confirm('<?= $translations['confirm_change_role'] ?>');">
                                <input type="hidden" name="action" value="change_role">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="hidden" name="new_role" value="<?= $user['role'] === 'admin' ? 'user' : 'admin' ?>">
                                <button type="submit">
                                    <?= $user['role'] === 'admin' ? '→ ' . $translations['user'] : '→ ' . $translations['admin'] ?>
                                </button>
                            </form>

                            <!-- Formulaire pour supprimer -->
                            <form method="POST" class="inline-form-no-margin" onsubmit="return confirm('<?= $translations['confirm_delete_user'] ?>');">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn-delete">
                                    <?= $translations['delete_user'] ?>
                                </button>
                            </form>
                        <?php else : ?>
                            <span class="current-user"><?= $translations['welcome_user'] ?> (<?= $translations['user'] ?>)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include '../src/partials/footer.php'; ?>
