// TODO: DECLARACIÓN DE ELEMENTOS
const inputSearch = document.getElementById("inputSearch");
const allBtn = document.getElementById("all");
const filterSelect = document.querySelectorAll(".filter__select");
const productsBox = document.querySelector(".productsBox");
const addFor = document.getElementById("addFor");
const modalForm = document.getElementById("modalForm");

const formAdd = document.querySelector(".product__form");
formAdd.addEventListener("submit", (e) => {
  addProduct(e);
  toggleShowElement(modalForm);
});

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

    const togglesForm = document.querySelectorAll(".toggleForm");
    togglesForm.forEach((btn) => {
      btn.addEventListener("click", () => {
        toggleShowElement(modalForm);
        setDataProduct(btn.dataset.id);
        // resetear form
        formAdd.reset();
      });
    });
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

// peticion getdataproduct
async function setDataProduct(productIdName) {
  try {
    const req = await fetch(`${serverURL}/fetch/getDataProductFetch.php`, {
      method: "POST",
      body: new URLSearchParams(`productIdName=${productIdName}`),
    });
    const res = await req.json();

    document.getElementById("productIdName").value = res.product_id;
    document.getElementById("productName").value = res.name;
    document.getElementById("productPrice").value = res.price_sale;
    document.getElementById("addFor").value =
      res.sale_for == 1 ? "CANTIDAD" : "UNIDAD/N.S.";

    if (res.sale_for == 1) {
      document.querySelector(".quantityBox").classList.remove("hidden");
      document.querySelector(".nsBox").classList.add("hidden");
    } else if (res.sale_for == 2) {
      document.querySelector(".nsBox").classList.remove("hidden");
      document.querySelector(".quantityBox").classList.add("hidden");
    }
  } catch (error) {
    console.log(error);
  }
}

addFor.addEventListener("change", () => {});
