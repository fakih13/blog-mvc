class FormHandler {
  constructor() {
    this.methodInput = document.querySelector("#method");
    this.form = document.querySelector("form");
    this.targetType = document.querySelector("#targetType");
    this.resultContainer = document.getElementById("resultContainer");
    this.targetSearch = document.querySelector("#targetSearch");
    this.checkBoxEnd = document.getElementById("addEnd");
    this.dateContainer = document.querySelector("#date");

    document.addEventListener("DOMContentLoaded", () => {
      this.toggleTargetSearchVisibility();
    });

    this.methodInput.addEventListener("change", () =>
      this.handleMethodChange()
    );
    this.targetType?.addEventListener("change", () => {
      this.selectedTarget = this.targetType.value;
      this.toggleTargetSearchVisibility();
    });
    this.targetSearch?.addEventListener("input", () =>
      this.searchTargetInSql()
    );
    this.checkBoxEnd?.addEventListener("change", () => this.addEnd());
    this.form?.addEventListener("submit", (e) => this.handleSubmit(e));
    document.addEventListener("click", (event) =>
      this.hideResultContainer(event)
    );
  }

  handleMethodChange() {
    const selectedMethod = this.methodInput.value;
    selectedMethod === "code"
      ? this.addCouponCodeInput()
      : this.removeCouponCodeInput();
  }

  addCouponCodeInput() {
    const couponCodeInput = document.createElement("input");
    couponCodeInput.setAttribute("type", "text");
    couponCodeInput.setAttribute("id", "couponCodeInput");
    couponCodeInput.setAttribute("placeholder", "Code de réduction");
    couponCodeInput.setAttribute("name", "code");
    couponCodeInput.classList.add("mb-2");

    this.methodInput.insertAdjacentElement("afterend", couponCodeInput);
  }

  removeCouponCodeInput() {
    const couponCodeInput = document.getElementById("couponCodeInput");
    couponCodeInput?.remove();
  }

  searchTargetInSql() {
    const query = this.targetSearch.value;

    if (query.length > 2) {
      fetch(`/admin/promotion/searchTarget/${this.selectedTarget}/${query}`)
        .then((response) => response.json())
        .then((data) => {
          this.resultContainer.innerHTML = "";
          if (data.length > 0) {
            this.resultContainer.style.display = "block";
            this.resultContainer.setAttribute("class", "border mt-2");
            data.forEach((item) => {
              const resultItem = document.createElement("div");
              resultItem.classList.add(
                "mx-auto",
                "p-2",
                "border-bottom",
                "pe-auto"
              );
              resultItem.textContent = item.Nom;
              resultItem.addEventListener("click", () => {
                this.addTargetToForm(item);
                this.resultContainer.style.display = "none";
                this.targetSearch.value = "";
              });
              this.resultContainer.appendChild(resultItem);
            });
          } else {
            this.resultContainer.style.display = "none";
          }
        });
    }
  }

  addTargetToForm(target) {
    const targetExist = document.getElementById("target");

    if (targetExist) {
      targetExist.remove();
    }

    const containerTarget = document.createElement("div");
    containerTarget.classList.add("d-flex");
    containerTarget.setAttribute("id", "target");

    const spanTarget = document.createElement("span");
    spanTarget.textContent = target.Nom;

    const inputTarget = document.createElement("input");
    inputTarget.setAttribute("value", target.PlatID);
    inputTarget.setAttribute("name", "PlatID");
    inputTarget.setAttribute("hidden", "");

    const removeButton = document.createElement("button");
    removeButton.textContent = "X";
    removeButton.addEventListener("click", (e) => {
      e.preventDefault();
      containerTarget.remove();
    });

    containerTarget.appendChild(spanTarget);
    containerTarget.appendChild(removeButton);
    containerTarget.appendChild(inputTarget);

    this.targetSearch.insertAdjacentElement("afterend", containerTarget);
  }

  addEnd() {
    const endDateIsTrue = document.getElementById("endDate");
    endDateIsTrue?.remove();

    if (this.checkBoxEnd.checked) {
      const endDate = document.createElement("input");
      endDate.setAttribute("type", "date");
      endDate.setAttribute("name", "end_date");
      endDate.setAttribute("id", "endDate");
      this.checkBoxEnd.insertAdjacentElement("afterend", endDate);
    }
  }

  async handleSubmit(e) {
    e.preventDefault();

    const inputs = Array.from(this.form.querySelectorAll("input, select"));
    const postData = {};

    let hasEmptyValue = [];
    let hasInvalidDate = false;

    const currentDate = new Date();
    const day = currentDate.getDate();
    const month = currentDate.getMonth() + 1;
    const year = currentDate.getFullYear();
    const fullDate = `${year}-${month < 10 ? "0" + month : month}-${
      day < 10 ? "0" + day : day
    }`;

    inputs.forEach((input) => {
      if (input.name !== "targetSearch" && input.value.trim()) {
        if (input.name === "start_date" && input.value < fullDate) {
          hasInvalidDate = true;
        }
        if (input.name === "percentage" && input.value <= 0.99) {
          hasEmptyValue.push({ name: input.name });
        }
        if (
          (input.name === "target_type" && input.value === "categorie") ||
          (input.name === "target_type" && input.value === "plat")
        ) {
          const platIDInput = inputs.find((item) => item.name === "PlatID");
          if (!platIDInput || !platIDInput.value.trim()) {
            hasInvalidDate = true;
          }
        }
        postData[input.name] = input.value.trim();
      } else if (input.name !== "targetSearch") {
        hasEmptyValue.push({ name: input.name });
      }
    });

    if (hasEmptyValue.length > 0) {
      hasEmptyValue.forEach((emptyInput) => {
        this.showAlert(`Le champ ${emptyInput.name} est vide.`, "danger");
      });
    } else if (hasInvalidDate) {
      this.showAlert(
        "Date antérieure. Veuillez entrer une date valide.",
        "danger"
      );
    } else {
      if (Object.keys(postData).length > 0) {
        try {
          const response = await fetch("/admin/promotion/add/database", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(postData),
          });

          if (response.ok) {
            const data = await response.json();
            console.log(data);
            this.showAlert("Données envoyées avec succès.", "success");
          } else {
            console.error("Failed to send data:", response.statusText);
            this.showAlert("Échec de l'envoi des données.", "danger");
          }
        } catch (error) {
          console.error("Error:", error.message);
          this.showAlert(
            "Erreur de connexion. Veuillez réessayer plus tard.",
            "danger"
          );
        }
      } else {
        console.error("No data to send.");
      }
    }
  }

  showAlert(message, type) {
    console.log(`Alert type: ${type}, Message: ${message}`);
    const alertBanner = document.createElement("div");
    alertBanner.classList.add(
      "alert",
      `alert-${type}`,
      "alert-dismissible",
      "fade",
      "show"
    );
    alertBanner.setAttribute("role", "alert");
    alertBanner.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    const containerElement = document.querySelector("main");
    containerElement?.insertBefore(alertBanner, containerElement.firstChild);
  }

  hideResultContainer(event) {
    if (
      !this.targetSearch.contains(event.target) &&
      !this.resultContainer.contains(event.target)
    ) {
      this.resultContainer.style.display = "none";
    }
  }

  toggleTargetSearchVisibility() {
    if (this.targetType.value === "boutique") {
      this.targetSearch.classList.add("d-none");
    } else {
      this.targetSearch.classList.remove("d-none");
    }
  }
}

new FormHandler();
