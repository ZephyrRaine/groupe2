<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/functions.php');

// Initialiser les filtres
$priorityFilter = isset($_GET['priorite']) ? $_GET['priorite'] : '';
$statusFilter = isset($_GET['statut']) ? $_GET['statut'] : '';

// Charger les tâches avec filtres
$tasks = getTasks($priorityFilter, $statusFilter);
print_r($tasks);

// Vérification si une action de mise à jour de statut est demandée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $taskId = $_POST['task_id'];

        if ($action === 'update_status') {
            $newStatus = 'terminé'; // Mettre à jour le statut à "terminé"
            updateTaskStatus($taskId, $newStatus);
        } elseif ($action === 'delete_task') {
            deleteTask($taskId);
        }

        // Redirection pour éviter de re-traiter le formulaire lors d'un rechargement de la page
        header('Location: visual_taches.php');
        exit;
    }
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

    <!-- Bouton pour créer une nouvelle tâche -->
    <a href="create_task.php" class="btn btn-primary mb-3">Créer une nouvelle tâche</a>

    <!-- Bouton pour créer une nouvelle catégorie -->
    <a href="categories.php" class="btn btn-primary mb-3">Créer une nouvelle catégorie</a>

    <!-- Formulaire de filtrage -->
    <form method="GET" action="visual_taches.php" class="row g-3 mb-3">
        <div class="col-md-4">
            <label for="priorite" class="form-label">Priorité</label>
            <select id="priorite" name="priorite" class="form-select">
                <option value="">Toutes</option>
                <option value="basse" <?php echo $priorityFilter == 'basse' ? 'selected' : ''; ?>>Basse</option>
                <option value="moyenne" <?php echo $priorityFilter == 'moyenne' ? 'selected' : ''; ?>>Moyenne</option>
                <option value="haute" <?php echo $priorityFilter == 'haute' ? 'selected' : ''; ?>>Haute</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="statut" class="form-label">Statut</label>
            <select id="statut" name="statut" class="form-select">
                <option value="">Tous</option>
                <option value="en cours" <?php echo $statusFilter == 'en cours' ? 'selected' : ''; ?>>En cours</option>
                <option value="terminé" <?php echo $statusFilter == 'terminé' ? 'selected' : ''; ?>>Terminé</option>
            </select>
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </form>

    <!-- Affichage des tâches avec option de mise à jour de statut -->
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Description</th>
                <th scope="col">Date de création</th>
                <th scope="col">Date d'échéance</th>
                <th scope="col">Statut</th>
                <th scope="col">Priorité</th>
                <th scope="col">Catégorie</th> <!-- Nouvelle colonne pour la catégorie -->
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task) : ?>
                <tr>
                    <td><?php echo $task['nom']; ?></td>
                    <td><?php echo $task['description']; ?></td>
                    <td><?php echo $task['date_creation']; ?></td>
                    <td><?php echo $task['date_echeance']; ?></td>
                    <td><?php echo $task['statut']; ?></td>
                    <td><?php echo $task['priorite']; ?></td>
                    <td><?php echo $task['category_name']; ?></td> <!-- Affichage de la catégorie -->
                    <td>
                        <!-- Formulaire pour mettre à jour le statut de la tâche -->
                        <?php if ($task['statut'] != 'terminé') : ?>
                        <form action="visual_taches.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-success">Marquer comme terminé</button>
                        </form>
                        <?php endif; ?>
                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="visual_taches.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete_task">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">Supprimer</button>
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
