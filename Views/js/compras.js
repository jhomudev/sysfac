// TODO: DECLARACIÓN DE ELEMENTOS
const tbody = document.querySelector(".table__tbody");
const inputSearch = document.getElementById("inputSearch");
const allBtn = document.getElementById("all");
const filterSelect = document.querySelectorAll(".filter__select");

// peticion para traer productos
async function getPurchases(words = "", column = "", value = "") {
  try {
    const formData = new FormData();
    formData.append("words", words);
    formData.append("column", column);
    formData.append("value", value);
    const req = await fetch(`${serverURL}/fetch/getPurchasesFetch.php`, {
      method: "POST",
      body: formData,
    });
    const res = await req.text();
    tbody.innerHTML = res;
  } catch (error) {
    console.log(error);
  }
}

getPurchases();

inputSearch.addEventListener("input", () => getPurchases(inputSearch.value));
allBtn.addEventListener("click", () => {
  getPurchases();
  filterSelect.forEach((filter) => {
    filter.selectedIndex = -1;
  });
});
filterSelect.forEach((filter) => {
  filter.addEventListener("change", () => {
    getPurchases("", filter.dataset.col, filter.value);
  });
});
