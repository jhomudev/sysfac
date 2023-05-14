// TODO: DECLARACIÓN DE ELEMENTOS
const inputSearch = document.getElementById("inputSearch");
const allBtn = document.getElementById("all");
const filterSelect = document.querySelectorAll(".filter__select");
const rowsCount = document.getElementById("rowsCount");
const tbody = document.querySelector(".table__tbody");

function habilityDOM() {
  const formCreate = document.querySelector(".form__create");
  const formsFetch = document.querySelectorAll(".formFetch");
  const btnToggleForm = document.querySelectorAll(".toggleForm");
  const btnsEdit = document.querySelectorAll(".btn__edit");
  const btnToggleDetails = document.querySelectorAll(".toggleDetails");

  btnToggleDetails.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      toggleShowElement(modalDetails);
      setDetailsProduct(this.dataset.key);
    });
  });

  btnToggleForm.forEach((btn) => {
    btn.addEventListener("click", () => {
      toggleShowElement(modalForm);
      document.querySelector(".form__title").textContent = "Agregar producto";
      document.querySelector(".form__submit").value = "Agregar";
      document.getElementById("productId").value = "";
      formCreate.reset();
    });
  });

  btnsEdit.forEach((btn) => {
    btn.addEventListener("click", function () {
      toggleShowElement(modalForm);
      setDataProduct(this.dataset.key);
    });
  });

  // Funcionalidad de envio de forms con fetch
  formsFetch.forEach((form) => {
    form.addEventListener("submit", (e) => {
      sendFormFetch(e);
    });
  });
}

// peticion para traer productos
async function getProductsInventary(words = "", column = "", value = "") {
  try {
    const formData = new FormData();
    formData.append("words", words);
    formData.append("column", column);
    formData.append("value", value);
    const req = await fetch(
      `${serverURL}/fetch/getProductsInventaryFetch.php`,
      {
        method: "POST",
        body: formData,
      }
    );
    const res = await req.json();
    rowsCount.innerHTML = res.length;
    if (res.length > 0) {
      tbody.innerHTML = "";
      res.forEach((product) => {
        tbody.innerHTML += `
        <tr>
          <td>${product.product_name}</td>
          <td>${product.serial_number}</td>
          <td>S/ ${product.price_purchase}</td>
          <td>${product.local_name}</td>
          <td>${product.state}</td>
          <td class="actions">
            <form action="${serverURL}/fetch/.php" method="POST" class="formFetch formDelete">
              <input type="hidden" value="${product.product_id}" name="tx_product_id">
              <button class="actions__btn" style="--cl:var(--c_sky);" title="Cambiar estado"><i class="ph ph-swap"></i></button>
            </form>
            <form action="${serverURL}/fetch/.php" method="POST" class="formFetch formDelete">
              <input type="hidden" value="${product.product_id}" name="tx_product_id">
              <button class="actions__btn" style="--cl:var(--c_green);" title="Añadir a venta"><i class="ph ph-shopping-cart"></i></button>
            </form>
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
    habilityDOM();
  } catch (error) {
    console.log(error);
  }
}

getProductsInventary();

inputSearch.addEventListener("input", () =>
  getProductsInventary(inputSearch.value)
);
allBtn.addEventListener("click", () => {
  getProductsInventary();
  filterSelect.forEach((filter) => {
    filter.selectedIndex = -1;
  });
});
filterSelect.forEach((filter) => {
  filter.addEventListener("change", () => {
    getProductsInventary("", filter.dataset.col, filter.value);
  });
});
