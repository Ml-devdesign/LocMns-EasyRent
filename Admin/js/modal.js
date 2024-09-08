//Login.php
document.addEventListener("DOMContentLoaded", function () {
  // Ajoute un écouteur sur le bouton d'ouverture du modal
  document.querySelectorAll(".open-modal-btn").forEach((button) => {
    button.addEventListener("click", function () {
      // Récupère l'ID du client à partir de l'attribut data-id
      const customerId = this.getAttribute("data-id");
      console.log("customerId ", customerId);

      // Récupère le formulaire de la modale
      // Affichage de la modale
      document.getElementById("modal-" + customerId).style.display = "block";

      // Ajouter l'ID du client au champ caché dans le formulaire dans la modale
      const modalForm = document.querySelector(
        "#modal-" + customerId + " form"
      );
      modalForm.querySelector("input[name='Customer_id']").value = customerId;
    });
  });
  // Ajoute des écouteurs sur les boutons de fermeture du modal
  document.querySelectorAll(".close-modal-btn").forEach((button) => {
    button.addEventListener("click", function () {
      this.closest(".modal-overlay").style.display = "none";
    });
  });

  // Ajoute des écouteurs sur les modals pour fermer lors d'un clic à l'extérieur
  document.querySelectorAll(".modal-overlay").forEach((modal) => {
    modal.addEventListener("click", function (e) {
      if (e.target === this) {
        this.style.display = "none";
      }
    });
  });
});
