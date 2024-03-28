const methodInput = document.querySelector("#method");

methodInput.addEventListener("change", handleMethodChange);

function handleMethodChange() {
  const selectedMethod = methodInput.value;

  if (selectedMethod === "code") {
    addCouponCodeInput(methodInput);
  } else {
    removeCouponCodeInput();
  }
}

function addCouponCodeInput(methodInput) {
  const couponCodeInput = document.createElement("input");
  couponCodeInput.setAttribute("type", "text");
  couponCodeInput.setAttribute("id", "couponCodeInput");
  couponCodeInput.setAttribute("placeholder", "Code de réduction");

  methodInput.insertAdjacentElement("afterend", couponCodeInput);
}

function removeCouponCodeInput() {
  const couponCodeInput = document.getElementById("couponCodeInput");
  if (couponCodeInput) {
    couponCodeInput.remove();
  }
}

const targetType = document.querySelector("#tagetType");

let selectedTarget;

targetType?.addEventListener("change", function () {
  selectedTarget = targetType.value;
});

let resultContainer = document.getElementById("resultContainer");

const targetSearch = document.querySelector("#targetSearch");

targetSearch?.addEventListener("input", function () {
  searchTargetinSql(selectedTarget, targetSearch, resultContainer);
});

function searchTargetinSql(selectedTarget, targetSearch, resultContainer) {
  let query = targetSearch.value;

  if (query.length > 2) {
    fetch("/searchTarget/" + selectedTarget + "/" + query)
      .then((response) => response.json())
      .then((data) => {
        resultContainer.innerHTML = "";
        if (data.length > 0) {
          resultContainer.style.display = "block";
          resultContainer.setAttribute("class", "border mt-2");
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
              resultContainer.style.display = "none";
              targetSearch.value = "";
            });

            resultContainer.appendChild(resultItem);
          });
        } else {
          resultContainer.innerHTML = "";
          resultContainer.style.display = "none";
        }
      });
  }
}
