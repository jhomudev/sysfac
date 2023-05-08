// Deeficiones de elementos
const modalForm = document.getElementById("modalForm");
// const modalDetails = document.getElementById("modalDetails");

const toggleForm = document.querySelectorAll(".toggleForm");
const formsFetch = document.querySelectorAll(".formFetch");
const formCreate = document.querySelector(".formCreate");
const btnsEdit = document.querySelectorAll(".btn_edit");

toggleForm.forEach((btn) => {
  btn.addEventListener("click", () => {
    toggleShowElement(modalForm);
    document.querySelector(".form__title").textContent = "Agregar usuario";
    document.querySelector(".form__submit").value = "Agregar";
    document.getElementById("supplierId").value = "";
    formCreate.reset();
  });
});

// Funcionalidad de envio de forms con fetch
formsFetch.forEach((form) => {
  form.addEventListener("submit", (e) => sendFormFetch(e));
});

// Set data de proveedor
btnsEdit.forEach((btn) => {
  btn.addEventListener("click", (e) => setDataSupplier(btn.dataset.key));
});

// Peticion para llenar campos de formulario para edición
async function setDataSupplier(supplierId) {
  const req = await fetch(`${serverURL}/fetch/getDataSupplierFetch.php`, {
    method: "POST",
    body: new URLSearchParams(`supplierId=${supplierId}`),
  });
  const res = await req.json();

  document.querySelector(".form__title").textContent = "Modificar proveedor";
  document.querySelector(".form__submit").value = "Modificar";
  document.getElementById("supplierId").value = res.supplier_id;
  document.getElementById("ruc").value = res.RUC;
  document.getElementById("nombre").value = res.name;
  document.getElementById("direccion").value = res.address;
  document.getElementById("telefono").value = res.phone;
}
