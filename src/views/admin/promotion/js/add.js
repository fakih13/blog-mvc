const methodInput = document.querySelector("#method");
const form = document.querySelector("form");
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
  couponCodeInput.setAttribute("name", "code");
  couponCodeInput.classList.add("mb-2");

  methodInput.insertAdjacentElement("afterend", couponCodeInput);
}

function removeCouponCodeInput() {
  const couponCodeInput = document.getElementById("couponCodeInput");
  if (couponCodeInput) {
    couponCodeInput.remove();
  }
}

const targetType = document.querySelector("#tagetType");

let selectedTarget = targetType.value;

targetType?.addEventListener("change", function () {
  selectedTarget = targetType.value;
});

let resultContainer = document.getElementById("resultContainer");

const targetSearch = document.querySelector("#targetSearch");

targetSearch?.addEventListener("input", function () {
  //console.log(selectedTarget);
  searchTargetinSql(selectedTarget, targetSearch, resultContainer);
});

function searchTargetinSql(selectedTarget, targetSearch, resultContainer) {
  let query = targetSearch.value;

  if (query.length > 2) {
    fetch(`/admin/promotion/searchTarget/${selectedTarget}/${query}`)
      .then((response) => response.json())
      .then((data) => {
        resultContainer.innerHTML = "";
        if (data.length > 0) {
          resultContainer.style.display = "block";
          resultContainer.setAttribute("class", "border mt-2");
          data.forEach((item) => {
            console.log(item);
            let resultItem = document.createElement("div");
            resultItem.setAttribute(
              "class",
              "mx-auto p-2 border-bottom pe-auto"
            );
            resultItem.textContent = item.Nom;

            // Ajouter un écouteur d'événements pour le clic sur le résultat
            resultItem.addEventListener("click", function () {
              const form = document.querySelector("form");
              addTargetToForm(item, form, targetSearch);
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

function addTargetToForm(target, form, targetSearch) {
  const targetExist = document.getElementById("target");

  if (targetExist) {
    form.removeChild(targetExist);
  }
  let containerTarget = document.createElement("div");

  containerTarget.classList.add("d-flex");
  containerTarget.setAttribute("id", "target");

  let spanTarget = document.createElement("span");
  spanTarget.textContent = target.Nom;

  let inputTarget = document.createElement("input");

  inputTarget.setAttribute("value", target.PlatID);
  inputTarget.setAttribute("name", "PlatID");
  inputTarget.setAttribute("hidden", "");

  let removeButton = document.createElement("button");
  removeButton.textContent = "X";

  containerTarget.appendChild(spanTarget);
  containerTarget.appendChild(removeButton);
  containerTarget.appendChild(inputTarget);

  targetSearch.insertAdjacentElement("afterend", containerTarget);

  removeButton.addEventListener("click", function (e) {
    e.preventDefault();
    form.removeChild(containerTarget);
  });
}

const checkBoxEnd = document.getElementById("addEnd");
const dateContainer = document.querySelector("#date");
checkBoxEnd?.addEventListener("change", function () {
  addEnd(checkBoxEnd, dateContainer);
});

function addEnd(checkbox, dateContainer) {
  let endDateIsTrue = document.getElementById("endDate");
  if (endDateIsTrue) {
    dateContainer.removeChild(endDateIsTrue);
  }
  if (checkbox.checked) {
    let endDate = document.createElement("input");
    endDate.setAttribute("type", "date");
    endDate.setAttribute("name", "end_date");
    endDate.setAttribute("id", "endDate");

    checkbox.insertAdjacentElement("afterend", endDate);
  }
}

form?.addEventListener("submit", async function (e) {
  e.preventDefault();

  const inputs = this.querySelectorAll("input, select");
  const postData = {}; // Utilisation d'un objet au lieu d'un tableau

  // Parcourir tous les inputs récupérés
  inputs.forEach(function (input) {
    if (input.name === "targetSearch") {
      return;
    }
    const name = input.name;
    const value = input.value.trim(); // Supprimer les espaces vides

    if (name && value) {
      // Vérifier si le nom et la valeur ne sont pas vides
      postData[name] = value;
      // Afficher la valeur de chaque input dans la console
      console.log(`${name}: ${value}`);
      // Vous pouvez faire autre chose avec les valeurs récupérées
    } else {
      console.error(`Input "${name}" is empty or invalid.`);
      // Gérer les erreurs ou afficher un message à l'utilisateur
    }
  });

  // Vérification si des données à envoyer
  if (Object.keys(postData).length > 0) {
    try {
      // Requête POST avec la méthode fetch
      console.log(postData);
      const response = await fetch("/admin/promotion/add/database", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(postData),
      });

      // Vérification de la réponse
      if (response.ok) {
        const data = await response.json();
        console.log(data);
        // Traiter la réponse si nécessaire
      } else {
        console.error("Failed to send data:", response.statusText);
        // Gérer les erreurs de requête
      }
    } catch (error) {
      console.error("Error:", error.message);
      // Gérer les erreurs de connexion ou autres erreurs
    }
  } else {
    console.error("No data to send.");
    // Gérer le cas où aucune donnée n'est disponible pour l'envoi
  }
});

document.addEventListener("click", function (event) {
  const targetSearch = document.getElementById("targetSearch");
  const resultContainer = document.getElementById("resultContainer");

  if (
    !targetSearch.contains(event.target) &&
    !resultContainer.contains(event.target)
  ) {
    resultContainer.style.display = "none";
  }
});
