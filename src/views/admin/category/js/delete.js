class CategoryManager {
  constructor() {
    this.showModalButtons = document.querySelectorAll(".showModal");
    this.modalBody = document.querySelector(".modal-body");
    this.modalDialog = document.querySelector(".modal-dialog");
    this.deleteButtonCategory = document.querySelector("#deleteButtonCategory");
    this.categoryIdToDelete = null; // Variable pour stocker l'ID

    this.init();
  }

  init() {
    this.showModalButtons.forEach((element) => {
      element.addEventListener("click", (e) => {
        const parentElement = element.parentElement;
        const span = parentElement?.querySelector("span");
        const nameCategory = span.textContent;
        this.categoryIdToDelete = span?.getAttribute("data-category-id"); // Stocker l'ID ici
        this.modalBody.textContent = nameCategory;
        console.log(this.categoryIdToDelete);
        console.log(nameCategory);
      });
    });

    this.deleteButtonCategory?.addEventListener("click", () => {
      if (!this.categoryIdToDelete) {
        console.error("ID not found");
        return;
      }

      this.deleteCategory(this.categoryIdToDelete);
    });
  }

  async deleteCategory(id) {
    try {
      const response = await fetch(`/admin/category/delete/database/${id}`, {
        method: "DELETE",
      });

      if (response.ok) {
        const data = await response.json();
        if (data.success) {
          console.log(data);
          this.showAlert(data.message, "success");
        } else {
          this.showAlert(data.message, "danger");
        }
      } else {
        console.error("Failed to delete category");
        this.showAlert("Failed to delete category", "danger");
      }
    } catch (error) {
      console.error("Error:", error);
    }
  }
  showAlert(message, type) {
    const alertBanner = document.createElement("div");
    alertBanner.classList.add(
      "alert",
      `alert-${type}`,
      "alert-dismissible",
      "fade",
      "show",
      "m-2"
    );
    alertBanner.setAttribute("role", "alert");
    alertBanner.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    this.modalDialog.after(alertBanner);
  }
}

// Instantiate the class
const categoryManager = new CategoryManager();
