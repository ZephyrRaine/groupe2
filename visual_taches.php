<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');

// Fonction pour récupérer toutes les tâches
function getAllTasks() {
    global $dbh;
    $stmt = $dbh->query('SELECT * FROM taches');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour mettre à jour le statut d'une tâche
function updateTaskStatus($taskId, $newStatus) {
    global $dbh;
    $stmt = $dbh->prepare('UPDATE taches SET statut = :statut WHERE id = :id');
    $stmt->bindParam(':statut', $newStatus);
    $stmt->bindParam(':id', $taskId);
    return $stmt->execute();
}

// Charger toutes les tâches
$tasks = getAllTasks();

// Vérification si une action de mise à jour de statut est demandée
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $taskId = $_POST['task_id'];
    $newStatus = 'terminé'; // Mettre à jour le statut à "terminé"
    updateTaskStatus($taskId, $newStatus);
    // Redirection pour éviter de re-traiter le formulaire lors d'un rechargement de la page
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
    <title>Visualisation des tâches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <?php require_once(__DIR__ . '/header.php'); ?>
    <h1>Visualisation des tâches</h1>

    <!-- Affichage des tâches avec option de mise à jour de statut -->
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nom</th>
                <th scope="col">Description</th>
                <th scope="col">Date de création</th>
                <th scope="col">Date d'échéance</th>
                <th scope="col">Statut</th>
                <th scope="col">Priorité</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task) : ?>
                <tr>
                    <th scope="row"><?php echo $task['id']; ?></th>
                    <td><?php echo $task['nom']; ?></td>
                    <td><?php echo $task['description']; ?></td>
                    <td><?php echo $task['date_creation']; ?></td>
                    <td><?php echo $task['date_echeance']; ?></td>
                    <td><?php echo $task['statut']; ?></td>
                    <td><?php echo $task['priorite']; ?></td>
                    <td>
                        <!-- Formulaire pour mettre à jour le statut de la tâche -->
                        <form action="visual_taches.php" method="POST">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-success">Marquer comme terminé</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
