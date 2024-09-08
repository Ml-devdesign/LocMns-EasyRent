<?php
require_once __DIR__ . "/../../include/Connect.php";
// include $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
require_once __DIR__ . "/../../include/Protect.php";
?>




<!-- Formulaire pour ajout et ou modification avec recuperation de donee via l'id   -->


    <!-- <?php var_dump($reservation)?> -->
    <h2><?php echo empty($reservation['Reservation_id']) ? 'Créer' : 'Modifier'; ?> une Reservation</h2>

    <form class="p container_Customer display_column" method="post" action="process.php">
        <input  type="hidden" name="Reservation_id" value="<?= htmlspecialchars(isset($reservation['Reservation_id']) ? $reservation['Reservation_id'] : '' )?>">

    <h1 class="">Modifier la Reservation <?=htmlspecialchars($reservation['Reservation_id']) . ' ' . htmlspecialchars($reservation['Reservation_id']) ?></h1>        
        <label>
            Reservation_id: <br>
            <input class="p glassmorphism" type="text" name="Reservation_id" value="<?= htmlspecialchars($reservation['Reservation_id']); ?>">
        </label>
       
        <br>
        <label>
            Reservation_startDate: <br>
            <input class="p glassmorphism" type="text" name="Reservation_startDate" value="<?= htmlspecialchars($reservation['Reservation_startDate']); ?>">
        </label>
        <br>
        <label>
            Reservation_endDate: <br>
            <input class="p glassmorphism" type="text" name="Reservation_endDate" value="<?= htmlspecialchars($reservation['Reservation_endDate']); ?>">
        </label>
        <br>
        <label>
            Reservation_type : <br>
            <input class="p glassmorphism" type="text" name="Reservation_type" value="<?= htmlspecialchars($reservation['Reservation_type']); ?>">
        </label>
        <br>
        <!-- <label>Image: <input class="glassmorphism display" type="text" name="Customer_image" value="<?= htmlspecialchars($reservation['Customer_image']); ?>"></label><br> -->
        <label>
            Reservation_received: <br>
            <input class="p glassmorphism" type="text" name="Reservation_received" value="<?= htmlspecialchars($reservation['Reservation_received']); ?>">
        </label>
        <br>
        <label>
            Reservation_accepted :<br> 
            <input class="p glassmorphism" type="text" name="Reservation_accepted" value="<?= htmlspecialchars($reservation['Reservation_accepted']); ?>">
        </label>
        <br>
        <label>
            Reservation_created : <br>
            <input class="p glassmorphism" type="text" name="Reservation_created" value="<?= htmlspecialchars($reservation['Reservation_created']); ?>">
        </label>
        <br>
        <div class="darkMode rond container_Customer">
        <label class="switch darkMode rond glassmorphism ">
            <input type="checkbox" checked name="toggle" value="<?= htmlspecialchars($reservation['Reservation_valided'])? 'checked': htmlspecialchars('Reservation_refused')?>">
            <span class="slider round"></span>
        </label>
        <h2>valide ou refusee</h2>
        </div>
        
      
        
        <button type="submit"><?= empty($reservation['Device_id']) ? 'Créer' : 'Mettre à jour'; ?></button>
    </form>

    <!-- Formulaire de suppression -->
    <form class="glassmorphism display" method="post" action="process.php">
        <h2>Supprimer unE reservation</h2>
        <input type="hidden" name="action" value="delete">
        <label>ID Reservation: <input type="text" name="Device_id"></label><br>
        <button type="submit">Supprimer</button>
    </form>

    <!-- Formulaire de lecture -->
    <form class="glassmorphism display" method="get" action="process.php">
        <h2>Lire une Reservation</h2>
        <input type="hidden" name="action" value="read">
        <label>ID Reservation: <input type="text" name="Device_id"></label><br>
        <button type="submit">Lire</button>
    </form>

