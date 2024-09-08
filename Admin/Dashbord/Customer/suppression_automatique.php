<?php

// suppression_automatique.php
require_once __DIR__ . "/../../include/Connect.php";

// Date limite : il y a deux ans
$twoYearsAgo = date('Y-m-d H:i:s', strtotime('-2 years'));

// Requête SQL pour supprimer les clients inactifs
$sql = "DELETE FROM customer WHERE deletion_date IS NOT NULL AND deletion_date <= :twoYearsAgo";
$stmt = $db->prepare($sql);
$stmt->bindParam(':twoYearsAgo', $twoYearsAgo);
$stmt->execute();

echo "Clients inactifs supprimés.";

// Options pour exécuter une tâche récurrente en local :
// Utilisation de Windows Task Scheduler (Planificateur de tâches Windows) : Sous Windows, vous pouvez utiliser le "Planificateur de tâches" pour exécuter un script PHP à intervalle régulier, similaire à un cron job sous Linux.

// 1. Utilisation de Windows Task Scheduler (Planificateur de tâches Windows)
// Voici les étapes pour configurer une tâche récurrente sur votre machine locale Windows avec WAMP.

// Étape 1 : Créez votre script PHP
// Créez un script PHP que vous souhaitez exécuter périodiquement. Par exemple, un script qui supprime les clients inactifs après 2 ans :

// Étape 2 : Ouvrez le Planificateur de tâches Windows
// Ouvrez le Planificateur de tâches Windows en tapant "Planificateur de tâches" dans le menu Démarrer de Windows.
// Cliquez sur "Créer une tâche" dans le panneau de droite.
// Étape 3 : Configurer la tâche
// Général :

// Donnez un nom à la tâche, par exemple, "Suppression des clients inactifs".
// Cochez "Exécuter même si l'utilisateur n'est pas connecté" pour que la tâche fonctionne en arrière-plan.
// Déclencheurs :

// Cliquez sur "Nouveau" pour ajouter un déclencheur.
// Sélectionnez "Tous les jours" ou une autre période selon la fréquence à laquelle vous souhaitez exécuter le script.
// Actions :

// Cliquez sur "Nouveau" pour ajouter une action.
// Sélectionnez "Démarrer un programme".
// Dans le champ "Programme/script", entrez le chemin de votre exécutable PHP. Par exemple :
// makefile
// Copier le code
// C:\wamp64\bin\php\php7.x.x\php.exe
// Dans le champ "Arguments", entrez le chemin vers votre script PHP :
// bash
// Copier le code
// C:/wamp64/www/votre_projet/suppression_automatique.php
// Conditions :

// Vous pouvez ajuster certaines conditions (comme l'exécution uniquement si l'ordinateur est allumé).
// Étape 4 : Testez la tâche
// Une fois que la tâche est créée, vous pouvez tester son bon fonctionnement en cliquant sur "Exécuter" dans le Planificateur de tâches.

// 2. Vérification et suppression dans votre code PHP
// Si vous ne souhaitez pas utiliser Windows Task Scheduler, vous pouvez ajouter un mécanisme qui vérifie régulièrement si des clients doivent être supprimés à chaque fois que le site est utilisé. Vous pouvez placer ce code dans un fichier global inclus dans chaque page (comme Connect.php ou header.php).

// Voici un exemple de vérification dans votre application :

// php
// Copier le code
// // suppression_auto.php (à inclure dans votre header.php ou autre fichier)
// require_once __DIR__ . "/../../include/Connect.php";

// // Date limite : il y a deux ans
// $twoYearsAgo = date('Y-m-d H:i:s', strtotime('-2 years'));

// // Requête SQL pour supprimer les clients inactifs
// $sql = "DELETE FROM customer WHERE deletion_date IS NOT NULL AND deletion_date <= :twoYearsAgo";
// $stmt = $db->prepare($sql);
// $stmt->bindParam(':twoYearsAgo', $twoYearsAgo);
// $stmt->execute();
// Cela sera exécuté chaque fois qu'une page est chargée. Cependant, cette méthode n'est pas la plus optimale car elle dépend de l'activité sur votre site pour déclencher la suppression, et cela peut ralentir les performances.

// Conclusion
// Pour un serveur de production : Un cron job est préférable, mais en local avec WAMP, vous pouvez utiliser Windows Task Scheduler pour exécuter un script automatiquement.
// Si vous voulez un fonctionnement minimal : Ajoutez la logique de suppression directement dans votre code PHP, mais cela peut être moins efficace.


































// ccès aux outils comme crontab que vous utiliseriez sur un serveur Linux distant. 
// // Définir la date limite de 2 ans
// $twoYearsAgo = date('Y-m-d H:i:s', strtotime('-2 years'));

// // Sélectionner les clients inactifs dont la date de suppression est dépassée
// $sql = "SELECT Customer_id FROM customer WHERE deletion_date <= :twoYearsAgo";
// $stmt = $db->prepare($sql);
// $stmt->bindParam(':twoYearsAgo', $twoYearsAgo, PDO::PARAM_STR);
// $stmt->execute();
// $inactiveCustomers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// foreach ($inactiveCustomers as $customer) {
//     // Option 1: Pseudonymisation des données
//     $sqlUpdate = "UPDATE customer SET 
//                   Customer_firstName = 'Anonyme', 
//                   Customer_lastName = 'Anonyme', 
//                   Customer_email = 'anonyme@example.com', 
//                   Customer_phoneNum = NULL, 
//                   Customer_adress = NULL 
//                   WHERE Customer_id = :customer_id";
//     $stmtUpdate = $db->prepare($sqlUpdate);
//     $stmtUpdate->bindParam(':customer_id', $customer['Customer_id'], PDO::PARAM_INT);
//     $stmtUpdate->execute();

//     // Option 2: Suppression complète du client
//     // $sqlDelete = "DELETE FROM customer WHERE Customer_id = :customer_id";
//     // $stmtDelete = $db->prepare($sqlDelete);
//     // $stmtDelete->bindParam(':customer_id', $customer['Customer_id'], PDO::PARAM_INT);
//     // $stmtDelete->execute();
// }
?>
