<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Hasher le mot de passe
    $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    try {
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
        echo 'Erreur : ' . $e->getMessage();
    }
} else {
    // Si la méthode n'est pas POST, rediriger vers l'accueil
    header('Location: index.php');
    exit;
}
?>
