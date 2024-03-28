let updateRecipe = document.querySelectorAll(".updateRecipe");

updateRecipe.forEach((recipe) => {
  recipe.addEventListener("click", (e) => {
    let idRecipe = recipe.dataset.id;
    let updateLocation = "food/update/view/" + idRecipe;
    window.location.href = updateLocation;
  });
});
