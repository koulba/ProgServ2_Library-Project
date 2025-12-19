<?php
require_once '../src/auth_required.php';
require_once '../src/functions.php';
require_once '../src/config/database.php';

// Connexion à la DB
$db = new Database();
$pdo = $db->getPdo();

// Récupère tous les livres
$stmt = $pdo->query("SELECT * FROM books ORDER BY id DESC");
$books = $stmt->fetchAll();


include '../src/partials/header.php';

// Gestion mail
/*
require_once __DIR__ . '/../src/utils/autoloader.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

const MAIL_CONFIGURATION_FILE = __DIR__ . '/../src/config/mail.ini';

if (file_exists(MAIL_CONFIGURATION_FILE)) {
    $config = parse_ini_file(MAIL_CONFIGURATION_FILE, true);

    if ($config) {
        $host = $config['host'];
        $port = filter_var($config['port'], FILTER_VALIDATE_INT);
        $authentication = filter_var($config['authentication'], FILTER_VALIDATE_BOOLEAN);
        $username = $config['username'];
        $password = $config['password'];
        $from_email = $config['from_email'];
        $from_name = $config['from_name'];

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->Port = $port;
            $mail->SMTPAuth = $authentication;
            $mail->Username = $username;
            $mail->Password = $password;
            $mail->CharSet = "UTF-8";
            $mail->Encoding = "base64";

            // Expéditeur et destinataire
            $mail->setFrom($from_email, $from_name);
            $mail->addAddress('CHANGE_ME', 'CHANGE WITH YOUR NAME');

            // Contenu du mail
            $mail->isHTML(true);
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();

            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
*/

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