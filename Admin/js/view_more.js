document.addEventListener("DOMContentLoaded", function () {
  // Fonction pour basculer la visibilité d'un conteneur spécifique et stocker l'état
  function toggleVisibility(
    buttonId,
    containerId,
    showText,
    hideText,
    localStorageKey
  ) {
    const button = document.getElementById(buttonId);
    const container = document.getElementById(containerId);

    if (button && container) {
      // Récupérer l'état initial du conteneur dans le localStorage
      const storedState = localStorage.getItem(localStorageKey);
      const isHidden = storedState === "false" ? false : true; // null ou "true" signifie caché
      container.style.display = isHidden ? "none" : "block"; // Restaurer l'état
      button.textContent = isHidden ? showText : hideText;

      button.addEventListener("click", function () {
        const isHiddenNow = container.style.display === "none";
        container.style.display = isHiddenNow ? "block" : "none";
        button.textContent = isHiddenNow ? hideText : showText;

        // Stocker l'état dans le localStorage
        localStorage.setItem(localStorageKey, isHiddenNow);
      });
    } else {
      console.error(
        `Element with ID "${buttonId}" or "${containerId}" not found.`
      );
    }
  }

  // Appliquer la fonction pour chaque section
  toggleVisibility(
    "viewMoreCustomers",
    "customersContainer",
    "Voir Plus",
    "Voir Moins",
    "customersVisibility"
  );
  toggleVisibility(
    "viewMoreProducts",
    "productsContainer",
    "Voir Plus",
    "Voir Moins",
    "productsVisibility"
  );
  toggleVisibility(
    "viewMoreReservations",
    "reservationsContainer",
    "Voir Plus",
    "Voir Moins",
    "reservationsVisibility"
  );
});

// Erreur les contenaire ce referme automatiquement lors de la selection dune page
// Pour le débogage,voir quand l'état est sauvegardé ou restauré
// Test :  Cela affichera dans la console les états stockés dans le localStorage et m'aidera à comprendre s’ils sont correctement restaurés.
console.log(
  "customersVisibility:",
  localStorage.getItem("customersVisibility")
);
console.log("productsVisibility:", localStorage.getItem("productsVisibility"));
console.log(
  "reservationsVisibility:",
  localStorage.getItem("reservationsVisibility")
);
// Resultat
// customersVisibility: false    =============> L'état du conteneur "customersContainer" est bien stocké dans localStorage mais sa valeur est false, ce qui signifie que le conteneur est fermé.
// view_more.js:64 productsVisibility: null
// view_more.js:65 reservationsVisibility: null ========================> Les états des conteneurs "productsContainer" et "reservationsContainer" ne sont pas encore stockés dans le localStorage, car leurs valeurs sont null.
