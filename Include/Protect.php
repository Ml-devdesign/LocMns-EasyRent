<!-- index.php : fichier principal de votre site web, qui est chargé lorsque les visiteurs accèdent à la racine de votre site. -->

<?php  // est connecté en vérifiant si la variable de session user_connected est définie à "ok". 
   
if (session_status() === PHP_SESSION_NONE) {// vérifie si une session est déjà démarrée avant d'appeler session_start()
    session_start();//n'as besoin d'etre present q'une seul fois dans les diff PHP 

    if (!isset($_SESSION["user_connected"]) || $_SESSION["user_connected"] != "ok") {
    // est connecté en vérifiant si la variable de session user_connected est définie à "ok". 
//Si la negation isset la variable est vrais(existente) OU le variable est vrais(existente) alors si les deux sont vrais(existente) et s elle contienne -> ok alors 
    header("Location:login.php");
    //Si ce n'est pas le cas, il redirigera l'utilisateur vers la page de connexion.
    exit(); // Il est bon de sortir du script après une redirection
        
}
}

