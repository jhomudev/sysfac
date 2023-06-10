// TODO: DECLARACIÓN DE ELEMENTOS
const inputSearch = document.getElementById("inputSearch");
const allBtn = document.getElementById("all");
const filterSelect = document.querySelectorAll(".filter__select");
const tbody = document.querySelector(".table__tbody");
const modalForm = document.getElementById("modalForm");
const modalDetails = document.getElementById("modalDetails");

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
      document.getElementById("productIdName").value = "";
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

// Peticion para llenar campos de formulario para edición
async function setDataProduct(productIdName) {
  try {
    const req = await fetch(`${serverURL}/fetch/getDataProductFetch.php`, {
      method: "POST",
      body: new URLSearchParams(`productIdName=${productIdName}`),
    });
    const res = await req.json();

    document.querySelector(".form__title").textContent = "Modificar usuario";
    document.querySelector(".form__submit").value = "Modificar";
    document.getElementById("productIdName").value = res.product_id;
    document.getElementById("nombre").value = res.name;
    document.getElementById("precio").value = res.price_sale;
    document.getElementById("unidad").value = res.unit;
    document.getElementById("minimo").value = res.inventary_min;
    document.getElementById("linkImage").value = res.link_image;
  } catch (error) {
    console.log(error);
  }
}

// peticion para traer productos
async function getProducts(words = "", column = "", value = "") {
  try {
    const formData = new FormData();
    formData.append("words", words);
    formData.append("column", column);
    formData.append("value", value);
    const req = await fetch(`${serverURL}/fetch/getProductsFetch.php`, {
      method: "POST",
      body: formData,
    });
    const res = await req.json();

    if (res.length > 0) {
      tbody.innerHTML = "";
      res.forEach((product) => {
        tbody.innerHTML += `
        <tr style="background:${
          product.stock <= product.inventary_min ? "#F0D0D6" : ""
        };">
          <td>${product.name}</td>
          <td>S/ ${product.price_sale}</td>
          <td>${product.unit}</td>
          <td>${product.sale_for == 1 ? "CANTIDAD" : "UNIDAD/N.S."}</td>
          <td>${product.category}</td>
          <td>${product.inventary_min}</td>
          <td>${product.stock}</td>
          <td>${product.is_active ? "SI" : "NO"}</td>
          <td class="actions">
            <button data-key="${
              product.product_id
            }" class="actions__btn btn__edit" style="--cl:var(--c_sky);" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
            <form action="${serverURL}/fetch/deleteProductFetch.php" method="POST" class="formFetch formDelete">
              <input type="hidden" value="${
                product.product_id
              }" name="tx_product_id">
              <button class="actions__btn btn_delete" style="--cl:red;" title="Eliminar"><i class="ph ph-trash"></i></button>
            </form>
          </td>
        </tr>
        `;
      });
    } else {
      tbody.innerHTML = `
      <tr>
        <td aria-colspan="8" colspan="8">
          <div class="empty">
            <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/5445/5445197.png" alt="vacio" class="empty__img"></div>
            <p class="empty__message">No hay registros</p>
          </div>
        </td>
      </tr>
    `;
    }
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
