<?php
// Vérifiez si la session n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<label class="logo">
    <span class="logo-noir">Easy</span>
    <img class="logo-img" src="/img/logosansmarque-removebg-preview.png" alt="Logo EasyRent">
    <span class="logo-orange">Rent</span>
</label>
<input type="checkbox" id="box">
<label for="box" class="btn-area">
    <span>&#9776;</span>
</label>
<nav class="nav-area">
    <ul>
        <li><a href="/index.php">Accueil</a></li>
        <li><a href="/public/catalogue.php">Catalogue</a></li>
        <li><a href="#">Nos services</a></li>
        <li><a href="#">Contact</a></li>
        <?php if (!isset($_SESSION['user_connected']) || $_SESSION['user_connected'] !== "ok"): ?>
        <li><a href="/login.php">Connexion</a></li>
        <?php else: ?>
        <li><a href="/logout.php">Déconnexion</a></li>
        <?php endif; ?>
    </ul>
</nav>
