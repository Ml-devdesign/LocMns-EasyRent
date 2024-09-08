<?php
include $_SERVER["DOCUMENT_ROOT"]."/includes/protect.php";
require_once __DIR__ . '/../../config/database.php';

// Fonction pour journaliser les erreurs dans un fichier
function logError($message) {
    $file = __DIR__ . '/../../logs/errors.log';
    $current = file_get_contents($file);
    $current .= date('Y-m-d H:i:s') . " - " . $message . "\n";
    file_put_contents($file, $current);
}

// Vérifie si l'ID du produit est passé et est valide
if (isset($_POST['id']) && $_POST['id'] > 0) {
    $deviceId = $_POST['id'];

    // Vérifie si le produit existe dans la base de données
    $sqlCheck = "SELECT * FROM devis WHERE Device_id = :device_id";
    $stmtCheck = $db->prepare($sqlCheck);
    $stmtCheck->bindParam(':device_id', $deviceId, PDO::PARAM_INT);
    $stmtCheck->execute();
    
    if ($stmtCheck->rowCount() > 0) {
        // Si le produit existe, on continue avec la suppression
        if (isset($_POST['checkbox']) && $_POST['checkbox'] == 1) {
            if (isset($_POST['checkboxDeleteAll']) && $_POST['checkboxDeleteAll'] == 1) {

                // Préparation de la requête SQL pour supprimer le produit
                $sql = "DELETE FROM devis WHERE Device_id = :device_id";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':device_id', $deviceId, PDO::PARAM_INT);

                // Exécute la requête SQL pour supprimer le produit
                if ($stmt->execute()) {
                    // Redirection vers le tableau de bord après la suppression
                    header('Location:dashboard_ad.php');
                    exit();
                } else {
                    // En cas d'échec de la suppression, journaliser l'erreur et rediriger l'utilisateur
                    logError("Échec de la suppression du produit ID : $deviceId");
                    header('Location:error_page.php?error=deletion_failed');
                    exit();
                }
            } else {
                echo "La suppression de tous les produits n'est pas activée.";
            }
        } else {
            echo "Case à cocher non sélectionnée.";
        }
    } else {
        // Si l'ID du produit n'existe pas dans la base de données
        logError("Produit non trouvé avec l'ID : $deviceId");
        echo "Produit non trouvé.";
    }
} else {
    // Gestion du cas où l'ID du produit n'est pas défini ou est invalide
    logError("ID du produit invalide fourni : " . (isset($_POST['id']) ? $_POST['id'] : 'aucun ID fourni'));
    echo "ID du produit invalide.";
}
