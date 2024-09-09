<?php
// Configuration de la base de données
require_once __DIR__ . "/../../include/Connect.php";
// Sécurité : Vérifier si l'utilisateur est authentifié avant d'exécuter le processus
include $_SERVER["DOCUMENT_ROOT"] . "/Admin/include/Protect.php";

// Séparation des préoccupations : Le fichier process.php ne contient que la logique de traitement des données des clients 
// Le fichier process.php est exécuté uniquement lorsque le formulaire est soumis, c'est-à-dire lors d'une requête POST.
// Le fichier process.php ne contient pas de code HTML, il ne génère pas de sortie HTML.
// Le fichier process.php ne contient pas de redirections, il ne génère pas de sortie HTML.
// Traitement des données soumises : Cette étape se fait dans process.php, qui gère les informations soumises via POST, les valide et les insère ou met à jour dans la base de données.

// process.php Traite les données soumises du formulaire.
// Insère ou met à jour le client dans la base de données.

// Initialisation des variables par défaut
$customer =[
    'Customer_id' => '',
    'Customer_firstName' => '',
    'Customer_lastName' => '',
    'Customer_email' => '',
    'Customer_password' => '',
    'Customer_adress' => '',
    'Customer_zipCode' => '',
    'Customer_phoneNum' => '',
    'Admin_booleen' => '',
    'consent_date' => null,
    'deletion_date' => null,
    'Authorisation_id' => ''
];


// Récupérer les données du client s'il y a un ID spécifié
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $customerId = $_GET['id'];
    $sql = "SELECT * FROM customer WHERE Customer_id = :customer_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le client existe
    if (!$customer) {
        echo 'Client non trouvé.';
        exit; 
    }
}

// Vérification si le formulaire a été soumis pour création ou mise à jour
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validation des données d'entrée (exemple : validation de l'email  afin qu'elles répondent à des exigences strictes)
    $email = filter_var($_POST['Customer_email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        // Gérer l'erreur, par exemple afficher un message à l'utilisateur et arrêter l'exécution du script
        echo 'Email invalide.';
        exit;
    }
    // Récupération des données du formulaire
    $customerId = $_POST['Customer_id'];
    $firstName = $_POST['Customer_firstName'];
    $lastName = $_POST['Customer_lastName'];
    $password = password_hash($_POST['Customer_password'], PASSWORD_DEFAULT); // Ne pas stocker les mots de passe en clair dans la base de données
    $adress = $_POST['Customer_adress'];
    $zipCode = $_POST['Customer_zipCode'];
    $phoneNum = $_POST['Customer_phoneNum'];
    $authId = $_POST['Authorisation_id'];
    // $admin = isset($_POST['Admin_booleen']) ? 1 : 0;
    // Définir le booleen Admin en fonction de l'Authorisation_id sélectionnée
    // Exemple : Si l'Authorisation_id = 1, alors Admin_booleen = 1, sinon = 0
    //  la ligne $admin = ($authId == 1) ? 1 : 0; est placée aprés la récupération de $authId via $_POST, pour qu'elle fonctionne comme prévu.
    $admin = ($authId == 1) ? 1 : 0;

    // RGPD : Définir la date de création et la date de suppression après 2 ans
    $creationDate = date('Y-m-d H:i:s');
    $consentDate = isset($_POST['consent_date']) ? $_POST['consent_date'] : null;


    // Avec Cette approche, on a un contrôle précis sur la mise à jour des dates tout en évitant les erreurs causées par des modifications involontaires.
    // Si la consent_date a été modifiée, mettre à jour la deletion_date. Sinon, conservez l'ancienne.
    if ($consentDate && $consentDate !== $existingConsentDate) {
        // Recalculer la deletion_date en fonction de la consent_date
        $deletionDate = date('Y-m-d H:i:s', strtotime($consentDate . ' +2 years'));
    } else {
        // Si la consent_date est inchangée, on garde la deletion_date existante
        $deletionDate = $existingDeletionDate ?: date('Y-m-d H:i:s', strtotime('+2 years')); // Valeur par défaut si pas de deletion_date existante
    }


        // Préparation de la Requête SQL préparée en fonction de la présence ou non d'un ID client
        if (empty($customerId)) {
        // Création d'un nouveau client
        $sql = "INSERT INTO customer 
                (Customer_firstName, 
                Customer_lastName, 
                Customer_email, 
                Customer_password, 
                Customer_adress, 
                Customer_zipCode, 
                Customer_phoneNum, 
                Admin_booleen, 
                consent_date,
                deletion_date,
                Authorisation_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $firstName, 
            $lastName, 
            $email, 
            $password, 
            $adress, 
            $zipCode, 
            $phoneNum, 
            $admin, 
            $consentDate,
            $deletionDate,
            $authId
        ]);

        // Récupérer l'ID du client nouvellement inséré
        $customerId = $db->lastInsertId();

        // Insertion dans la table 'consents'
        $sqlConsent = "INSERT INTO consents (customer_id, consent_type, consent_given, consent_date) 
                    VALUES (?, ?, ?, ?)";
        $stmtConsent = $db->prepare($sqlConsent);
        $stmtConsent->execute([$customerId, 'account_creation', 1, $consentDate]);

        echo "Client et consentement créés avec succès.<br>";
    }

    } else {
        // Mise à jour d'un client existant
            // requête préparée avec PDO pour éviter les injections SQL
            // Pourquoi c'est important : Les requêtes préparées permettent de lier les données en utilisant des "placeholders" (?) et empêchent l'injection de code malveillant dans votre requête SQL.
       if (!empty($customerId)) {
    // Mise à jour du client existant
    $sql = "UPDATE customer SET 
            Customer_firstName = ?, 
            Customer_lastName = ?, 
            Customer_email = ?, 
            Customer_password = ?, 
            Customer_adress = ?, 
            Customer_zipCode = ?, 
            Customer_phoneNum = ?, 
            Admin_booleen = ?, 
            consent_date = ?,
            deletion_date =?,
            Authorisation_id = ?,
            WHERE Customer_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        $firstName, 
        $lastName, 
        $email, 
        $password, 
        $adress, 
        $zipCode, 
        $phoneNum, 
        $admin, 
        $consentDate, 
        $deletionDate, 
        $authId, 
        $customerId
    ]);

    // Vérifier s'il existe déjà un consentement pour ce client
    $sqlConsentCheck = "SELECT * FROM consents WHERE customer_id = ?";
    $stmtConsentCheck = $db->prepare($sqlConsentCheck);
    $stmtConsentCheck->execute([$customerId]);
    $consentRecord = $stmtConsentCheck->fetch();

    if ($consentRecord) {
        // Mise à jour du consentement existant
        $sqlUpdateConsent = "UPDATE consents SET consent_type = ?, consent_given = ?, consent_date = ? WHERE customer_id = ?";
        $stmtUpdateConsent = $db->prepare($sqlUpdateConsent);
        $stmtUpdateConsent->execute(['account_update', 1, $consentDate, $customerId]);
    } else {
        // Insertion d'un nouveau consentement si aucun n'existe
        $sqlInsertConsent = "INSERT INTO consents (customer_id, consent_type, consent_given, consent_date) 
                             VALUES (?, ?, ?, ?)";
        $stmtInsertConsent = $db->prepare($sqlInsertConsent);
        $stmtInsertConsent->execute([$customerId, 'account_update', 1, $consentDate]);
    }

    echo "Client et consentement mis à jour avec succès.<br>";
}

    // Redirection après succès
    header("Location:/Admin/index.php");
    exit();
}
?>

<!-- // Récupérer les informations du client pour modification
// if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['customerId'])) {
//     $sql = "SELECT * FROM customer WHERE Customer_id = ?";
//     $stmt = $db->prepare($sql);
//     $stmt->execute([$_GET['customerId']]);
//     $customer = $stmt->fetch(PDO::FETCH_ASSOC);
// }

// // Suppression d'un client
// if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['customerId'])) {
//     $sql = "DELETE FROM customer WHERE Customer_id = ?";
//     $stmt = $db->prepare($sql);
//     $stmt->execute([$_POST['customerId']]);
//     echo "Client supprimé avec succès.<br>"; -->

