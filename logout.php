<?php
session_start();

require_once __DIR__ . "/Include/Connect.php";

if (isset($_SESSION['user_id'])) {
    $sql = "UPDATE customer SET token = NULL WHERE Customer_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $_SESSION['user_id']]);

    session_unset();    // Vide toutes les variables de session
    session_destroy();  // Détruit la session

    header("Location: /login.php");
    exit();
}
