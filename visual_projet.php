<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/functions.php');

// Initialize filters
$statusFilter = isset($_GET['statut']) ? $_GET['statut'] : '';

// Load projects with filters
$projects = getProjects($statusFilter);

// Check if a status update action is requested
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $projectId = $_POST['project_id'];

        if ($action === 'delete_project') {
            deleteProject($projectId);
        }

        // Redirect to avoid re-processing the form on page reload
        header('Location: visual_projet.php');
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
    <title>Visualisation des projets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <?php require_once(__DIR__ . '/header.php'); ?>
    <h1>Visualisation des projets</h1>

    <!-- Button to create a new project -->
    <a href="create_project.php" class="btn btn-primary mb-3">Créer un nouveau projet</a>

    <!-- Display projects -->
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Description</th>
                <th scope="col">Date de création</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $project) : ?>
                <tr>
                    <td><a href="visual_taches.php?projet_id=<?php echo $project['id']; ?>"><?php echo htmlspecialchars($project['nom'] ?? ''); ?></a></td>
                    <td><?php echo htmlspecialchars($project['description'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($project['date_debut'] ?? ''); ?></td>
                    <td>
                        <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="visual_projet.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete_project">
                            <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">Supprimer</button>
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
