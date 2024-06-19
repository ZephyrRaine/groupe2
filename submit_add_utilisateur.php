<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site To-Do List - Page d'accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <!-- Inclusion de l'en-tête du site -->
        <?php require_once(__DIR__ . '/header.php'); ?>
    </div>
</body>
</html>

<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
function display_message($message, $type = 'error') {
    $color = $type === 'error' ? 'red' : 'green';
    echo "<div style='background-color: $color; color: white; padding: 10px; border-radius: 5px; text-align: center; margin: 10px 0;'>$message</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        display_message('Format d\'email invalide');
        exit;
    }

    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
        display_message('Tous les champs sont obligatoires');
        exit;
    }

    try {
        // Vérifier si l'email existe déjà
        $stmt = $GLOBALS['dbh']->prepare('SELECT COUNT(*) FROM utilisateurs WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            display_message('Cette adresse email est déjà utilisée');
            exit;
        }

        // Hasher le mot de passe
        $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        // Préparer et exécuter la requête d'insertion
        $stmt = $GLOBALS['dbh']->prepare('INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)');
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $hashed_password);
        $stmt->execute();

        // Redirection vers une page de confirmation ou l'accueil
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        display_message('Erreur : ' . $e->getMessage());
    }
} else {
    // Si la méthode n'est pas POST, rediriger vers l'accueil
    header('Location: index.php');
    exit;
}
?>