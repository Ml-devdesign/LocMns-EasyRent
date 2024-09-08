<?php
require_once __DIR__ . "/../../include/Connect.php";
require_once __DIR__ . "/../../include/Protect.php";

// Initialisation des tableaux pour les modèles et catégories
$models = [];
$categories = [];

try {
    // Requête SQL pour récupérer les modèles depuis la table `device_model`
    $sql_models = "SELECT dm.Device_model_id, dm.Device_modelName, db.Device_brand_name 
                   FROM device_model dm
                   LEFT JOIN device_brand db ON dm.Device_brand_id = db.Device_brand_id";

    // Requête SQL pour récupérer les catégories depuis la table `device_category`
    $sql_categories = "SELECT Device_category_id, Device_category_name FROM device_category";
    
    // Exécuter les deux requêtes
    $stmt_models = $db->query($sql_models);
    $stmt_categories = $db->query($sql_categories);
    
    if ($stmt_models && $stmt_categories) {
        // Récupérer les résultats sous forme de tableaux associatifs
        $models = $stmt_models->fetchAll(PDO::FETCH_ASSOC);
        $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Erreur lors de l'exécution des requêtes SQL.";
    }

} catch (PDOException $e) {
    echo "Erreur lors du traitement des modèles et catégories : " . htmlspecialchars($e->getMessage());
}

// Initialisation des valeurs du produit si elles ne sont pas déjà définies
$product = isset($product) ? $product : [
    'Device_id' => '',
    'Device_name' => '',
    'Device_status' => '',
    'Device_entryDate' => '',
    'Device_exitDate' => '',
    'Device_priceRent' => '',
    'Device_description' => '',
    'Device_model_id' => '',
    'Device_category_id' => ''
];
?>

<!-- Formulaire d'ajout et/ou de modification de produit -->
<form class="p container_dash display_column" method="post" action="process.php">
    <input type="hidden" name="Device_id" value="<?= htmlspecialchars(isset($product['Device_id']) ? $product['Device_id'] : '') ?>">
    <h1>Modifier le Produit <?= htmlspecialchars($product['Device_name']) . ' ' . 'Id : ' . htmlspecialchars($product['Device_id']) ?></h1>        

    <label>
        Nom : <br>
        <input class="p glassmorphism" type="text" name="Device_name" value="<?= htmlspecialchars($product['Device_name']); ?>">
    </label>  
    <br>

    <!-- Sélection du modèle -->
    <label>
        Modèle :<br> 
        <div class="box-Device_model">
            <select name="Device_model_id">
            <?php 
                if (!empty($models)) {
                    foreach ($models as $model) {
                        // Vérifie si l'ID du modèle correspond à celui du produit
                        $selected = ($model['Device_model_id'] == $product['Device_model_id']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($model['Device_model_id']) . '" ' . $selected . '>' 
                             . htmlspecialchars($model['Device_modelName']) . ' - ' . htmlspecialchars($model['Device_brand_name']) 
                             . '</option>';
                    }
                } else {
                    echo '<option value="">Aucun modèle disponible</option>';
                }
            ?>
            </select>
        </div>
    </label>
    <br>

    <!-- Sélection de la catégorie -->
    <label>
        Catégorie : <br>
        <div class="box-Device_category">
            <select name="Device_category_id">
            <?php 
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        // Vérifie si l'ID de la catégorie correspond à celui du produit
                        $selected = ($category['Device_category_id'] == $product['Device_category_id']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($category['Device_category_id']) . '" ' . $selected . '>' 
                             . htmlspecialchars($category['Device_category_name']) . '</option>';
                    }
                } else {
                    echo '<option value="">Aucune catégorie disponible</option>';
                }
            ?>
            </select>
        </div>
    </label>
    <br>

    <!-- Statut du produit -->
    <label>
        Statut : <br>
        <input class="p glassmorphism" type="text" name="Device_status" value="<?= htmlspecialchars($product['Device_status']); ?>">
    </label>
    <br>

    <!-- Date d'entrée -->
    <label>
        Date d'entrée : <br>
        <input class="p glassmorphism" type="date" name="Device_entryDate" value="<?= htmlspecialchars($product['Device_entryDate']); ?>">
    </label>
    <br>

    <!-- Date de sortie -->
    <label>
        Date de sortie : <br>
        <input class="p glassmorphism" type="date" name="Device_exitDate" value="<?= htmlspecialchars($product['Device_exitDate']); ?>">
    </label>
    <br>

    <!-- Prix de location -->
    <label>
        Prix : <br>
        <input class="p glassmorphism" type="number" name="Device_priceRent" value="<?= htmlspecialchars($product['Device_priceRent']); ?>">
    </label>
    <br>

    <!-- Description du produit -->
    <label>
        Description : <br>
        <textarea class="p glassmorphism" name="Device_description"><?= htmlspecialchars($product['Device_description']); ?></textarea>
    </label>
    <br>

    <!-- Bouton de soumission -->
    <button type="submit"><?= empty($product['Device_id']) ? 'Créer' : 'Mettre à jour'; ?></button>
</form>
