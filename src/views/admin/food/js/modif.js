function searchIngredientInSql(event, resultSearch, ingredientInput) {
  let query = this.value;

  if (query.length > 2) {
    fetch("/searchIngredient/" + query)
      .then((response) => response.json())
      .then((data) => {
        resultSearch.innerHTML = ""; // Effacer les résultats précédents

        if (data.length > 0) {
          resultSearch.style.display = "block";
          resultSearch.setAttribute("class", "border mt-2");
          // Ajouter les résultats au #resultSearch
          data.forEach((item) => {
            let resultItem = document.createElement("div");
            resultItem.setAttribute(
              "class",
              "mx-auto p-2 border-bottom pe-auto"
            );
            resultItem.textContent = item.Nom;

            // Ajouter un écouteur d'événements pour le clic sur le résultat
            resultItem.addEventListener("click", function () {
              addIngredientToContainer(item.Nom);
              resultSearch.style.display = "none";
              ingredientInput.value = "";
            });

            resultSearch.appendChild(resultItem);
          });
        } else {
          resultSearch.innerHTML = "";
          resultSearch.style.display = "none";
        }
      });
  } else {
    resultSearch.style.display = "none";
  }
}
