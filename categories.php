<?php
session_start();

// Inclusion du fichier de configuration MySQL
require_once("config/mysql.php");

// Vérifier si le formulaire d'ajout a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nom'])) {
    $nomCategorie = trim($_POST['nom']);

    // Ajouter la catégorie à la session
    if (!isset($_SESSION['categories'])) {
        $_SESSION['categories'] = [];
    }

    // Ajouter la nouvelle catégorie à la liste
    $_SESSION['categories'][] = $nomCategorie;

    // Rediriger pour éviter la resoumission du formulaire
    header("Location: form_categories.php");
    exit();
}

// Récupérer les catégories depuis la base de données
$query_select = "SELECT * FROM categories";
$results = $dbh->query($query_select);
$categories = $results->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter et Supprimer des catégories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container">
        <?php require_once(__DIR__ . '/header.php'); ?>
        <h1>Ajouter une catégorie</h1>
        <form action="form_categories.php" method="post">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la catégorie :</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>

        <!-- Afficher les catégories depuis la base de données -->
        <?php if (!empty($categories)): ?>
            <h2>Liste des Catégories (Base de données)</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $categorie): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($categorie['id']); ?></td>
                            <td><?php echo htmlspecialchars($categorie['nom']); ?></td>
                            <td>
                                <form action="form_categories.php" method="post">
                                    <input type="hidden" name="delete_id" value="<?php echo $categorie['id']; ?>">
                                    <button type="submit" class="btn btn-danger" >Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Inclusion du bas de page du site -->
    <?php require_once(__DIR__ . '/footer.php'); ?>

</body>
</html>
