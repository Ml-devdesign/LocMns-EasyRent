<?php
// Inclusion des variables d'environnement pour la connexion à la base de données
require_once __DIR__ . '/../config/config.php';
try {
    // Connexion à la base de données en utilisant les variables définies dans config.php
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
