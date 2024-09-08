<?php
// Configuration de la base de données
require_once __DIR__ . "/../../include/Connect.php";
require_once __DIR__ . "/../../include/Protect.php";

// Initialisation des variables par défaut
$reservation= [
    'Reservation_id' => '',
    'Reservation_endDate' => '',
    'Reservation_startDate' => '',
    'Reservation_type' => '',
    'Reservation_received' => '',
    'Reservation_accepted' => '',
    'Reservation_created' => '',
    'Reservation_valided' => '',
    'Reservation_refused' => ''
];

// Vérifie si l'ID est passé en paramètre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $reservationId = (int) $_GET['id'];
    // Récupérer les informations du client
    $sql = "SELECT * FROM reservation WHERE Reservation_id = :reservation_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':reservation_id', $reservationId, PDO::PARAM_INT);
    $stmt->execute();
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reservation) {
     // Traitement des valeurs null : Utilisation de array_map pour remplacer les valeurs null par des chaînes vides dans $customer.
    // Pré-remplissage des champs du formulaire : Correction de la pré-remplissage pour éviter le problème avec null.
        $reservation = array_map(fn($value) => $value === null ? '' : $value, $reservation);
    }else{
       echo 'Reservation non trouvé.';
        exit; 
    }
} else {
    echo 'ID Reservation non spécifié ou invalide.';
}

// Vérifier si le formulaire a été soumis pour création ou mise à jour
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ReservationId = $_POST['Reservation_id'];
    $Reservation_endDate = $_POST['Reservation_endDate'];
    $Reservation_startDate = $_POST['Reservation_startDate'];
    $Reservation_type = $_POST['Reservation_type'];
    $Reservation_received = $_POST['Reservation_received'];
    $Reservation_accepted = $_POST['Reservation_accepted'];
    $Reservation_created = $_POST['Reservation_created'];
    $Reservation_valided = $_POST['Reservation_valided'];
    $Reservation_refused = $_POST['Reservation_refused'];

    if (empty($reservationId)) {
        // Création d'une nouvelle reservation
        $sql = "INSERT INTO reservation (Reservation_endDate, Reservation_startDate, Reservation_type, Reservation_received, Reservation_accepted, Reservation_created, Reservation_valided, Reservation_refused, ReservationId)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$Reservation_endDate, $Reservation_startDate, $Reservation_type, $Reservation_received, $Reservation_accepted, $Reservation_created, $Reservation_valided, $Reservation_refused , $ReservationId]);
        echo "reservation créé avec succès.<br>";
    } else {
        // Mise à jour d'un client existant
        $sql = "UPDATE reservation SET 
                Reservation_endDate = ?, 
                Reservation_startDate = ?, 
                Reservation_startDate = ?, 
                Reservation_type = ?, 
                Reservation_received = ?, 
                Reservation_created = ?, 
                Reservation_valided = ?, 
                Reservation_refused = ?, 
                WHERE Device_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$Reservation_endDate, $Reservation_startDate, $Reservation_type, $Reservation_received, $Reservation_accepted, $Reservation_created, $Reservation_valided, $Reservation_refused]);
        echo "Reservation mis à jour avec succès.<br>";
    }
}
?>
<!-- 
// Récupérer les informations du client pour modification
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
//     echo "Client supprimé avec succès.<br>";
// } -->


