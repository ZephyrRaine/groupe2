
<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');

    try {
        // Créer une nouvelle connexion PDO
        // Définir le mode d'erreur de PDO sur exception
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
    // Traitement de la suppression d'une catégorie
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        
        // Supprimer la catégorie de la base de données
        $query_delete = "DELETE FROM categories WHERE id = :id";
        $stmt = $dbh->prepare($query_delete);
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirection pour éviter la resoumission du formulaire
        header("Location: categories.php");
        exit();
    }
    // Vérifier si le formulaire a été soumis
    else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer le nom de la catégorie depuis le formulaire
        $nom = $_POST["nom"];

        // Préparer la requête SQL pour insérer la nouvelle catégorie
        $stmt = $dbh->prepare("INSERT INTO categories (nom) VALUES (:nom)");
        // Lier le paramètre
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Nouvelle catégorie ajoutée avec succès";
        } else {
            echo "Erreur lors de l'ajout de la catégorie";
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}

// Fermer la connexion (optionnel, PDO le fait automatiquement en fin de script)
$pdo = null;
?>
