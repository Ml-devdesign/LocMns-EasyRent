<?php
// Assurez-vous que le fichier de connexion est correctement inclus
require_once __DIR__ . "/Include/Connect.php"; // Le fichier doit définir la variable $db

$emailError = false;
$passwordError = false;
$loginSuccess = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    session_start();
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Rechercher l'utilisateur dans la table customer
    $sql = "SELECT * FROM customer WHERE Customer_email = :email";
    $stmt = $db->prepare($sql);
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch();

    if ($row) {
        // Vérification de l'authentification : password_verify()
        if (password_verify($password, $row['Customer_password'])) {
            $token = bin2hex(random_bytes(32));
            $_SESSION['token'] = $token;

            // Mettre à jour le token de l'utilisateur dans la base de données
            $sql = "UPDATE customer SET token = :token WHERE Customer_id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':token' => $token, ':id' => $row['Customer_id']]);

            // Initialiser les variables de session
            $_SESSION['user_connected'] = "ok";
            $_SESSION["user_id"] = $row['Customer_id'];

            if ($row['Admin_booleen'] == true) {
                $_SESSION["role"] = 'admin';
                $_SESSION["authorization"] = 'admin';
                header("Location:/../Admin/index.php");
                exit();
            } else {
                $_SESSION["role"] = 'customer';
                $_SESSION["authorization"] = $row['Authorisation_id'];
                header("Location:/../Customer/index.php");
                exit();
            }
        } else {
            $passwordError = true; // Le mot de passe est incorrect
        }
    } else {
        $emailError = true; // L'adresse e-mail est incorrecte
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="/Css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<!-- Script du modal.js-->
  <script src="/js/modal.js"></script>
</head>
<body data-email-error="<?php echo $emailError ? 'true' : 'false'; ?>" data-password-error="<?php echo $passwordError ? 'true' : 'false'; ?>" data-login-success="<?php echo $loginSuccess ? 'true' : 'false'; ?>">  
    <!-- Header -->
    <header>
        <?php include_once './Include/Header.php'; ?>  
    </header>

    <!-- Contenu principal -->
    <div class="main-content">
        <div class="aside flex-container">
            <img class="img_login" src="img/atlasFaranes.jpg" alt="Atlas Faranes">
            <form id="loginForm" method="post" action="">
                <h1 class="lato-light-italic">Connexion</h1>
                <div class="txt_field <?php echo $emailError ? 'error' : ''; ?>">
                    <input type="email" id="email" name="email" required aria-required="true" aria-label="Adresse e-mail" class="<?php echo $emailError ? 'error' : ''; ?>">
                    <span></span>
                    <label for="email" class="<?php echo $emailError ? 'error' : ''; ?>"><?php echo $emailError ? 'Adresse e-mail incorrecte' : 'Saisissez une adresse e-mail :'; ?></label>
                </div>
                <div class="txt_field <?php echo $passwordError ? 'error' : ''; ?>">
                    <input type="password" id="password" name="password" required aria-required="true" aria-label="Mot de Passe" class="<?php echo $passwordError ? 'error' : ''; ?>">
                    <span></span>
                    <label for="password" class="<?php echo $passwordError ? 'error' : ''; ?>"><?php echo $passwordError ? 'Mot de passe incorrect' : 'Mot de Passe :'; ?></label>
                </div>
                <input id="modal-submit" class="column-6" name="submit" type="submit" value="Login">
                <div class="modal-wrapper open-modal-btn-wrapper signup_link signup_link-Reinitialiser">
                    <span></span>
                    <label>Réinitialiser le mot de passe ?</label>
                    <a id="reinitialiser" data-target="modalReinitialiser" href="#">Réinitialiser</a>
                </div>
                <div class="signup_link signup_link-Creez">
                    <span></span>
                    <label>Pas de compte ? Contactez l'administrateur!</label>
                     <a id="contactBtn" href="#">Contact</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Reinitialiser-->
   <div id="modalReinitialiser" class="modal-overlay">
        <div class="modal-wrapper">
            <div class="modal-content">
                <div class="close-btn-wrapper">
                    <span class="close">&times;</span>
                </div>
                <div id="modalBody"></div>
            </div>
        </div>
    </div>
    
    <!-- Modal Contact-->
<div id="modalContact" class="modal-overlay">
    <div class="modal-wrapper">
        <div class="modal-content">
            <div class="close-btn-wrapper">
                <span class="close">&times;</span>
            </div>
            <div id="modalBody">
                <?php include './Include/Contact.php'; ?>
            </div>
        </div>
    </div>
</div>
    

    <!-- Footer -->
    <footer>
        <?php include_once './Include/Footer.php'; ?>  
    </footer>

    <script src="/js/login.js"></script>
    <script src="/js/reinitialiser_form.js"></script>
    <script src="/js/contact.js"></script>
</body>
</html>
