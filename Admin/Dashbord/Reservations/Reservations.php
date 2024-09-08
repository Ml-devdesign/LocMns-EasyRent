<?php
require_once __DIR__ . "/../../include/Connect.php";
require_once __DIR__ . "/../../include/Protect.php"; // Protection et connexion à la base de données

// Initialisation des variables pour éviter les erreurs
$reservations = [];
$totalPages = 1; // Nombre total de pages

try {
    // Pagination
    $perPage = 5; // Nombre de réservations par page

    // Validation et assainissement du paramètre `page_reservations`
    $currentPage = isset($_GET['page_reservations']) && is_numeric($_GET['page_reservations']) ? (int)$_GET['page_reservations'] : 1;
    $currentPage = max($currentPage, 1); // Assure que la page est au moins 1

    // Validation et assainissement du paramètre `search_reservations`
    $searchTerm = isset($_GET['search_reservations']) ? "%". htmlspecialchars($_GET['search_reservations'], ENT_QUOTES, 'UTF-8') ."%" : null;

    // Calcul du nombre total de réservations
    if ($searchTerm) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM reservation WHERE Customer_id LIKE :customer_id");
        $stmt->bindParam(':customer_id', $searchTerm, PDO::PARAM_STR);
    } else {
        $stmt = $db->query("SELECT COUNT(*) FROM reservation");
    }
    
    $stmt->execute();
    $totalReservations = $stmt->fetchColumn();
    
    // Calcul du nombre total de pages et ajustement de la page courante
    $totalPages = ceil($totalReservations / $perPage);
    $currentPage = min($currentPage, $totalPages); // Assure que la page ne dépasse pas le total

    // Calcul de l'offset pour la requête LIMIT
    $start = ($currentPage - 1) * $perPage;

    // Requête pour récupérer les réservations
    if ($searchTerm) {
        $sql = "SELECT * FROM reservation WHERE Customer_id LIKE :customer_id LIMIT :start, :perPage";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':customer_id', $searchTerm, PDO::PARAM_STR);
    } else {
        $sql = "SELECT * FROM reservation LIMIT :start, :perPage";
        $stmt = $db->prepare($sql);
    }

    // Utilisation de bindValue pour LIMIT
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();

    // Récupération des réservations de la page courante
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de récupération des réservations: " . htmlspecialchars($e->getMessage());
}

// Affichage des réservations ou message si aucune réservation n'est trouvée
if (empty($reservations)) {
    echo '<div class="button-wrapper"><a class="btn outline" href="/Admin/Dashbord/Reservations/add.php">Ajouter</a></div>';
    echo '<p>Aucune réservation trouvée.</p>';
}
?>

<!-- Moteur de recherche -->
<form action="" method="GET" class="search-bar">
    <input class="rond" type="text" name="search_reservations" id="searchInputReservations" placeholder="Rechercher par ID de client..." value="<?= isset($_GET['search_reservations']) ? htmlspecialchars($_GET['search_reservations'], ENT_QUOTES, 'UTF-8') : '' ?>">
    <button class="btn fill" type="submit" id="searchButtonReservations">Rechercher</button>
</form>
<!-- Fin du moteur de recherche -->


<?php foreach ($reservations as $reservation) { ?>
    <section class="section_customer glassmorphism d1">
        <div class="wrapper">
            <div class="banner-image"></div>
            <h1>ID : <?= htmlspecialchars($reservation['Reservation_id'], ENT_QUOTES, 'UTF-8') ?></h1>
            <h1>Date de début : <?= htmlspecialchars($reservation['Reservation_startDate'], ENT_QUOTES, 'UTF-8') ?></h1>
            <h1>Date de fin : <?= htmlspecialchars($reservation['Reservation_endDate'], ENT_QUOTES, 'UTF-8') ?></h1>
            <h1>ID Client : <?= htmlspecialchars($reservation['Customer_id'], ENT_QUOTES, 'UTF-8') ?></h1>
            <div class="button-wrapper open-modal-btn-wrapper">
                <a class="btn outline" data-type="reservation" data-id="<?= htmlspecialchars($reservation['Reservation_id'], ENT_QUOTES, 'UTF-8') ?>">Edit</a>
            </div>
            <div class="button-wrapper">
                <a class="btn fill" data-id="<?= htmlspecialchars($reservation['Reservation_id'], ENT_QUOTES, 'UTF-8') ?>">Delete</a>
            </div>
        </div>
    </section>

    <!-- Modale pour chaque réservation -->
    <div id="reservation-modal-<?= htmlspecialchars($reservation['Reservation_id'], ENT_QUOTES, 'UTF-8') ?>" class="modal-overlay" style="display:none;">
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

<!-- Pagination -->
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
</nav>
<!-- Fin de la pagination -->
