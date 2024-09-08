<?php
require_once __DIR__ . "/../../include/Connect.php";
require_once __DIR__ . "/../../include/Protect.php";
// Seperation de la logique et de la recuperation de données 
// Récupération des autorisations : Cette étape est nécessaire dans add.php pour afficher la liste déroulante avec les options d'autorisation.
// Affiche le formulaire pour ajouter ou modifier un client.
// Récupère les données existantes (client et autorisations) depuis la base de données pour remplir le formulaire.


// Si le formulaire est pour la modification, on suppose que la variable $customer est définie
// Sinon, on initialise un tableau vide pour éviter les erreurs
// Si le formulaire est pour la modification, on suppose que la variable $customer est définie sinon on initialise un tableau vide pour éviter les erreurs 
$customer = $customer ?? [
    'Customer_id' => '',
    'Customer_firstName' => '',
    'Customer_lastName' => '',
    'Customer_email' => '',
    'Customer_password' => '',
    'Customer_adress' => '',
    'Customer_zipCode' => '',
    'Customer_phoneNum' => '',
    'Admin_booleen' => 0,
    'consent_date' => null,
    'deletion_date' => null,
    'Authorisation_id' => ''
];

// Pourquoi récupérer les autorisations dans add.php :
// Le fichier add.php gère l'affichage du formulaire pour créer ou modifier un client.
// Si vous voulez afficher une liste déroulante des autorisations, vous devez récupérer ces données avant de rendre le formulaire à l'utilisateur. Par conséquent, c'est dans add.php que vous devez faire la requête SQL pour obtenir les autorisations.

// Exemple de flux :
// Utilisateur visite /Admin/Dashbord/Customer/add.php :

// Le fichier add.php s'exécute.
// Il récupère les informations du client (si un ID est passé) et les autorisations depuis la base de données pour les afficher dans le formulaire.
// Le formulaire est affiché, avec les autorisations dans une liste déroulante.
// Utilisateur soumet le formulaire :

// Le formulaire envoie une requête POST à process.php.
// process.php récupère les données du formulaire et les traite (validation, insertion, mise à jour).

// Requête SQL pour récupérer les autorisations triées par description
try {
    $sql = "SELECT Authorisation_id, Authorisation_description FROM authorisation ORDER BY Authorisation_description ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $authorisations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifiez si la requête retourne des résultats
    if (!$authorisations) {
        echo "Aucune autorisation trouvée.";
        $authorisations = []; // Assurez-vous que $authorisations est un tableau vide pour éviter l'erreur du foreach
    }
} catch (PDOException $e) {
    // Gérer les erreurs de requête
    echo "Erreur lors de la récupération des autorisations : " . $e->getMessage();
    $authorisations = []; // Initialiser en tant que tableau vide en cas d'erreur
}



?>

<form class="p container_dash display_column" method="POST" action="/Admin/Dashbord/Customer/process.php">
    <input type="hidden" name="Customer_id" value="<?= htmlspecialchars($customer['Customer_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <h1 class="">Modifier le client <?= htmlspecialchars(($customer['Customer_firstName'] ?? '') . ' ' . ($customer['Customer_lastName'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h1>
    
    <!-- <?php var_dump($customer['consent_date']);  // Cela doit afficher une date au format 'YYYY-MM-DD'
    var_dump($customer['deletion_date']);
    ?>  l'erreur venant du format de la date car la bdd stocker la date et la recuperation afficher YYYY-MM-DD HH:MM:SS ce qui empêcher l'affichage correct dans un champ de type date -->
           
    <label>
        Prénom: <br>
        <input class="p glassmorphism" type="text" name="Customer_firstName" value="<?= htmlspecialchars($customer['Customer_firstName'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <br>

    <label>
        Nom: <br>
        <input class="p glassmorphism" type="text" name="Customer_lastName" value="<?= htmlspecialchars($customer['Customer_lastName'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <br>

    <label>
        Email: <br>
        <input class="p glassmorphism" type="text" name="Customer_email" pattern="^[\w.-]+@([\w-]+\.)+[\w-]{2,4}$" value="<?= htmlspecialchars($customer['Customer_email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <br>

    <label>
        Mot de passe: <br>
        <input class="p glassmorphism" type="password" name="Customer_password" value="<?= htmlspecialchars($customer['Customer_password'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <br>

    <label>
        Adresse: <br>
        <input class="p glassmorphism" type="text" name="Customer_adress" value="<?= htmlspecialchars($customer['Customer_adress'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <br>

    <label>
        Code Postal:<br> 
        <input class="p glassmorphism" type="text" name="Customer_zipCode" value="<?= htmlspecialchars($customer['Customer_zipCode'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </label>
    <br>

    <label>
        Numéro de téléphone: <br>
        <input class="p glassmorphism" type="text" name="Customer_phoneNum" pattern="[0-9]{10}" value="<?= htmlspecialchars($customer['Customer_phoneNum'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="XXXXXXXXXX">
    </label>
    <br>

    <!-- <label>
        Admin: <br>
        <input class="p glassmorphism " type="checkbox" name="Admin_booleen" <?= isset($customer['Admin_booleen']) && $customer['Admin_booleen'] == 1 ? 'checked' : ''; ?>>
    </label>
    <br> -->

   <label>
        ID d'autorisation: <br>
        <select class="p glassmorphism display" name="Authorisation_id">
            <option value="">Sélectionner une autorisation</option>
            <?php foreach ($authorisations as $authorisation): ?>
                <option value="<?= htmlspecialchars($authorisation['Authorisation_id'], ENT_QUOTES, 'UTF-8') ?>"
                    <?= isset($customer['Authorisation_id']) && $customer['Authorisation_id'] == $authorisation['Authorisation_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($authorisation['Authorisation_description'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <br>

    <!-- RGPD -->
    <label>
        Date de consentement: <br>
        <input class="p glassmorphism display" type="date" name="consent_date" 
            value="<?= !empty($customer['consent_date']) ? date('Y-m-d', strtotime($customer['consent_date'])) : '' ?>">
    </label>
    <br>

    <!-- Pour eviter la modification de la date de d'anonimisation on modifie le label -->
    <!-- <label>
        Date de suppression prévue: <br>
        <input class="p glassmorphism display" type="date" name="deletion_date" 
            value="<?= !empty($customer['deletion_date']) ? date('Y-m-d', strtotime($customer['deletion_date'])) : '' ?>">
    </label> -->
    
    <label>
    Date de suppression prévue: <br>
        <p class="p glassmorphism display">
            <?= !empty($customer['deletion_date']) ? date('Y-m-d', strtotime($customer['deletion_date'])) : 'Non défini' ?>
        </p>
    </label>

    <br>

    <button type="submit"><?= empty($customer['Customer_id']) ? 'Créer' : 'Mettre à jour'; ?></button>
</form>
