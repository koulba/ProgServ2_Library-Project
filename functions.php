<?php

require './database.php';

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
    $autor,
    $releasedate,
    $isbn,
    $editor,
    $saga,
    $note
) {

    global $pdo;
    $sql = "INSERT INTO books (
        name,
        autor,
        releasedate,
        isbn,
        editor,
        saga,
        note
    ) VALUES (
        :name,
        :autor,
        :releasedate,
        :isbn,
        :editor,
        :saga,
        :note
    )";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':releasedate', $releasedate);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->bindParam(':editor', $editor);
    $stmt->bindParam(':saga', $saga);
    $stmt->bindParam(':note', $note);

    $stmt->execute();

    $bookId = $pdo->lastInsertId();

    return $bookId;

}