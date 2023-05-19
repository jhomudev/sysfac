// TODO: DECLARACIÓN DE ELEMENTOS
const tbody = document.querySelector(".table__tbody");
const inputSearch = document.getElementById("inputSearch");
const allBtn = document.getElementById("all");
const filterSelect = document.querySelectorAll(".filter__select");

// peticion para traer productos
async function getSells(words = "", column = "", value = "") {
  try {
    const formData = new FormData();
    formData.append("words", words);
    formData.append("column", column);
    formData.append("value", value);
    const req = await fetch(`${serverURL}/fetch/getSellsFetch.php`, {
      method: "POST",
      body: formData,
    });
    const res = await req.text();
    tbody.innerHTML = res;
  } catch (error) {
    console.log(error);
  }
}

getSells();

inputSearch.addEventListener("input", () => getSells(inputSearch.value));
allBtn.addEventListener("click", () => {
  getSells();
  filterSelect.forEach((filter) => {
    filter.selectedIndex = -1;
  });
});
filterSelect.forEach((filter) => {
  filter.addEventListener("change", () => {
    getSells("", filter.dataset.col, filter.value);
  });
});
