<?php
include $_SERVER["DOCUMENT_ROOT"] . "/Include/Protect.php";
require_once __DIR__ . '/../../include/Connect.php';

// Afficher les données POST pour le débogage
var_dump($_POST);
exit();
// Sortie
// Résolution du problème
// Vérifiez que les données sont bien envoyées via POST :
// var_dump($_POST);
// C:\wamp64\www\SoutenanceEasyRent\Admin\Dashbord\Customer\delete.php:6:
// array (size=2)
//   'id' => string '23' (length=2)
//   'checkboxDeleteAll' => string '1' (length=1)
// mais il manque la clé 'checkbox' qui devrait être présente pour confirmer l'anonymisation. Cela signifie que la case à cocher pour l'anonymisation n'a pas été cochée, et c'est pourquoi la logique du script delete.php ne se poursuit pas.

// Vérification de l'existence de l'ID client
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $customerId = $_POST['id'];
    
    // Requête pour récupérer les données du client
    if (isset($_POST['checkbox']) && $_POST['checkbox'] == 1) {

        if (isset($_POST['checkboxDeleteAll']) && $_POST['checkboxDeleteAll'] == 1) {
            // Pseudonymisation (GDPR) - Anonymisation des données sensibles
            $sql = "UPDATE customer 
                    SET Customer_firstName = 'Anonyme', 
                        Customer_lastName = 'Anonyme', 
                        Customer_email = 'anonyme@example.com', 
                        Customer_phoneNum = NULL, 
                        Customer_adress = NULL 
                    WHERE Customer_id = :customer_id";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);

            // Exécution de la requête et redirection
            if ($stmt->execute()) {
                echo "Client anonymisé et supprimé avec succès.";
                header('Location:/Admin//index.php?success=customer_deleted');
                exit();
            } else {
                echo "Erreur lors de l'anonymisation et suppression des données du client.";
            }
        }
    }
} else {
    echo "ID client invalide.";
}
