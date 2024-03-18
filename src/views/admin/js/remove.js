const removeRecipe = document.querySelectorAll(".removeRecipe");
let modalBody = document.getElementById("modalBody");
let deleteButtonModal = document.getElementById("deleteButtonModale");
let deleteModal = document.getElementById("deleteModal");

removeRecipe.forEach((recipe) => {
  recipe.addEventListener("click", (event) => {
    // Récupérer l'élément enfant du parent de removeRecipe
    let childElement = recipe.parentNode.querySelector(".nomRecipe");
    let idRecipe = recipe.parentNode.querySelector(
      "input[name='idRecipe']"
    ).value;
    // Ajouter le contenu de l'élément enfant à modalBody
    modalBody.textContent =
      "Êtes-vous sur de vouloir supprimer " +
      childElement.textContent +
      " de la carte";
    console.log(idRecipe);
    deleteButtonModal.dataset.id = idRecipe;
  });
});

// Ajoutez un écouteur d'événements pour le bouton de suppression dans la modale
/* deleteButtonModal.addEventListener("click", () => {
  let idRecipe = deleteButtonModal.dataset.id;
  window.location.href = "supprimer/" + idRecipe;
}); */

deleteButtonModal.addEventListener("click", () => {
  let idRecipe = deleteButtonModal.dataset.id;
  let modalFooter = document.getElementById("modalFooter");
  let btnCancel = document.getElementById("btnCancel");

  deleteButtonModal?.setAttribute("hidden", "");
  btnCancel.setAttribute("hidden", "");

  let spinner = document.createElement("div");
  spinner.setAttribute("class", "spinner-border");
  spinner.setAttribute("role", "status");

  let spanSpinner = document.createElement("span");
  spanSpinner.setAttribute("class", "visually-hidden");
  spanSpinner.textContent = "Loading...";

  spinner.appendChild(spanSpinner);
  modalFooter.appendChild(spinner);

  // Création de la requête AJAX
  let xhr = new XMLHttpRequest();

  // Configuration de la requête
  xhr.open("POST", "food/supprimer", true); // Modifier "supprimer.php" avec l'URL de votre script PHP de suppression
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // Gestionnaire d'événement pour le chargement de la réponse
  xhr.onload = function () {
    if (xhr.status === 200) {
      // La suppression a réussi, vous pouvez gérer la réponse ici si nécessaire
      let response = JSON.parse(xhr.responseText);
      response.success = false;
      if (response.success == true) {
        setTimeout(function () {
          location.reload();
        }, 12000);
      } else {
        setTimeout(function () {
          deleteButtonModal?.removeAttribute("hidden");
          btnCancel?.removeAttribute("hidden");
          spinner.remove();
        }, 2000);
      }

      console.log(response.success);
      // Recharger la page ou effectuer d'autres actions si nécessaire
      //location.reload();
    } else {
      // La suppression a échoué, gérer l'erreur ici
      console.error("Erreur lors de la suppression de l'élément.");
    }
  };

  // Gestion des erreurs de connexion
  xhr.onerror = function () {
    console.error("Erreur lors de la connexion au serveur.");
  };

  // Envoi de la requête avec l'ID de la recette
  xhr.send("id=" + encodeURIComponent(idRecipe));
});

// Vider le contenu de modalBody lorsque la modale est fermée
deleteModal.addEventListener("hidden.bs.modal", function (event) {
  modalBody.textContent = "";
  deleteButtonModal.dataset.id = "";
});
