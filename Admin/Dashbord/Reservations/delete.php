<?php
include $_SERVER["DOCUMENT_ROOT"]."/Includes/Protect.php";
require_once __DIR__ . '/../../Config/Database.php';
var_dump($_POST);
exit;
// Delete the customer from the database and remove the customer from the database table
if(isset($_POST['id']) && $_POST['id'] > 0) {
    if(isset($_POST['checkbox']) && $_POST['checkbox'] == 1) {
        if(isset($_POST['checkboxDeleteAll']) && $_POST['checkboxDeleteAll'] == 1) {

            // Prepare the SQL statement to delete the customer from the database table customer
            $sql = "DELETE FROM devis WHERE Device_id = :device_id";

            // Execute the SQL statement to delete the customer from the database table customer
            $stmt = $db->prepare($sql);

            // Bind the customer ID to the SQL statement
            $stmt->bindParam(':device_id',$_G_POST['id'], PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect the user to the dashboard page after deleting the customer from the database table
                header('Location:dashboard_ad.php');
                // Exit the script after redirecting the user to the dashboard page
                exit();
            } else { 
                // Handle the error if the deletion fails
                echo "Error deleting product.";
            }
        }
    } 
} else {
    // Handle the case where 'id' is not set or invalid
    echo "Invalid product ID.";
}

