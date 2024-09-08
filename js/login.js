// Fonction pour échapper les chaînes de caractères
function escapeHTML(str) {
  // Fonction pour échapper les chaînes de caractères spéciales en HTML pour éviter les injections XSS.
  return str.replace(/[&<>'"]/g, function (tag) {
    const charsToReplace = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      "'": "&#39;",
      '"': "&quot;",
    };
    return charsToReplace[tag] || tag;
  });
}

// Ajoute un écouteur d'événement pour le clic sur le bouton de soumission (identifié par l'ID "submit")
document.querySelector("#submit").addEventListener("click", (e) => {
  // Empêche le comportement par défaut du formulaire, c'est-à-dire de recharger la page
  e.preventDefault();

  // Récupère la valeur saisie dans le champ email (identifié par l'ID "email")
  const email = document.querySelector("#email").value;
  // Récupère la valeur saisie dans le champ mot de passe (identifié par l'ID "password")
  const password = document.querySelector("#password").value;

  // Envoie une requête HTTP POST à l'URL "/login" avec les options spécifiées
  fetch("/login", {
    // Envoie une requête HTTP POST à l'URL /login avec les options définies (méthode, en-têtes, et corps de la requête)
    method: "POST", // Utilise la méthode HTTP POST
    headers: { "Content-Type": "application/json" }, // Indique que le corps de la requête est au format JSON
    body: JSON.stringify({ email, password }), // Convertit l'objet contenant email et mot de passe en chaîne JSON pour l'envoyer dans le corps de la requête
  })
    .then((res) => res.json()) // Convertit la réponse en JSON
    .then((data) => {
      // Vérifie si la réponse indique un succès
      if (data.success) {
        // Si succès, redirige vers la page d'accueil
        window.location.href = "/";
      } else {
        // Sinon, affiche l'élément avec l'ID "pass" (probablement un message d'erreur)
        document.querySelector("#pass").style.display = "block";
        document.querySelector("#pass").textContent = escapeHTML(
          data.message || "Erreur de connexion"
        );
      }
    })
    .catch((err) => {
      console.error("Erreur lors de la requête:", err);
      document.querySelector("#pass").style.display = "block";
      document.querySelector("#pass").textContent =
        "Une erreur est survenue. Veuillez réessayer plus tard.";
    });
});

document.addEventListener("DOMContentLoaded", function () {
  var emailError = document.body.getAttribute("data-email-error");
  var passwordError = document.body.getAttribute("data-password-error");
  var loginSuccess = document.body.getAttribute("data-login-success");

  if (emailError === "true") {
    document.querySelector("#email").classList.add("error");
  }

  if (passwordError === "true") {
    document.querySelector("#password").classList.add("error");
  }

  if (loginSuccess === "true") {
    showModal("Connexion réussie !");
  } else if (emailError === "true" || passwordError === "true") {
    showModal("Erreur de connexion. Veuillez vérifier vos informations.");
  }
});

function showModal(message) {
  var modal = document.getElementById("loginModal");
  var span = document.getElementsByClassName("close")[0];
  document.getElementById("modalMessage").textContent = message;
  modal.style.display = "block";

  span.onclick = function() {
    modal.style.display = "none";
  }

  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
}