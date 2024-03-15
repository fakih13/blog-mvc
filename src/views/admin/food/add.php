<?php

ob_start();
?>

<h1>Plats Pti' Riad</h1>

<p>ajouter</p>


<?php if (!empty($errors)) : ?>
  <div>
    <?php foreach ($errors as $error) : ?>
      <div class="alert alert-danger" role="alert"><?= $error ?></div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>



<form action="" method="post" class="d-flex flex-column w-50" id="myForm">
  <label for="Nom">Nom du plat</label>
  <input type="text" name="Nom" id="Nom">
  <label for="Prix">Prix</label>
  <input type="number" step="0.01" name="Prix" id="Prix">
  <label for="Description">Description</label>
  <textarea name="Description" id="Description" cols="30" rows="10"></textarea>
  <label for="ingredient">Ingrédient</label>
  <input type="text" name="ingredientSearch" id="ingredientSearch">
  <div id="resultSearch"></div>
  <div id="containerIngredient"></div>
  <button type="submit" class="mt-3">Ajouter</button>
</form>


<script>
  /* document.querySelector('#myForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('ok')
  }); */

  let ingredientInput = document.getElementById("ingredientSearch")
  let resultSearch = document.getElementById("resultSearch");

  ingredientInput.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
      event.preventDefault();

      let test = ingredientInput.value
      if (test.length > 0) {
        ingredientInput.value = "";
        resultSearch.style.display = "none";
        addIngredientToContainer(test)
      }


    }
  });

  ingredientInput.addEventListener("input", function(event) {
    let query = this.value;

    if (query.length > 2) {
      fetch("/searchIngredient/" + query)
        .then((response) => response.json())
        .then((data) => {

          resultSearch.innerHTML = ""; // Effacer les résultats précédents

          if (data.length > 0) {
            resultSearch.style.display = "block";
            resultSearch.setAttribute('class', 'border mt-2')
            // Ajouter les résultats au #resultSearch
            data.forEach((item) => {
              let resultItem = document.createElement("div");
              resultItem.setAttribute('class', 'mx-auto p-2 border-bottom pe-auto')
              resultItem.textContent = item.Nom;

              // Ajouter un écouteur d'événements pour le clic sur le résultat
              resultItem.addEventListener("click", function() {
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
      document.getElementById("resultSearch").style.display = "none";
    }
  });


  let counter = 0; // Pour générer des noms uniques pour chaque champ d'ingrédient

  function addIngredientToContainer(ingredientName) {
    // Créer un nom unique pour chaque champ d'ingrédient
    let ingredientNameAttribute = "ingredientRecipe_" + counter;

    // Incrémenter le compteur pour le prochain champ d'ingrédient
    counter++;

    // Créer le champ d'ingrédient avec le nom unique
    let containerAddIngredient = document.createElement("div");
    let inregredientElement = document.createElement("input");
    inregredientElement.setAttribute("type", "text");
    inregredientElement.setAttribute("readonly", "");
    inregredientElement.setAttribute("class", "ingredientRecipes");
    inregredientElement.setAttribute("name", ingredientNameAttribute);
    inregredientElement.setAttribute("value", ingredientName.toLowerCase());

    let removeButtonIngredient = document.createElement("button");
    removeButtonIngredient.setAttribute("type", "button");
    removeButtonIngredient.setAttribute("class", "removeButtonIngredient");
    removeButtonIngredient.textContent = "X";

    // Ajouter les éléments créés au conteneur
    containerAddIngredient.appendChild(inregredientElement);
    containerAddIngredient.appendChild(removeButtonIngredient);

    // Ajouter le conteneur à #containerIngredient
    document.getElementById("containerIngredient").appendChild(containerAddIngredient);

    // Ajouter un écouteur d'événements pour le bouton de suppression
    removeButtonIngredient.addEventListener("click", function(e) {
      e.preventDefault();
      // Supprimer l'élément parent du bouton (le div conteneur)
      document.getElementById("containerIngredient").removeChild(containerAddIngredient);
    });
  }





  document.addEventListener("click", function(event) {
    var ingredientSearch = document.getElementById("ingredientSearch");
    var resultContainer = document.getElementById("resultSearch");

    if (
      !ingredientSearch.contains(event.target) &&
      !resultContainer.contains(event.target)
    ) {
      resultContainer.style.display = "none";
    }
  });
</script>
<?php

$content = ob_get_clean();
require_once('../src/views/admin/layout.php');
