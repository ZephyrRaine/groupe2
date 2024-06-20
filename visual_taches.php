<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/functions.php');

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['LOGGED_USER'])) {
    header('Location: login.php');
    exit;
}

// Récupérer le projet_id de l'URL
$projectId = isset($_GET['projet_id']) ? intval($_GET['projet_id']) : 0;

// Initialiser les filtres
$priorityFilter = isset($_GET['priorite']) ? $_GET['priorite'] : '';
$statusFilter = isset($_GET['statut']) ? $_GET['statut'] : '';
$id_projet = $_GET["projet_id"];

// Charger les tâches avec filtres
$tasks = getTasks($id_projet, $priorityFilter, $statusFilter);

// Vérifiez si une mise à jour du statut est demandée
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $taskId = $_POST['task_id'];

        if ($action === 'update_status') {
            $newStatus = $_POST['new_status'];
            updateTaskStatus($taskId, $newStatus);
        }

        // Redirigez pour éviter le re-traitement du formulaire en rechargeant la page
        header('Location: visual_taches.php?projet_id=' . $projectId);
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
    <a href="create_task.php?projet_id=<?php echo $projectId; ?>" class="btn btn-primary mb-3">Créer une nouvelle tâche</a>
    <a href="categories.php" class="btn btn-primary mb-3">Créer une nouvelle catégorie</a>
    <!-- Filtre de priorités et de statut -->
    <form method="GET" action="visual_taches.php" class="mb-3">
        <input type="hidden" name="projet_id" value="<?php echo $projectId; ?>">
        <div class="row">
            <div class="col-md-3">
                <label for="priorite" class="form-label">Priorité</label>
                <select id="priorite" name="priorite" class="form-select">
                    <option value="">Toutes</option>
                    <option value="basse" <?php if ($priorityFilter === 'basse') echo 'selected'; ?>>Basse</option>
                    <option value="moyenne" <?php if ($priorityFilter === 'moyenne') echo 'selected'; ?>>Moyenne</option>
                    <option value="haute" <?php if ($priorityFilter === 'haute') echo 'selected'; ?>>Haute</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="statut" class="form-label">Statut</label>
                <select id="statut" name="statut" class="form-select">
                    <option value="">Tous</option>
                    <option value="en cours" <?php if ($statusFilter === 'en cours') echo 'selected'; ?>>En cours</option>
                    <option value="terminé" <?php if ($statusFilter === 'terminé') echo 'selected'; ?>>Terminé</option>
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filtrer</button>
            </div>
        </div>
    </form>

    <!-- Affichage des tâches -->
    <table class="table">
        <thead>
            <tr>
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
            <?php foreach ($tasks as $tache) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($tache['nom'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($tache['description'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($tache['date_creation'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($tache['date_echeance'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($tache['statut'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($tache['priorite'] ?? ''); ?></td>
                    <td>
                        <form action="visual_taches.php?projet_id=<?php echo $projectId; ?>" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="task_id" value="<?php echo $tache['id']; ?>">
                            <select name="new_status" onchange="this.form.submit()">
                                <option value="en cours" <?php if ($tache['statut'] === 'en cours') echo 'selected'; ?>>En cours</option>
                                <option value="terminé" <?php if ($tache['statut'] === 'terminé') echo 'selected'; ?>>Terminé</option>
                            </select>
                        </form>
                        <a href="edit_task.php?id=<?php echo $tache['id']; ?>&projet_id=<?php echo $projectId; ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="visual_taches.php?projet_id=<?php echo $projectId; ?>" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete_task">
                            <input type="hidden" name="task_id" value="<?php echo $tache['id']; ?>">
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
