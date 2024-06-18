<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');

// Vérification si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['mot_de_passe'];

    try {
        // Vérification des informations de connexion dans la table utilisateurs
        $stmt = $GLOBALS['dbh']->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Connexion réussie : enregistrer l'utilisateur dans la session
            $_SESSION['LOGGED_USER'] = $user;

            // Redirection vers une page de succès ou l'accueil
            header('Location: index.php');
            exit;
        } else {
            // Échec de la connexion : message d'erreur
            $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Email ou mot de passe incorrect.';
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
} else {
    // Si la méthode n'est pas POST, rediriger vers l'accueil
    header('Location: index.php');
    exit;
}
?>
