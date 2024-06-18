<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/functions.php');

// Vérifier si l'ID de la tâche est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirectToUrl('visual_taches.php');
}

$taskId = intval($_GET['id']);
$task = getTaskById($taskId);

if (!$task) {
    redirectToUrl('visual_taches.php');
}

// Générer un token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $priority = filter_input(INPUT_POST, 'priority', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $dueDate = filter_input(INPUT_POST, 'due_date', FILTER_SANITIZE_STRING);

    if ($name && $description && $priority && $status && $dueDate) {
        updateTask($taskId, $name, $description, $priority, $status, $dueDate);
        redirectToUrl('visual_taches.php');
    } else {
        $error = 'Tous les champs sont obligatoires.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une tâche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <?php require_once(__DIR__ . '/header.php'); ?>
    <h1>Modifier une tâche</h1>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit_task.php?id=<?php echo $taskId; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Nom de la tâche</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($task['nom']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="priority" class="form-label">Priorité</label>
            <select id="priority" name="priority" class="form-select" required>
                <option value="basse" <?php echo $task['priorite'] == 'basse' ? 'selected' : ''; ?>>Basse</option>
                <option value="moyenne" <?php echo $task['priorite'] == 'moyenne' ? 'selected' : ''; ?>>Moyenne</option>
                <option value="haute" <?php echo $task['priorite'] == 'haute' ? 'selected' : ''; ?>>Haute</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <select id="status" name="status" class="form-select" required>
                <option value="en cours" <?php echo $task['statut'] == 'en cours' ? 'selected' : ''; ?>>En cours</option>
                <option value="terminé" <?php echo $task['statut'] == 'terminé' ? 'selected' : ''; ?>>Terminé</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Date d'échéance</label>
            <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo htmlspecialchars($task['date_echeance']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="visual_taches.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
<?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
