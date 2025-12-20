<?php

require_once 'config/database.php';

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialiser la connexion PDO globale
$db = new Database();
$pdo = $db->getPdo();

//supprimer un livre (seulement si l'utilisateur en est le propriétaire)
function removeBook($id, $userId = null) {
    global $pdo;

    // Si userId est fourni, vérifier que l'utilisateur est propriétaire du livre
    if ($userId !== null) {
        $sql = "DELETE FROM books WHERE id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
    } else {
        // Pour la compatibilité (admin peut tout supprimer)
        $sql = "DELETE FROM books WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
    }

    return $stmt->execute();
}


//ajouter un livre
function addBook(
    $name,
    $author,
    $releasedate,
    $isbn,
    $editor,
    $saga,
    $note,
    $userId
) {

    global $pdo;
    $sql = "INSERT INTO books (
        name,
        author,
        releasedate,
        isbn,
        editor,
        saga,
        note,
        user_id
    ) VALUES (
        :name,
        :author,
        :releasedate,
        :isbn,
        :editor,
        :saga,
        :note,
        :user_id
    )";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':releasedate', $releasedate);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->bindParam(':editor', $editor);
    $stmt->bindParam(':saga', $saga);
    $stmt->bindParam(':note', $note);
    $stmt->bindParam(':user_id', $userId);

    $stmt->execute();

    $bookId = $pdo->lastInsertId();

    return $bookId;

}

//récupérer un livre par son ID
function getBookById($id, $userId = null) {
    global $pdo;

    // Si userId est fourni, vérifier que l'utilisateur est propriétaire
    if ($userId !== null) {
        $sql = "SELECT * FROM books WHERE id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
    } else {
        // Pour la compatibilité (admin peut tout voir)
        $sql = "SELECT * FROM books WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
    }

    $stmt->execute();
    return $stmt->fetch();
}

//récupérer tous les livres d'un utilisateur
function getBooksByUserId($userId) {
    global $pdo;
    $sql = "SELECT * FROM books WHERE user_id = :user_id ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetchAll();
}

//modifier un livre
function updateBook(
    $id,
    $name,
    $author,
    $releasedate,
    $isbn,
    $editor,
    $saga,
    $note,
    $userId = null
) {
    global $pdo;

    // Si userId est fourni, vérifier que l'utilisateur est propriétaire
    if ($userId !== null) {
        $sql = "UPDATE books SET
            name = :name,
            author = :author,
            releasedate = :releasedate,
            isbn = :isbn,
            editor = :editor,
            saga = :saga,
            note = :note
            WHERE id = :id AND user_id = :user_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
    } else {
        // Pour la compatibilité (admin peut tout modifier)
        $sql = "UPDATE books SET
            name = :name,
            author = :author,
            releasedate = :releasedate,
            isbn = :isbn,
            editor = :editor,
            saga = :saga,
            note = :note
            WHERE id = :id";

        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':releasedate', $releasedate);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->bindParam(':editor', $editor);
    $stmt->bindParam(':saga', $saga);
    $stmt->bindParam(':note', $note);

    return $stmt->execute();
}


// ============================================
// FONCTIONS D'AUTHENTIFICATION
// ============================================

// Inscrire un nouvel utilisateur
function registerUser($username, $email, $password) {
    global $pdo;

    // Vérifier si le nom d'utilisateur existe déjà
    $sql = "SELECT id FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->fetch()) {
        return ['success' => false, 'error' => 'username_exists'];
    }

    // Vérifier si l'email existe déjà
    $sql = "SELECT id FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->fetch()) {
        return ['success' => false, 'error' => 'email_exists'];
    }

    // Hasher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insérer le nouvel utilisateur
    $sql = "INSERT INTO users (username, email, password, created_at)
            VALUES (:username, :email, :password, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        return ['success' => true, 'user_id' => $pdo->lastInsertId()];
    }

    return ['success' => false, 'error' => 'db_error'];
}

// Connecter un utilisateur
function loginUser($username, $password) {
    global $pdo;

    $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $username);
    $stmt->execute();

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Créer la session utilisateur
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'] ?? 'user'; // Stocker le rôle de l'utilisateur

        return ['success' => true, 'user' => $user];
    }

    return ['success' => false, 'error' => 'login_failed'];
}

// Déconnecter un utilisateur
function logoutUser() {
    $_SESSION = [];

    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    session_destroy();
}

// Vérifier si un utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Obtenir l'utilisateur connecté
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email'],
            'role' => $_SESSION['role'] ?? 'user'
        ];
    }
    return null;
}

// Vérifier si l'utilisateur connecté est un administrateur
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Obtenir tous les utilisateurs (admin uniquement)
function getAllUsers() {
    global $pdo;

    $sql = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Mettre à jour le rôle d'un utilisateur (admin uniquement)
function updateUserRole($userId, $newRole) {
    global $pdo;

    // Vérifier que le rôle est valide
    if (!in_array($newRole, ['user', 'admin'])) {
        return ['success' => false, 'error' => 'invalid_role'];
    }

    $sql = "UPDATE users SET role = :role WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role', $newRole);
    $stmt->bindParam(':id', $userId);

    if ($stmt->execute()) {
        return ['success' => true];
    }

    return ['success' => false, 'error' => 'db_error'];
}

// Supprimer un utilisateur (admin uniquement)
function deleteUser($userId) {
    global $pdo;

    // Ne pas permettre la suppression de son propre compte
    if ($userId == $_SESSION['user_id']) {
        return ['success' => false, 'error' => 'cannot_delete_self'];
    }

    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userId);

    if ($stmt->execute()) {
        return ['success' => true];
    }

    return ['success' => false, 'error' => 'db_error'];
}

function sendEmail($email, $username) {
    require_once __DIR__ . '/utils/mail.php';
    return \Utils\sendWelcomeEmail($email, $username);
}
 