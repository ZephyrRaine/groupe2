<?php
// Assurez-vous que le fichier de configuration est inclus
require_once(__DIR__ . '/config/mysql.php');

try {
    // Récupération des utilisateurs
    $usersStatement = $GLOBALS['dbh']->prepare('SELECT * FROM utilisateurs');
    $usersStatement->execute();
    $users = $usersStatement->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des tâches activées (par exemple, celles dont le statut n'est pas "terminé")
    $tasksStatement = $GLOBALS['dbh']->prepare('SELECT * FROM taches WHERE statut != "terminé"');
    $tasksStatement->execute();
    $tasks = $tasksStatement->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
