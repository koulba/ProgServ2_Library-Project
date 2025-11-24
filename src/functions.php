<?php

require_once 'config/database.php';

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//supprimer un livre
function removeBook($id) {

    global $pdo;
    $sql = "DELETE FROM books WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
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
    $note
) {

    global $pdo;
    $sql = "INSERT INTO books (
        name,
        author,
        releasedate,
        isbn,
        editor,
        saga,
        note
    ) VALUES (
        :name,
        :author,
        :releasedate,
        :isbn,
        :editor,
        :saga,
        :note
    )";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':releasedate', $releasedate);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->bindParam(':editor', $editor);
    $stmt->bindParam(':saga', $saga);
    $stmt->bindParam(':note', $note);

    $stmt->execute();

    $bookId = $pdo->lastInsertId();

    return $bookId;

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
            'email' => $_SESSION['email']
        ];
    }
    return null;
}