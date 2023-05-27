// TODO: DECLARACIÓN DE ELEMENTOS
const tbody = document.querySelector(".table__tbody");
const inputSearch = document.getElementById("inputSearch");
const allBtn = document.getElementById("all");
const filterSelect = document.querySelectorAll(".filter__select");
const filtersDate = document.querySelectorAll(".filter__date");

// peticion para traer productos
async function getSells(
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

inputSearch.addEventListener("input", () => {
  filterSelect.forEach((filter) => {
    filter.selectedIndex = -1;
  });
  filtersDate.forEach((filter) => {
    filter.value = "";
  });
  getSells(inputSearch.value);
});
allBtn.addEventListener("click", () => {
  getSells();
  filterSelect.forEach((filter) => {
    filter.selectedIndex = -1;
  });
  filtersDate.forEach((filter) => {
    filter.value = "";
  });
});
filterSelect.forEach((filter) => {
  filter.addEventListener("change", () => {
    getSells("", filter.dataset.col, filter.value);
  });
});

filtersDate.forEach((filter) => {
  filter.addEventListener("change", () => {
    getSells("", "", "", filtersDate[0].value, filtersDate[1].value);
  });
});
