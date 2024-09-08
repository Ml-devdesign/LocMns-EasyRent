document.addEventListener("DOMContentLoaded", function () {
  // Gestion du modal de contact
  var contactModal = document.getElementById("modalContact");
  var contactBtn = document.getElementById("contactBtn");
  var contactClose = contactModal.getElementsByClassName("close")[0];

  contactBtn.addEventListener("click", function (e) {
    e.preventDefault();
    contactModal.style.display = "block";
  });

  contactClose.onclick = function () {
    contactModal.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target == contactModal) {
      contactModal.style.display = "none";
    }
  };

  // Gestion du modal de réinitialisation du mot de passe
  var resetModal = document.getElementById("modalReinitialiser");
  var resetBtn = document.getElementById("reinitialiser");
  var resetClose = resetModal.getElementsByClassName("close")[0];

  resetBtn.addEventListener("click", function (e) {
    e.preventDefault();
    resetModal.style.display = "block";
  });

  resetClose.onclick = function () {
    resetModal.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target == resetModal) {
      resetModal.style.display = "none";
    }
  };
});
