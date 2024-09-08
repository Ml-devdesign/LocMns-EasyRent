<?php
// Traitement du formulaire
$nameError = $emailError = $messageError = "";
$name = $email = $message = "";
$successMessage = $errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameError = "Le nom est requis";
    } else {
        $name = test_input($_POST["name"]);
    }

    if (empty($_POST["email"])) {
        $emailError = "L'adresse e-mail est requise";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Format d'adresse e-mail invalide";
        }
    }

    if (empty($_POST["message"])) {
        $messageError = "Le message est requis";
    } else {
        $message = test_input($_POST["message"]);
    }

    if (empty($nameError) && empty($emailError) && empty($messageError)) {
        $to = "admin@soutenanceeasyrent.com";
        $subject = "Nouveau message de contact de $name";
        $body = "Nom: $name\nE-mail: $email\n\nMessage:\n$message";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            $successMessage = "Votre message a été envoyé avec succès.";
        } else {
            $errorMessage = "Une erreur est survenue lors de l'envoi de votre message. Veuillez réessayer plus tard.";
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
$data = htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
    return $data;
}
?>

<form id="contactForm" method="post" action="">
    <h1 class="lato-light-italic">Contactez-nous</h1>
    <?php if (!empty($successMessage)): ?>
        <p class="success"><?php echo $successMessage; ?></p>
    <?php elseif (!empty($errorMessage)): ?>
        <p class="error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
    <div class="txt_field <?php echo $nameError ? 'error' : ''; ?>">
        <input type="text" id="name" name="name" required aria-required="true" aria-label="Nom" value="<?php echo $name; ?>">
        <span></span>
        <label for="name" class="<?php echo $nameError ? 'error' : ''; ?>"><?php echo $nameError ? $nameError : 'Nom :'; ?></label>
    </div>
    <div class="txt_field <?php echo $emailError ? 'error' : ''; ?>">
        <input type="email" id="email" name="email" required aria-required="true" aria-label="Adresse e-mail" value="<?php echo $email; ?>">
        <span></span>
        <label for="email" class="<?php echo $emailError ? 'error' : ''; ?>"><?php echo $emailError ? $emailError : 'Adresse e-mail :'; ?></label>
    </div>
    <div class="txt_field <?php echo $messageError ? 'error' : ''; ?>">
        <textarea id="message" name="message" required aria-required="true" aria-label="Message"><?php echo $message; ?></textarea>
        <span></span>
        <label for="message" class="<?php echo $messageError ? 'error' : ''; ?>"><?php echo $messageError ? $messageError : 'Message :'; ?></label>
    </div>
    <input class="column-6" name="submit" type="submit" value="Envoyer">
</form>