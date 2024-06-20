<?php
include 'config/mysql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = $_POST['project_name'];
    $project_description = $_POST['project_description'];
    $project_start_date = $_POST['project_start_date'];
    $project_end_date = $_POST['project_end_date'];

    $sql = "INSERT INTO projets (nom, description, date_debut, date_fin) VALUES (:name, :description, :date_debut, :date_fin)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':name', $project_name);
    $stmt->bindParam(':description', $project_description);
    $stmt->bindParam(':date_debut', $project_start_date);
    $stmt->bindParam(':date_fin', $project_end_date);

    if ($stmt->execute()) {
        header('Location: visual_projet.php');
        exit;
    } else {
        echo "Error: Could not create project";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un nouveau projet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php require_once(__DIR__. '/header.php'); ?>
        <h2>Créer un nouveau projet</h2>
        <form method="POST" class="mb-3">
            <div class="mb-3">
                <label for="project_name" class="form-label">Nom du projet</label>
                <input type="text" class="form-control" id="project_name" name="project_name" required>
            </div>
            <div class="mb-3">
                <label for="project_description" class="form-label">Description du projet</label>
                <textarea class="form-control" id="project_description" name="project_description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="project_start_date" class="form-label">Date de début</label>
                <input type="date" class="form-control" id="project_start_date" name="project_start_date" required>
            </div>
            <div class="mb-3">
                <label for="project_end_date" class="form-label">Date de fin</label>
                <input type="date" class="form-control" id="project_end_date" name="project_end_date" required>
            </div>
            <button type="submit" class="btn btn-primary">Créer le projet</button>
        </form>
    </div>
    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
