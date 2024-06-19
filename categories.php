<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un nouvel utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container">
        <?php require_once(__DIR__ . '/header.php'); ?>
        <h1>Ajouter une catégorie</h1>
        <form action="form_categories.php" method="post">
            <label for="nom">Nom de la catégorie :</label>
            <input type="text" id="nom" name="nom" required>
            <input type="submit" value="Ajouter">
        </form>
    </div>
    <!-- Inclusion du bas de page du site -->
    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
