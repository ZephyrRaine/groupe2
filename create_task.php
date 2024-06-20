<?php
include 'config/mysql.php';

// Retrieving projet_id from the URL
$projectId = isset($_GET['projet_id']) ? intval($_GET['projet_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $task_creation_date = $_POST['task_creation_date'];
    $task_due_date = $_POST['task_due_date'];
    $task_status = $_POST['task_status'];
    $task_priority = $_POST['task_priority'];
    $projectId = $_POST['project_id'];

    // Including id_projet in the SQL query and binding the parameter
    $sql = "INSERT INTO taches (nom, description, date_creation, date_echeance, statut, priorite, id_projet) 
            VALUES (:nom, :description, :date_creation, :date_echeance, :statut, :priorite, :id_projet)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nom', $task_name);
    $stmt->bindParam(':description', $task_description);
    $stmt->bindParam(':date_creation', $task_creation_date);
    $stmt->bindParam(':date_echeance', $task_due_date);
    $stmt->bindParam(':statut', $task_status);
    $stmt->bindParam(':priorite', $task_priority);
    $stmt->bindParam(':id_projet', $projectId);

    if ($stmt->execute()) {
        header('Location: visual_taches.php?projet_id=' . $projectId);
        exit;
    } else {
        echo "Error: Could not create task";
    }
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
        <h2>Créer une nouvelle tâche</h2>
        <form method="POST" class="mb-3">
            <div class="mb-3">
                <label for="task_name" class="form-label">Nom de la tâche</label>
                <input type="text" class="form-control" id="task_name" name="task_name" required>
            </div>
            <div class="mb-3">
                <label for="task_description" class="form-label">Description de la tâche</label>
                <textarea class="form-control" id="task_description" name="task_description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="task_creation_date" class="form-label">Date de création</label>
                <input type="date" class="form-control" id="task_creation_date" name="task_creation_date" required>
            </div>
            <div class="mb-3">
                <label for="task_due_date" class="form-label">Date d'échéance</label>
                <input type="date" class="form-control" id="task_due_date" name="task_due_date" required>
            </div>
            <div class="mb-3">
                <label for="task_status" class="form-label">Statut</label>
                <select id="task_status" name="task_status" class="form-select" required>
                    <option value="en cours">En cours</option>
                    <option value="terminé">Terminé</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="task_priority" class="form-label">Priorité</label>
                <select id="task_priority" name="task_priority" class="form-select" required>
                    <option value="basse">Basse</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="haute">Haute</option>
                </select>
            </div>
            <!-- Hidden input field for project ID -->
            <input type="hidden" name="project_id" value="<?php echo $projectId; ?>">
            <button type="submit" class="btn btn-primary">Créer la tâche</button>
        </form>
    </div>
    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
