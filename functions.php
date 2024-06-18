<?php
require_once(__DIR__ . '/config/mysql.php');

function displayAuthor(string $authorEmail, array $users): string {
    foreach ($users as $user) {
        if ($authorEmail === $user['email']) {
            return $user['full_name'] . '(' . $user['age'] . ' ans)';
        }
    }
    return 'Auteur inconnu';
}

function isValidRecipe(array $recipe): bool {
    if (array_key_exists('is_enabled', $recipe)) {
        $isEnabled = $recipe['is_enabled'];
    } else {
        $isEnabled = false;
    }
    return $isEnabled;
}

function getRecipes(array $recipes): array {
    $valid_recipes = [];
    foreach ($recipes as $recipe) {
        if (isValidRecipe($recipe)) {
            $valid_recipes[] = $recipe;
        }
    }
    return $valid_recipes;
}

function redirectToUrl(string $url): never {
    header("Location: {$url}");
    exit();
}

function getTasks($priorityFilter = '', $statusFilter = '') {
    global $dbh;
    $sql = 'SELECT * FROM taches WHERE 1=1';
    if ($priorityFilter) {
        $sql .= ' AND priorite = :priorite';
    }
    if ($statusFilter) {
        $sql .= ' AND statut = :statut';
    }
    $stmt = $dbh->prepare($sql);
    if ($priorityFilter) {
        $stmt->bindParam(':priorite', $priorityFilter);
    }
    if ($statusFilter) {
        $stmt->bindParam(':statut', $statusFilter);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateTaskStatus($taskId, $newStatus) {
    global $dbh;
    $stmt = $dbh->prepare('UPDATE taches SET statut = :statut WHERE id = :id');
    $stmt->bindParam(':statut', $newStatus);
    $stmt->bindParam(':id', $taskId);
    return $stmt->execute();
}

function deleteTask($taskId) {
    global $dbh;
    $stmt = $dbh->prepare('DELETE FROM taches WHERE id = :id');
    $stmt->bindParam(':id', $taskId);
    return $stmt->execute();
}

function createTask($name, $description, $date_creation, $date_echeance, $statut, $priorite) {
    global $dbh;
    $stmt = $dbh->prepare('INSERT INTO taches (nom, description, date_creation, date_echeance, statut, priorite) VALUES (:nom, :description, :date_creation, :date_echeance, :statut, :priorite)');
    $stmt->bindParam(':nom', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date_creation', $date_creation);
    $stmt->bindParam(':date_echeance', $date_echeance);
    $stmt->bindParam(':statut', $statut);
    $stmt->bindParam(':priorite', $priorite);
    return $stmt->execute();
}
?>
