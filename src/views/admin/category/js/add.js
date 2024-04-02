const form = document.querySelector("form");

form?.addEventListener("submit", async (e) => {
  e.preventDefault();
  const inputName = document.querySelector("#nameCategory");
  if (inputName?.value?.length > 0) {
    console.log("ok");
    const addCategory = await fetch("/admin/category/add/database", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(inputName.value),
    });
    if (addCategory.ok) {
      let data = await addCategory.json();
      console.log(data);
      showAlert(data.message, "success");
    }
  } else {
    console.error("non");
  }
});

function showAlert(message, type) {
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
