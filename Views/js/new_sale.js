// TODO: DECLARACIÓN DE ELEMENTOS
const inputSearch = document.getElementById("inputSearch");
const allBtn = document.getElementById("all");
const filterSelect = document.querySelectorAll(".filter__select");
const productsBox = document.querySelector(".productsBox");

function habilityDOM() {
  const formsAdd = document.querySelectorAll(".product__form");
  formsAdd.forEach((form) => {
    form.addEventListener("submit", (e) => addProduct(e));
  });
}

// peticion para traer productos para venta
async function getProducts(words = "", column = "", value = "") {
  try {
    const formData = new FormData();
    formData.append("words", words);
    formData.append("column", column);
    formData.append("value", value);
    const req = await fetch(`${serverURL}/fetch/getProductsForSaleFetch.php`, {
      method: "POST",
      body: formData,
    });
    const res = await req.text();
    productsBox.innerHTML = res;

    habilityDOM();
  } catch (error) {
    console.log(error);
  }
}
getProducts();

inputSearch.addEventListener("input", () => getProducts(inputSearch.value));
allBtn.addEventListener("click", () => {
  getProducts();
  filterSelect.forEach((filter) => {
    filter.selectedIndex = -1;
  });
});
filterSelect.forEach((filter) => {
  filter.addEventListener("change", () => {
    getProducts("", filter.dataset.col, filter.value);
  });
});
