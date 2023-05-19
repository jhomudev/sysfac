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
    const res = await req.json();
    if (res.length > 0) {
      tbody.innerHTML = "";
      res.forEach((sell) => {
        tbody.innerHTML += `
        <tr>
          <td>${sell.user}</td>
          <td>${sell.client}</td>
          <td>S/${sell.total_import}</td>
          <td>S/${sell.discount}</td>
          <td>S/${sell.total_pay}</td>
          <td>${sell.created_at}</td>
          <td class="actions">
            <a href="${serverURL}/fetch/proof.php" target="_blank" class="actions__btn" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-newspaper-clipping"></i></a>
          </td>
        </tr>
        `;
      });
    } else {
      tbody.innerHTML = `
      <tr>
        <td aria-colspan="7" colspan="7">
          <div class="empty">
            <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/5445/5445197.png" alt="vacio" class="empty__img"></div>
          </div>
          <p class="empty__message">No hay registros</p>
        </td>
      </tr>
    `;
    }
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
