const removeRecipe = document.querySelectorAll(".removeRecipe");
let modalBody = document.getElementById("modalBody");
let deleteButtonModal = document.getElementById("deleteButtonModale");
let deleteModal = document.getElementById("deleteModal");

removeRecipe.forEach((recipe) => {
  recipe.addEventListener("click", (event) => {
    // Récupérer l'élément enfant du parent de removeRecipe
    let childElement = recipe.parentNode.querySelector(".nomRecipe");

    // Ajouter le contenu de l'élément enfant à modalBody
    modalBody.textContent = childElement.textContent;
  });
});

// Ajoutez un écouteur d'événements pour le bouton de suppression dans la modale
deleteButtonModal.addEventListener("click", () => {
  console.log("Bouton Supprimer cliqué");
});

// Vider le contenu de modalBody lorsque la modale est fermée
deleteModal.addEventListener("hidden.bs.modal", function (event) {
  modalBody.textContent = "";
});
