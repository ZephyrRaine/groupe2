<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/functions.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: visual_taches.php');
    exit;
}

$taskId = $_GET['id'];
$task = getTaskById($taskId);

if (!$task) {
    header('Location: visual_taches.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $date_echeance = $_POST['date_echeance'];
    $statut = $_POST['statut'];
    $priorite = $_POST['priorite'];

    // Mettre à jour la tâche
    updateTask($taskId, $nom, $description, $date_echeance, $statut, $priorite);

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
    <title>Modifier la tâche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <?php require_once(__DIR__ . '/header.php'); ?>
    <h1>Modifier la tâche</h1>

    <form method="POST" action="edit_task.php?id=<?php echo $taskId; ?>">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($task['nom']); ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($task['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="date_creation" class="form-label">Date de création</label>
            <input type="text" class="form-control" id="date_creation" name="date_creation" value="<?php echo htmlspecialchars($task['date_creation']); ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="date_echeance" class="form-label">Date d'échéance</label>
            <input type="date" class="form-control" id="date_echeance" name="date_echeance" value="<?php echo htmlspecialchars($task['date_echeance']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select id="statut" name="statut" class="form-select" required>
                <option value="en cours" <?php if ($task['statut'] === 'en cours') echo 'selected'; ?>>En cours</option>
                <option value="terminé" <?php if ($task['statut'] === 'terminé') echo 'selected'; ?>>Terminé</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="priorite" class="form-label">Priorité</label>
            <select id="priorite" name="priorite" class="form-select" required>
                <option value="basse" <?php if ($task['priorite'] === 'basse') echo 'selected'; ?>>Basse</option>
                <option value="moyenne" <?php if ($task['priorite'] === 'moyenne') echo 'selected'; ?>>Moyenne</option>
                <option value="haute" <?php if ($task['priorite'] === 'haute') echo 'selected'; ?>>Haute</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
<?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
