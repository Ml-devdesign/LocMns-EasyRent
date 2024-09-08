document.addEventListener("DOMContentLoaded", function () {
  // Fonction générique pour ouvrir un modal
  function openModal(modalType, id) {
    const modal = document.getElementById(`${modalType}-modal-${id}`);
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

  // Ajoute un écouteur sur les boutons d'ouverture du modal (Customer, Product, Reservation)
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
});
