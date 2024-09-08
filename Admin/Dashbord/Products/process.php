<?php
// Configuration de la base de données
require_once __DIR__ . "/Admin/include/Connect.php";
require_once __DIR__ . "/Admin/include/Protect.php";

// Initialisation des variables par défaut
$product = [
    'Device_id' => '',
    'Device_status' => '',
    'Device_exitDate' => '',
    'Device_entryDate' => '',
    'Device_priceRent' => '',
    'Device_description' => '',
    'Device_name' => '',
    'Device_model_id' => '',
    'Device_category_id' => '',
    'Device_category_name' => '',
    'Device_modelName' => ''
];

// Vérifie si l'ID est passé en paramètre 
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = $_GET['id'];
    // Récupérer les informations du produit
    $sql = "SELECT * FROM device WHERE Device_id = :device_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':device_id', $productId, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Remplacer les valeurs null par des chaînes vides
        $product = array_map(fn($value) => $value === null ? '' : $value, $product);
    } else {
        echo 'Produit non trouvé.';
        exit; 
    }
} else {
    echo 'ID Produit non spécifié ou invalide.';
    exit;
}

// Vérifier si le formulaire a été soumis pour création ou mise à jour
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = isset($_POST['Device_id']) ? $_POST['Device_id'] : '';    
    $DeviceStatus = $_POST['Device_status'];
    $DeviceExitDate = $_POST['Device_exitDate'];
    $DeviceEntryDate = $_POST['Device_entryDate'];
    $DevicePriceRent = $_POST['Device_priceRent'];
    $DeviceDescription = $_POST['Device_description'];
    $DeviceName = $_POST['Device_name'];
    $Device_model_id = $_POST['Device_model_id'];
    $Device_category_id = $_POST['Device_category_id'];
    $Device_category_name = $_POST['Device_category_name'];
    $Device_modelName = $_POST['Device_modelName'];

    if (empty($productId)) {
        // Création d'un nouveau produit
        $sql = "INSERT INTO device (
                            Device_status, 
                            Device_exitDate, 
                            Device_entryDate, 
                            Device_priceRent, 
                            Device_description, 
                            Device_name, 
                            Device_model_id, 
                            Device_category_id, 
                            Device_category_name, 
                            Device_modelName
                            )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$DeviceStatus, 
                        $DeviceExitDate, 
                        $DeviceEntryDate, 
                        $DevicePriceRent, 
                        $DeviceDescription, 
                        $DeviceName, 
                        $Device_model_id, 
                        $Device_category_id, 
                        $Device_category_name, 
                        $Device_modelName]);
        echo "Produit créé avec succès.<br>";
    } else {
        // Mise à jour d'un produit existant
        $sql = "UPDATE device SET 
                            Device_status = ?, 
                            Device_exitDate = ?, 
                            Device_entryDate = ?, 
                            Device_priceRent = ?, 
                            Device_description = ?, 
                            Device_name = ?, 
                            Device_model_id = ?, 
                            Device_category_id = ?, 
                            Device_category_name = ?, 
                            Device_modelName = ?
                WHERE Device_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$DeviceStatus, 
                        $DeviceExitDate, 
                        $DeviceEntryDate, 
                        $DevicePriceRent, 
                        $DeviceDescription, 
                        $DeviceName, 
                        $Device_model_id, 
                        $Device_category_id, 
                        $Device_category_name, 
                        $Device_modelName, 
                        $productId]);
        echo "Produit mis à jour avec succès.<br>";
    }
}
