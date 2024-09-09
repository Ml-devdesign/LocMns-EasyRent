document.addEventListener("DOMContentLoaded", function () {
  // Fonction générique pour ouvrir un modal
  function openModal(modalType, id) {
    const modal = document.getElementById(`${modalType}-modal-${id}`);

    // Si le type est 'delete', ouvrir le modal de suppression générique
    if (modalType === "delete") {
      const deleteModal = document.getElementById("delete-modal"); // Récupère le modal de confirmation de suppression
      if (deleteModal) {
        document.getElementById("delete-id").value = id; // Définit l'ID du client dans le formulaire du modal
        deleteModal.style.display = "block"; // Affiche le modal de confirmation de suppression
      } else {
        console.error("Modale de suppression introuvable.");
      }
      return; // On arrête ici pour ne pas continuer le traitement des autres types
    }

    // Si le modal est trouvé, l'afficher
    if (modal) {
      modal.style.display = "block";
      const modalForm = modal.querySelector("form");
      if (modalForm) {
        const hiddenInput = modalForm.querySelector(
          `input[name='${modalType}_id']`
        );
        if (hiddenInput) {
          hiddenInput.value = id;
        }
      }
    } else {
      console.error(`Modale avec ID ${modalType}-modal-${id} introuvable.`);
    }
  }

  // Ajoute un écouteur sur les boutons d'ouverture du modal (Customer, Product, Reservation, Delete)
  document.querySelectorAll("[data-type]").forEach((button) => {
    button.addEventListener("click", function () {
      const modalType = this.getAttribute("data-type");
      const id = this.getAttribute("data-id");
      openModal(modalType, id);
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

  // Ouverture du modal de confirmation de suppression
  document.querySelectorAll(".open-delete-modal").forEach((button) => {
    button.addEventListener("click", function () {
      const customerId = this.getAttribute("data-id");
      document.getElementById("delete-id").value = customerId; // Définit l'ID du client dans le modal
      document.getElementById("delete-modal").style.display = "block"; // Affiche le modal de confirmation
    });
  });

  // Fermeture du modal de suppression spécifique avec le bouton 'Annuler'
  document.querySelector(".close-modal").addEventListener("click", function () {
    document.getElementById("delete-modal").style.display = "none"; // Cache le modal de suppression
  });
});
