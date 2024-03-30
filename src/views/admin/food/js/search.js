let ingredientInput = document.getElementById("ingredientSearch");

let resultSearch = document.getElementById("resultSearch");

let containerIngredient = document.getElementById("containerIngredient");

const containerElement = document.getElementById("myForm");

ingredientInput.addEventListener("keypress", function (event) {
  if (event.key === "Enter") {
    event.preventDefault();

    let ingredient = ingredientInput.value;
    if (ingredient.length > 0) {
      ingredientInput.value = "";
      resultSearch.style.display = "none";
      console.log(ingredient);
      addIngredientToContainer(
        ingredient,
        containerIngredient,
        containerElement
      );
    }
  }
});

ingredientInput.addEventListener("input", function () {
  searchIngredientInSql(resultSearch, ingredientInput);
});

function searchIngredientInSql(resultSearch, ingredientInput) {
  let query = ingredientInput.value;

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
              addIngredientToContainer(
                item.Nom,
                containerIngredient,
                containerElement
              );
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

let counter = 0; // Pour générer des noms uniques pour chaque champ d'ingrédient

function addIngredientToContainer(
  newIngredient,
  containerIngredient,
  containerElement
) {
  let ingredientsInContainer = document.querySelectorAll(".ingredientRecipes");
  let isAlreadyAdded = false;
  ingredientsInContainer.forEach((ingredient) => {
    if (ingredient.value.toLowerCase() === newIngredient.toLowerCase()) {
      isAlreadyAdded = true;
    }
  });

  if (isAlreadyAdded) {
    const alertBanner = document.createElement("div");
    alertBanner.classList.add(
      "alert",
      "alert-warning",
      "alert-dismissible",
      "fade",
      "show"
    );
    alertBanner.setAttribute("role", "alert");
    alertBanner.innerHTML = `
    Cet ingrédient est déjà présent dans le plat.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

    containerElement.insertBefore(alertBanner, containerElement.firstChild);

    // Si l'ingrédient est déjà présent, affichez un message à l'utilisateur ou effectuez toute autre action nécessaire.
    console.log("Cet ingrédient est déjà ajouté.");
    return;
  }

  // Créer un nom unique pour chaque champ d'ingrédient
  let ingredientNameAttribute = "ingredientRecipe_" + counter;

  // Incrémenter le compteur pour le prochain champ d'ingrédient
  counter++;

  // Créer le champ d'ingrédient avec le nom unique
  let containerAddIngredient = document.createElement("div");
  containerAddIngredient.classList.add("d-flex", "my-2");

  let inregredientElement = document.createElement("input");
  inregredientElement.setAttribute("type", "text");
  inregredientElement.setAttribute("readonly", "");
  inregredientElement.classList.add("ingredientRecipes", "mx-2");
  inregredientElement.setAttribute("name", ingredientNameAttribute);
  inregredientElement.setAttribute("value", newIngredient.toLowerCase());

  let removeButtonIngredient = document.createElement("button");
  removeButtonIngredient.setAttribute("type", "button");
  removeButtonIngredient.classList.add(
    "removeButtonIngredient",
    "btn",
    "btn-danger"
  );
  removeButtonIngredient.textContent = "X";

  // Ajouter les éléments créés au conteneur
  containerAddIngredient.appendChild(inregredientElement);
  containerAddIngredient.appendChild(removeButtonIngredient);

  // Ajouter le conteneur à #containerIngredient
  containerIngredient.appendChild(containerAddIngredient);

  // Ajouter un écouteur d'événements pour le bouton de suppression
  removeButtonIngredient.addEventListener("click", function (e) {
    e.preventDefault();
    // Supprimer l'élément parent du bouton (le div conteneur)
    containerIngredient.removeChild(containerAddIngredient);
  });
}

document.addEventListener("click", function (event) {
  const ingredientSearch = document.getElementById("ingredientSearch");
  const resultContainer = document.getElementById("resultSearch");

  if (
    !ingredientSearch.contains(event.target) &&
    !resultContainer.contains(event.target)
  ) {
    resultContainer.style.display = "none";
  }
});
