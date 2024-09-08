<?php 
include __DIR__ . "/../Include/Connect.php";
require_once __DIR__ . "/../Include/Protect.php"; // Protection et connexion à la base de données
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Index Ad</title>
    <link rel="stylesheet" href="/Css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body class="dash_img-bgCover">

<!-- Header -->
<header>
    <?php include __DIR__ . "/../Include/Header.php"; ?>
</header>

<div class="view">  
    <div class="dash_img-bgCover"></div>
  
    <div class="display">
        <!-- Barre de recherche -->
        <div class="rond container_dash">
            <label for="site-search">
                <span class="material-symbols-outlined">search</span>
            </label>
            <input class="rond" type="search" id="site-search" placeholder="Rechercher.."/>
        </div>
    </div>


      

    <div class="display">
        <!-- Section Clients -->
        <div class="container_dash">
            <div class="card_header">
                <span class="material-symbols-outlined">person</span>
                <h2>Clients</h2>
                
                <!-- Bouton pour ouvrir la modal d'ajout  -->
                <div class="button-wrapper open-modal-btn-wrapper">
                    <a class="btn outline" href="/Admin/Dashbord/Customer/add.php" id="openAddCustomerModal">Ajouter</a>
                </div>
                <!-- Bouton Voir Plus -->
                <div class="button-wrapper">
                    <a id="viewMoreCustomers" class="btn outline">Voir Plus</a>
                </div>
            </div>

            <div id="customersContainer" class="card_customer">
                <?php include __DIR__ . '/Dashbord/Customer/Customer.php'; ?>    
            </div>
        </div>

        <!-- Section Produits -->
        <div class="container_dash">
            <div class="card_header">
                <span class="material-symbols-outlined">shopping_cart</span>
                <h2>Produits</h2>
                
                <!-- Bouton pour ouvrir la modal de Produit -->
                <div class="button-wrapper open-modal-btn-wrapper">
                    <a class="btn outline" data-type="product" href="/Admin/Dashbord/Products/add.php">Ajouter</a>
                </div>
                <!-- Bouton Voir Plus -->
                <div class="button-wrapper">
                    <a id="viewMoreProducts" class="btn outline">Voir Plus</a>
                </div>
            </div>

            <div id="productsContainer" class="card_customer">
                <?php include __DIR__ . '/Dashbord/Products/Products.php'; ?>    
            </div>  
        </div>

        <!-- Section Réservations -->
        <div class="container_dash">
            <div class="card_header">
                <span class="material-symbols-outlined">shopping_cart</span>
                <h2>Réservations</h2>
                
                <!-- Bouton pour ouvrir la modal de Réservation -->
                <div class="button-wrapper open-modal-btn-wrapper">
                    <a class="btn outline" data-type="reservations" href="/Admin/Dashbord/Reservations/add.php">Ajouter</a>
                </div>
                <!-- Bouton Voir Plus -->
                <div class="button-wrapper">
                    <a id="viewMoreReservations" class="btn outline">Voir Plus</a>
                </div>
            </div>
            <div id="reservationsContainer" class="card_customer">
                <?php include __DIR__ . '/Dashbord/Reservations/Reservations.php'; ?>    
            </div>        
        </div>
    </div> 
</div>


<!-- Modal pour l'ajout de client (sans ID spécifique) -->
<div id="customer-modal" class="modal-overlay" style="display:none;">
  <div class="modal-wrapper container_dash">
    <div class="close-btn-wrapper">
      <button class="btn outline close-modal-btn">Fermer</button>
    </div>
    <div class="modal-content">
      <!-- Inclure ici le formulaire d'ajout de client -->
      <?php include __DIR__ . '/Dashbord/Customer/add.php'; ?>
    </div>
  </div>
</div>


<div class="view">
      <div class="display">
          <!-- Formulaire de suppression -->
          <!-- <form class="container_dash " method="post" action="process.php">
              <h2>Supprimer un client</h2>
              <input type="hidden" name="action" value="delete">
              <label>ID Client: <input type="text" name="customerId"></label><br>
              <button type="submit">Supprimer</button>
          </form> -->

          <!-- Formulaire de lecture -->
          <!-- <form class="container_dash " method="get" action="process.php">
              <h2>Lire un client</h2>
              <input type="hidden" name="action" value="read">
              <label>ID Client: <input type="text" name="customerId"></label><br>
              <button type="submit">Lire</button>
          </form> -->
      </div>
  </div>
<!-- https://dribbble.com/shots/23870293-Apple-Vision-Pro-Spatial-UI-Health-App# -->
<!-- https://dribbble.com/shots/22366260-Brain-Activity-Medical-UI-Data-Visualization -->
<!--Font = Pour que le lien fonctionne ne pas oublier le outline dans les filtre
    https://fonts.google.com/icons?selected=Material+Symbols+Outlined:shopping_cart:FILL@0;wght@300;GRAD@0;opsz@24&icon.size=24&icon.color=%23FFFFFF&icon.platform=web&icon.category=Business%26Payments&icon.style=Outlined -->
</div>

<!-- Footer -->
 <footer>
   <?php include __DIR__ . "/../Include/Footer.php"; ?> 
 </footer>
  <!-- Script -->
    <script src="/Admin/js/view_more.js"></script>  
    <script src="/Admin/js/modal-all.js"></script> 

  

</body>
</html>