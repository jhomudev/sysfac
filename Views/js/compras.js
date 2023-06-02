// TODO: DECLARACIÓN DE ELEMENTOS
const tbody = document.querySelector(".table__tbody");
const inputSearch = document.getElementById("inputSearch");
const allBtn = document.getElementById("all");
const filterSelect = document.querySelectorAll(".filter__select");
const filtersDate = document.querySelectorAll(".filter__date");

// peticion para traer productos
async function getPurchases(
  words = "",
  column = "",
  value = "",
  dateStart = "",
  dateEnd = ""
) {
  try {
    const formData = new FormData();
    formData.append("words", words);
    formData.append("column", column);
    formData.append("value", value);
    formData.append("date_start", dateStart);
    formData.append("date_end", dateEnd);
    const req = await fetch(`${serverURL}/fetch/getPurchasesFetch.php`, {
      method: "POST",
      body: formData,
    });
    const res = await req.text();
    console.log(res);
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

filtersDate.forEach((filter) => {
  filter.addEventListener("change", () => {
    getPurchases("", "", "", filtersDate[0].value, filtersDate[1].value);
  });
});
