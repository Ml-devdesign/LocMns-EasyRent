<?php
require_once __DIR__ . "/../../include/Connect.php";
require_once __DIR__ . "/../../include/Protect.php"; // Protection et connexion à la base de données

try {
    // Pagination
    $perPage = 5; // Nombre de produits par page

    // Validation et assainissement du paramètre `page_products`
    $currentPage = isset($_GET['page_products']) && is_numeric($_GET['page_products']) ? (int)$_GET['page_products'] : 1;
    $currentPage = max($currentPage, 1); // Assure que la page est au moins 1

    // Validation et assainissement du paramètre `search_products`
    $searchTerm = isset($_GET['search_products']) ? "%" . htmlspecialchars($_GET['search_products'], ENT_QUOTES, 'UTF-8') . "%" : null;

    // Calcul du nombre total de produits
    if ($searchTerm) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM device
                              JOIN device_model ON device.Device_model_id = device_model.Device_model_id
                              JOIN device_category ON device.Device_category_id = device_category.Device_category_id
                              WHERE device.Device_name LIKE :searchTerm");
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    } else {
        $stmt = $db->query("SELECT COUNT(*) FROM device
                            JOIN device_model ON device.Device_model_id = device_model.Device_model_id
                            JOIN device_category ON device.Device_category_id = device_category.Device_category_id");
    }
    
    $stmt->execute();
    $totalProducts = $stmt->fetchColumn();
    $totalPages = ceil($totalProducts / $perPage); // Nombre total de pages
    $currentPage = min($currentPage, $totalPages); // Assure que la page ne dépasse pas le total

    $start = ($currentPage - 1) * $perPage;

    // Requête pour récupérer les produits
    if ($searchTerm) {
        $sql = "SELECT device.*, device_model.Device_modelName, device_category.Device_category_name
                FROM device
                JOIN device_model ON device.Device_model_id = device_model.Device_model_id
                JOIN device_category ON device.Device_category_id = device_category.Device_category_id
                WHERE device.Device_name LIKE :searchTerm
                LIMIT :start, :perPage";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    } else {
        $sql = "SELECT device.*, device_model.Device_modelName, device_category.Device_category_name
                FROM device
                JOIN device_model ON device.Device_model_id = device_model.Device_model_id
                JOIN device_category ON device.Device_category_id = device_category.Device_category_id
                LIMIT :start, :perPage";
        $stmt = $db->prepare($sql);
    }

    // Bind des paramètres
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();

    // Récupération des produits de la page courante
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de récupération des produits: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}

// Si aucun produit n'est trouvé
if (empty($products)) {
    echo '<div class="button-wrapper"><a class="btn outline" href="/Admin/Dashbord/Products/add.php">Ajouter</a></div>';
    echo '<p>Aucun produit trouvé.</p>';
}
?>

<!-- Moteur de recherche -->
<form action="" method="GET" class="search-bar">
    <input class="rond" type="text" name="search_products" id="searchInputProducts" placeholder="Rechercher un produit..." value="<?= isset($_GET['search_products']) ? htmlspecialchars($_GET['search_products'], ENT_QUOTES, 'UTF-8') : '' ?>">
    <button class="btn fill" type="submit" id="searchButtonProducts">Rechercher</button>
</form>
<!-- Fin du moteur de recherche -->

<?php foreach ($products as $product) { ?>
    <section id="admin_bg" class="section_customer glassmorphism d1">
        <div class="wrapper">
            <div class="banner-image"></div>
            <h1>Nom : <?= htmlspecialchars($product['Device_name'], ENT_QUOTES, 'UTF-8') ?></h1>
            <h1>Id : <?= htmlspecialchars($product['Device_id'], ENT_QUOTES, 'UTF-8') ?></h1>
            <div class="button-wrapper open-modal-btn-wrapper"> 
                <a class="btn outline open-product-modal-btn" data-id="<?= htmlspecialchars($product['Device_id'], ENT_QUOTES, 'UTF-8') ?>">Edit</a>
            </div>
            <div class="button-wrapper"> 
                <a class="btn fill" data-id="<?= htmlspecialchars($product['Device_id'], ENT_QUOTES, 'UTF-8') ?>">Delete</a>
            </div>
        </div>
    </section>

    <!-- Modale pour chaque produit -->
    <div id="product-modal-<?= htmlspecialchars($product['Device_id'], ENT_QUOTES, 'UTF-8') ?>" class="modal-overlay" style="display:none;">
        <div class="modal-wrapper container_dash">
            <div class="close-btn-wrapper">
                <button class="btn outline close-modal-btn">Fermer</button>
            </div>
            <div class="modal-content">
                <?php include 'add.php'; ?>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Pagination for Products -->
<nav class="pagination-nav">
    <?php 
    $urlParams = $_GET; // Récupère les paramètres actuels de l'URL
    if ($currentPage > 1) {
        $urlParams['page_products'] = $currentPage - 1;
        echo '<a class="btn prev" href="?' . http_build_query($urlParams) . '">&laquo; Précédent</a>';
    }
    for ($page = 1; $page <= $totalPages; $page++) {
        $urlParams['page_products'] = $page;
        $activeClass = ($page == $currentPage) ? 'active' : '';
        echo '<a class="btn page ' . $activeClass . '" href="?' . http_build_query($urlParams) . '">' . $page . '</a>';
    }
    if ($currentPage < $totalPages) {
        $urlParams['page_products'] = $currentPage + 1;
        echo '<a class="btn next" href="?' . http_build_query($urlParams) . '">Suivant &raquo;</a>';
    }
    ?>
</nav>
<!-- Fin de la pagination -->
