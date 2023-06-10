// TODO: DECLARACIÓN DE ELEMENTOS
const inputSearch = document.getElementById("inputSearch");
const allBtn = document.getElementById("all");
const filterSelect = document.querySelectorAll(".filter__select");
const rowsCount = document.getElementById("rowsCount");
const tbody = document.querySelector(".table__tbody");
const formFetch = document.querySelector(".formFetch");

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
      res.forEach((product, id) => {
        tbody.innerHTML += `
        <tr>
          <td>${product.product_name}</td>
          <td>${product.serial_number ? product.serial_number : "N.A."}</td>
          <td>${product.local_name ? product.local_name : "No asignado"}</td>
          <td>${product.state}</td>
          <td class="actions">
            <form action="${serverURL}/fetch/.php" method="POST" class="formFetch formDelete">
              <input type="hidden" value="${
                product.product_id
              }" name="tx_product_id">
              <button class="actions__btn" style="--cl:var(--c_sky);" title="Cambiar estado"><i class="ph ph-swap"></i></button>
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

// getProductsInventary();

// inputSearch.addEventListener("input", () =>
//   getProductsInventary(inputSearch.value)
// );
// allBtn.addEventListener("click", () => {
//   getProductsInventary();
//   filterSelect.forEach((filter) => {
//     filter.selectedIndex = -1;
//   });
// });
// filterSelect.forEach((filter) => {
//   filter.addEventListener("change", () => {
//     getProductsInventary("", filter.dataset.col, filter.value);
//   });
// });

// *este es el otro metodo sin fetch

const checkboxMain = document.getElementById("checkboxMain");
const checkboxAll = document.getElementsByName("p_checkeds[]");

const action = document.getElementById("action");
const localBox = document.getElementById("localBox");
const stateBox = document.getElementById("stateBox");

checkboxMain.addEventListener("change", () => {
  checkboxAll.forEach((chexckbox) => {
    chexckbox.checked = checkboxMain.checked;
  });
});

// checkboxAll.forEach((chexckbox) => {
//   chexckbox.addEventListener("change", () => {
//     if (chexckbox.checked) console.log(chexckbox.value);
//   });
// });

// Mostrar campo adicional al elegir accion
action.addEventListener("change", () => {
  if (action.value == "assign_local") {
    localBox.classList.remove("hidden");
    stateBox.classList.add("hidden");
  }
  if (action.value == "change_state") {
    stateBox.classList.remove("hidden");
    localBox.classList.add("hidden");
  }
});

// Funcionalidad de envio de forms con fetch
// formFetch.addEventListener("submit", (e) => {
//   sendFormFetch(e);
// });
formFetch.addEventListener("submit", (e) => {
  e.preventDefault();
  // Obtencion de los values de los checkbox seleccionados
  let arrIds = [];
  const checkboxAll = document.getElementsByName("p_checkeds[]");

  checkboxAll.forEach((checkbox) => {
    if (checkbox.checked) arrIds.push(checkbox.value);
  });

  const data = new FormData(e.target);
  data.append("prods", arrIds);
  const method = e.target.getAttribute("method");
  const action = e.target.getAttribute("action");

  const config = {
    method: method,
    body: data,
  };

  Swal.fire({
    title: "Estas seguro de ejecutar la operación?",
    text: "Presione Aceptar para continuar.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Aceptar",
    cancelButtonText: "Cancelar",
  }).then(async (result) => {
    try {
      if (result.isConfirmed) {
        const req = await fetch(action, config);
        const res = await req.json();
        alertFetch(res);
      }
    } catch (error) {
      console.log(error);
    }
  });
});

// funcion para ejecutar accion
// async function executeAction() {

// }
