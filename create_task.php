<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['nom'];
    $description = $_POST['description'];
    $date_creation = date('Y-m-d');
    $date_echeance = $_POST['date_echeance'];
    $statut = 'en cours';
    $priorite = $_POST['priorite'];

    createTask($name, $description, $date_creation, $date_echeance, $statut, $priorite);
    header('Location: visual_taches.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une nouvelle tâche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <?php require_once(__DIR__ . '/header.php'); ?>
    <h1>Créer une nouvelle tâche</h1>

    <form method="POST" action="create_task.php">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="date_echeance" class="form-label">Date d'échéance</label>
            <input type="date" class="form-control" id="date_echeance" name="date_echeance" required>
        </div>
        <div class="mb-3">
            <label for="priorite" class="form-label">Priorité</label>
            <select id="priorite" name="priorite" class="form-select" required>
                <option value="basse">Basse</option>
                <option value="moyenne">Moyenne</option>
                <option value="haute">Haute</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Créer la tâche</button>
    </form>
</div>
<?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
