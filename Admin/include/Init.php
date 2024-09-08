<?php
echo "Inclure Init.php<br>";
include_once __DIR__ . '/Connect.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclure la connexion à la base de données
// include_once __DIR__ . '/../Connect.php';