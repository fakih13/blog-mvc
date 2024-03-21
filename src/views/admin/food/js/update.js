const btnIngredients = document.querySelectorAll(".btnIngredient");

const btnUpdate = document.getElementById("btnUpdate");

btnIngredients.forEach((btn) => {
  btn.addEventListener("click", async (e) => {
    e.preventDefault();
    const idIngredient = btn.dataset.idingredient;
    const idRecipe = btn.dataset.recipe;

    console.log(idIngredient);
    console.log(idRecipe);

    try {
      const response = await fetch(
        `../removeIngredient/${idRecipe}/${idIngredient}`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
        }
      );

      if (!response.ok) {
        throw new Error("La suppression a échoué.");
      }

      const data = await response.json();

      if (data.success) {
        // Créer la bannière d'alerte Bootstrap
        const alertBanner = document.createElement("div");
        alertBanner.classList.add(
          "alert",
          "alert-success",
          "alert-dismissible",
          "fade",
          "show"
        );
        alertBanner.setAttribute("role", "alert");
        alertBanner.innerHTML = `
          L'ingrédient a été supprimé.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Insérer la bannière d'alerte au début de la balise main
        const formElement = btn.closest("form");
        formElement.insertBefore(alertBanner, formElement.firstChild);

        // Supprimer le parent du bouton du DOM
        btn.parentNode.remove();
      } else {
        console.error("La suppression a échoué.");
      }
    } catch (error) {
      console.error("Une erreur s'est produite :", error.message);
    }
  });
});

const theIdRecipe = document.getElementById("idRecipe");

btnUpdate.addEventListener("click", async (e) => {
  e.preventDefault();
  const formData = new FormData(document.getElementById("myForm"));
  const excludeFields = document.querySelectorAll(
    '[data-existingIngredientInSql="true"]'
  );
  excludeFields.forEach((field) => {
    // Récupérer le nom du champ
    const fieldName = field.getAttribute("name");
    // Supprimer le champ du formData
    formData.delete(fieldName);
  });
  try {
    const response = await fetch(`../database/${theIdRecipe}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams(formData),
    });

    if (!response.ok) {
      throw new Error("La modification a échoué.");
    }

    const data = await response.json();
    console.log(data);
  } catch (error) {
    console.error("Une erreur s'est produite :", error.message);
  }
});
