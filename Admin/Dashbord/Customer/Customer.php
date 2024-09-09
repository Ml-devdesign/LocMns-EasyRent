<?php
// Inclusion du fichier de connexion à la base de données
require_once __DIR__ . "/../../include/Connect.php";
require_once __DIR__ . "/../../include/Protect.php"; // Protection de session, etc.

try {
    // Pagination
    $perPage = 5; // Nombre de clients par page

    // Validation du paramètre `page`
    $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($currentPage < 1) {
        $currentPage = 1;
    }

    // Calcul du nombre total de clients
    $stmt = $db->query("SELECT COUNT(*) FROM customer WHERE Customer_lastName != 'Anonyme'");
    $totalCustomers = $stmt->fetchColumn();
    $totalPages = ceil($totalCustomers / $perPage); // Nombre total de pages
    if ($currentPage > $totalPages) {
        $currentPage = $totalPages; // Si la page demandée dépasse le total
    }

    $start = ($currentPage - 1) * $perPage;

    // Recherche de clients
    $searchTerm = isset($_GET['search']) ? "%".$_GET['search']."%" : null;
    if ($searchTerm) {
        // Ajout de la condition pour exclure les clients anonymes lors de la recherche
        $sql = "SELECT * FROM customer WHERE Customer_lastName LIKE :Customer_lastName AND Customer_lastName != 'Anonyme' LIMIT :start, :perPage";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':Customer_lastName', $searchTerm, PDO::PARAM_STR);
    } else {
        // Ajout de la condition pour exclure les clients anonymes lors de l'affichage normal
        $sql = "SELECT * FROM customer WHERE Customer_lastName != 'Anonyme' LIMIT :start, :perPage";
        $stmt = $db->prepare($sql);
    }

    // Utilisation des paramètres liés pour éviter les injections SQL
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();

    // Récupération des clients de la page courante
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de récupération des clients: " . htmlspecialchars($e->getMessage());
}

if (empty($customers)) {
    echo '<div class="button-wrapper"><a class="btn outline" href="/Admin/Dashbord/Customer/add.php">Ajouter</a></div>';
    echo '<p>Aucune client trouvée.</p>';
}
?>

<!-- Moteur de recherche --> 
<form action="" method="GET" class="search-bar">
    <input class="rond" type="text" name="search" id="searchInput" placeholder="Rechercher un client..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : '' ?>">
    <button class="btn fill" type="submit" id="searchButton">Rechercher</button>
</form>
<!-- Fin du moteur de recherche -->

<!-- Pagination for Customer -->
<nav class="pagination-nav">
    <?php 
    $urlParams = $_GET; // Récupère les paramètres actuels de l'URL
    if ($currentPage > 1) {
        $urlParams['page'] = $currentPage - 1;
        echo '<a class="btn prev" href="?' . http_build_query($urlParams) . '">&laquo; Précédent</a>';
    }
    for ($page = 1; $page <= $totalPages; $page++) {
        $urlParams['page'] = $page;
        $activeClass = ($page == $currentPage) ? 'active' : '';
        echo '<a class="btn page ' . $activeClass . '" href="?' . http_build_query($urlParams) . '">' . $page . '</a>';
    }
    if ($currentPage < $totalPages) {
        $urlParams['page'] = $currentPage + 1;
        echo '<a class="btn next" href="?' . http_build_query($urlParams) . '">Suivant &raquo;</a>';
    }
    ?>
</nav>
<!-- Fin de la pagination -->

<?php 

// Affichage des clients ou message si aucun client n'est trouvé
if (!empty($customers)) {
foreach ($customers as $customer) { ?>
    <section id="admin_bg" class="section_customer glassmorphism d1">
        <div class="wrapper">
            <div class="banner-image"></div>
            <h1>Prenom : <?= htmlspecialchars($customer['Customer_firstName'], ENT_QUOTES, 'UTF-8') ?></h1>
            <h1>Nom : <?= htmlspecialchars($customer['Customer_lastName'], ENT_QUOTES, 'UTF-8') ?></h1>
            <!-- Verificataion de la recuperation des dates -->
            <div class="button-wrapper open-modal-btn-wrapper">
                <a class="btn outline" data-type="customer" data-id="<?= htmlspecialchars($customer['Customer_id'], ENT_QUOTES, 'UTF-8') ?>">Modifier</a>
            </div>
            
            <!-- Formulaire de l'anonymisation ou suppresion -->
            <form action="/Admin/Dashbord/Customer/delete.php" method="POST">
                <div class="button-wrapper">  
                    <input type="hidden" name="id" value="<?= htmlspecialchars($customer['Customer_id'], ENT_QUOTES, 'UTF-8') ?>">
                    <label>
                        <input type="checkbox" name="checkbox" value="1" required> Confirmer l'anonymisation
                    </label>
                    <label>
                        <input type="checkbox" name="checkboxDeleteAll" value="1"> Supprimer toutes les données
                    </label>
                    <!-- Bouton qui ouvre le modal de confirmation -->
                    <button type="button" class="btn fill open-delete-modal" data-id="<?= htmlspecialchars($customer['Customer_id'], ENT_QUOTES, 'UTF-8') ?>">Supprimer</button> 
                </div>
            </form>

        </div>
    </section>

    <!-- Modale pour chaque client -->
    <div id="customer-modal-<?= htmlspecialchars($customer['Customer_id'], ENT_QUOTES, 'UTF-8') ?>" class="modal-overlay" style="display:none;">
        <div class="modal-wrapper container_dash">
            <div class="close-btn-wrapper">
                <button class="btn outline close-modal-btn">Fermer</button>
            </div>
            <div class="modal-content">
                <?php include 'add.php'; ?>
            </div>
        </div>
    </div>
<?php }
} else {
    echo 'Aucun client trouvé.';
}
?>

 <!-- Modal de confirmation -->
    <div id="delete-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Confirmer la suppression</h2>
            <p>Êtes-vous sûr de vouloir anonymiser et supprimer les données de ce client ?</p>
            <form action="/Admin/Dashbord/Customer/delete.php" method="POST" id="delete-form">
                <input type="hidden" name="id" id="delete-id">
                <label>
                    <input type="checkbox" name="checkbox" value="1"> Confirmer l'anonymisation
                </label>
                <label>
                    <input type="checkbox" name="checkboxDeleteAll" value="1"> Supprimer toutes les données
                </label>
                <button type="submit" class="btn fill">Supprimer</button>
            </form>
            <button class="close-modal">Annuler</button>
        </div>
    </div>

<!-- Pagination for Customer -->
<nav class="pagination-nav">
    <?php 
    $urlParams = $_GET; // Récupère les paramètres actuels de l'URL
    if ($currentPage > 1) {
        $urlParams['page'] = $currentPage - 1;
        echo '<a class="btn prev" href="?' . http_build_query($urlParams) . '">&laquo; Précédent</a>';
    }
    for ($page = 1; $page <= $totalPages; $page++) {
        $urlParams['page'] = $page;
        $activeClass = ($page == $currentPage) ? 'active' : '';
        echo '<a class="btn page ' . $activeClass . '" href="?' . http_build_query($urlParams) . '">' . $page . '</a>';
    }
    if ($currentPage < $totalPages) {
        $urlParams['page'] = $currentPage + 1;
        echo '<a class="btn next" href="?' . http_build_query($urlParams) . '">Suivant &raquo;</a>';
    }
    ?>
</nav>
<!-- Fin de la pagination -->
