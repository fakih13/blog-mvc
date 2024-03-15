document
  .getElementById("ingredientSearch")
  .addEventListener("keyup", function (event) {
    if (event.key.match(/^[\w\s]+$/)) {
      let query = this.value;

      //console.log(query);
      if (query.length > 2) {
        // Par exemple, commencer la recherche après 2 caractères
        fetch("/search/" + query)
          .then((response) => response.json())
          .then((data) => {
            console.log(data);
            if (data.length > 0) {
              document.getElementById("resultSearch").style.display = "block";
              document.getElementById("resultSearch").innerHTML = data
                .map((item) => `<a href="/${item.URL_Anchor}">${item.Name}</a>`)
                .join("");
            } else {
              document.getElementById("resultSearch").style.display = "none";
            }
          });
      } else {
        document.getElementById("resultSearch").style.display = "none";
      }
    }
  });

document.addEventListener("click", function (event) {
  var searchBar = document.getElementById("search");
  var resultContainer = document.getElementById("resultSearch");

  if (
    !searchBar.contains(event.target) &&
    !resultContainer.contains(event.target)
  ) {
    resultContainer.style.display = "none";
  }
});
