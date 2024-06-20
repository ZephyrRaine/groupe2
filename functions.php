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

function getTasks($projet_id = -1, $priorityFilter = '', $statusFilter = '',) {
    global $dbh;

    $sql = 'SELECT * FROM taches WHERE 1 = 1';
    if($projet_id != -1) {
        $sql .= ' AND id_projet = :id_projet';
    }
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
    if($projet_id != -1) {
        $stmt->bindParam(':id_projet', $projet_id);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTaskById($taskId) {
    global $dbh;
    $stmt = $dbh->prepare('SELECT * FROM taches WHERE id = :id');
    $stmt->bindParam(':id', $taskId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne les données de la tâche ou NULL si non trouvée
}

function updateTask($taskId, $nom, $description, $date_echeance, $statut, $priorite) {
    global $dbh;
    $stmt = $dbh->prepare('UPDATE taches SET nom = :nom, description = :description, date_echeance = :date_echeance, statut = :statut, priorite = :priorite WHERE id = :id');
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date_echeance', $date_echeance);
    $stmt->bindParam(':statut', $statut);
    $stmt->bindParam(':priorite', $priorite);
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

function updateTaskStatus($id, $newStatus) {
    global $dbh;
    $stmt = $dbh->prepare('UPDATE taches SET statut = :statut WHERE id = :id');
    $stmt->bindParam(':statut', $newStatus);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

function getProjects($statusFilter = '') {
    global $dbh;
    $sql = "SELECT id, nom, description, date_debut FROM projets";
    if ($statusFilter) {
        $sql .= " WHERE statut = :statut";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':statut', $statusFilter);
        $stmt->execute();
    } else {
        $stmt = $dbh->query($sql);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProjectById($id) {
    global $dbh;
    $sql = "SELECT id, nom, description, date_debut, date_fin FROM projets WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateProject($id, $nom, $description, $date_debut, $date_fin) {
    global $dbh;
    $sql = "UPDATE projets SET nom = :nom, description = :description, date_debut = :date_debut, date_fin = :date_fin WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date_debut', $date_debut);
    $stmt->bindParam(':date_fin', $date_fin);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

function deleteProject($id) {
    global $dbh;
    $sql = "DELETE FROM projets WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

?>