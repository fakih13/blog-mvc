const btnIngredients = document.querySelectorAll(".btnIngredient");

btnIngredients.forEach((btn) => {
  btn.addEventListener("click", async (e) => {
    e.preventDefault();
    const idIngredient = btn.dataset.ingredient;
    const id = btn.dataset.recipe;

    try {
      const response = await fetch(`${id}/removeIngredient/${idIngredient}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
      });

      if (!response.ok) {
        throw new Error("La suppression a échoué.");
      }
      console.log(response);
      const data = await response.json();
      console.log(data);
    } catch (error) {
      console.error("Une erreur s'est produite :", error.message);
    }
  });
});
