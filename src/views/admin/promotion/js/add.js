const inputMethod = document.getElementById("method");
/* const couponCode = document.getElementById("couponCode"); */

/* inputMethod?.addEventListener("change", function () {
  let valueMethod = inputMethod.value;
  couponCode?.classList.toggle("d-none");
  if (valueMethod !== "code") {
    couponCode.value = "";
  }
}); */

inputMethod?.addEventListener("change", function () {
  let valueMethod = inputMethod.value;
  if (valueMethod === "code") {
    let inputCouponCode = document.createElement("input");
    inputCouponCode.setAttribute("text", "");
    inputCouponCode.setAttribute("id", "couponCodeTest");
    inputCouponCode.setAttribute("placeholder", "code de réduction");
    inputMethod.insertAdjacentElement("afterend", inputCouponCode);
  } else {
    let removeCouponCode = document.getElementById("couponCodeTest");
    removeCouponCode?.remove();
  }
});
