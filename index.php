<?php
// Assurez-vous que le fichier de connexion est correctement inclus
require_once __DIR__ . "/Include/Connect.php"; // Le fichier doit définir la variable $db
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location de Matériel Informatique</title>
    <link rel="stylesheet" href="/Css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>  
    <!-- Header -->
    <header>
        <?php include_once 'Include/Header.php'; ?>  
    </header>

    <div class="container">
        <!-- Bannière ou image en tête -->
        <header class="banner" style="background-image: url('path/to/your/banner-image.jpg');">
            <div class="banner-text">
                <h1>Bienvenue sur Votre Site</h1>
                <p>Le lieu de rencontre pour tous les bricoleurs.</p>
                <a href="inscription.php" class="btn btn-primary">Rejoignez-nous</a>
            </div>
        </header>

        <!-- Contenu principal -->
        <main class="main-content">
            <!-- Section de recherche -->
            <section class="search-section">
                <form action="recherche.php" method="GET" class="search-form">
                    <input type="text" name="query" placeholder="Recherchez un outil, une annonce..." class="search-input">
                    <button type="submit" class="search-button"><i class="fas fa-search"></i> Rechercher</button>
                </form>
            </section>

            <!-- Section des annonces en vedette -->
            <section class="featured-ads">
                <h2>Outils en vedette</h2>
                <div class="ads-grid">
                    <article class="ad-item">
                        <img src="path/to/ad1-image.jpg" alt="Titre de l'annonce 1" class="ad-image">
                        <h3 class="ad-title">Titre de l'annonce 1</h3>
                        <p class="ad-description">Description rapide de l'annonce 1...</p>
                        <a href="details.php?id=1" class="btn btn-secondary">Voir plus</a>
                    </article>
                    <article class="ad-item">
                        <img src="path/to/ad2-image.jpg" alt="Titre de l'annonce 2" class="ad-image">
                        <h3 class="ad-title">Titre de l'annonce 2</h3>
                        <p class="ad-description">Description rapide de l'annonce 2...</p>
                        <a href="details.php?id=2" class="btn btn-secondary">Voir plus</a>
                    </article>
                    <!-- Ajoutez plus d'annonces selon vos besoins -->
                </div>
            </section>

            <!-- Section des dernières annonces -->
            <section class="latest-ads">
                <h2>Dernières annonces</h2>
                <div class="ads-list">
                    <article class="ad-item">
                        <h3 class="ad-title">Titre de l'annonce récente 1</h3>
                        <p class="ad-description">Description courte de l'annonce récente 1...</p>
                        <a href="details.php?id=3" class="btn btn-secondary">Voir plus</a>
                    </article>
                    <article class="ad-item">
                        <h3 class="ad-title">Titre de l'annonce récente 2</h3>
                        <p class="ad-description">Description courte de l'annonce récente 2...</p>
                        <a href="details.php?id=4" class="btn btn-secondary">Voir plus</a>
                    </article>
                    <!-- Ajoutez plus d'annonces récentes selon vos besoins -->
                </div>
            </section>

            <!-- Section des catégories ou tags populaires -->
            <section class="popular-tags">
                <h2>Catégories Populaires</h2>
                <div class="tags-container">
                    <a href="category.php?tag=bricolage" class="tag">Bricolage</a>
                    <a href="category.php?tag=jardinage" class="tag">Jardinage</a>
                    <a href="category.php?tag=menuiserie" class="tag">Menuiserie</a>
                    <a href="category.php?tag=peinture" class="tag">Peinture</a>
                    <!-- Ajoutez plus de tags selon vos besoins -->
                </div>
            </section>

            <!-- Section d'appel à l'action -->
            <section class="call-to-action">
                <div class="cta-content">
                    <h2>Partagez vos outils ou trouvez ce dont vous avez besoin</h2>
                    <p>Inscrivez-vous gratuitement et commencez à publier ou rechercher des annonces dès aujourd'hui.</p>
                    <a href="inscription.php" class="btn btn-primary">Créer un compte</a>
                </div>
            </section>
        </main>
    </div>
    <!-- Footer -->
    <footer>
        <?php include_once 'Include/Footer.php'; ?>  
    </footer>

    <script src="/js/main.js"></script>
</body>
</html>
