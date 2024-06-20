<?php

session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/functions.php');
// Retrieving projet_id from the URL
$projectId = isset($_GET['projet_id']) ? intval($_GET['projet_id']) : 0;

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['LOGGED_USER'])) {
    //header('Location: login.php');
    //exit;
    echo "no user";
}

// Récupérer le projet_id de l'URL
$projectId = isset($_GET['projet_id']) ? intval($_GET['projet_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['nom'];
    $description = $_POST['description'];
    $date_creation = date('Y-m-d');
    $date_echeance = $_POST['date_echeance'];
    $statut = 'en cours';
    $priorite = $_POST['priorite'];
    $id_utilisateur = $_SESSION['LOGGED_USER']['id'];
    $id_projet = $projectId; // Utiliser le projectId récupéré de l'URL

    createTask($name, $description, $date_creation, $date_echeance, $statut, $priorite, $id_utilisateur, $id_projet);
    header('Location: visual_taches.php?projet_id=' . $id_projet);
    exit;
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
    <h1>Créer une nouvelle tâche</h1>

    <form method="POST" action="create_task.php?projet_id=<?php echo $projectId; ?>">
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
