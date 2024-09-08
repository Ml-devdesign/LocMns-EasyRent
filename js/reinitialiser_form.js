document.addEventListener("DOMContentLoaded", function () {
  var modal = document.getElementById("modalReinitialiser");
  var modalBody = document.getElementById("modalBody");
  var span = document.getElementsByClassName("close")[0];

  document
    .getElementById("reinitialiser")
    .addEventListener("click", function (e) {
      e.preventDefault();
      fetch("/Include/Reinitialiser_form.php")
        .then((response) => response.text())
        .then((data) => {
          modalBody.innerHTML = data;
          modal.style.display = "block";

          // Ajouter l'écouteur d'événement pour le formulaire de réinitialisation
          document
            .getElementById("resetForm")
            .addEventListener("submit", function (e) {
              e.preventDefault();

              var email = document.getElementById("email").value;
              var formData = new FormData();
              formData.append("email", email);

              fetch("/Include/Reinitialiser_form.php", {
                method: "POST",
                body: formData,
              })
                .then((response) => response.json())
                .then((data) => {
                  var responseMessage =
                    document.getElementById("responseMessage");
                  if (data.success) {
                    responseMessage.innerHTML =
                      '<p class="success">' + data.message + "</p>";
                  } else {
                    responseMessage.innerHTML =
                      '<p class="error">' + data.message + "</p>";
                  }
                })
                .catch((error) => {
                  console.error("Erreur:", error);
                });
            });
        })
        .catch((error) => console.error("Erreur:", error));
    });

  span.onclick = function () {
    modal.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };
});