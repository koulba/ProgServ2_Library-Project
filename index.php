<?php
require 'functions.php';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Libs Project</title>
</head>

<body>
    <h1>Page d'accueil du gestionnaire de livres</h1>
    <p>Utilise cette page pour visualiser et gérer tous tes livres.</p>

    <h2>Liste des livres</h2>

    <p><a href="create.php"><button>Ajouter un livre</button></a></p>

    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Date de parution</th>
                <th>Éditeur</th>
                <th>Note</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book) { ?>
                <tr>
                    <td><?= htmlspecialchars($book['name']) ?></td>
                    <td><?= htmlspecialchars($book['autor']) ?></td>
                    <td><?= htmlspecialchars($book['releasedate']) ?></td>
                    <td><?= htmlspecialchars($book['editor']) ?></td>
                    <td><?= htmlspecialchars($book['note']) ?></td>
                    <td>
                        <a href="delete.php?id=<?= htmlspecialchars($book['id']) ?>"><button>Supprimer</button></a>
                        <a href="edit.php?id=<?= htmlspecialchars($book['id']) ?>"><button>Éditer</button></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>