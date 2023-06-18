// Deeficiones de elementos
const modalForm = document.getElementById("modalForm");
// const modalDetails = document.getElementById("modalDetails");

const toggleForm = document.querySelectorAll(".toggleForm");
const formsRequest = document.querySelectorAll(".formRequest");
const formCreate = document.querySelector(".formCreate");
const btnsEdit = document.querySelectorAll(".btn_edit");

toggleForm.forEach((btn) => {
  btn.addEventListener("click", () => {
    toggleShowElement(modalForm);
    document.querySelector(".form__title").textContent = "Agregar usuario";
    document.querySelector(".form__submit").value = "Agregar";
    document.getElementById("supplierIdRUC").value = "";
    formCreate.reset();
  });
});

// Funcionalidad de envio de forms con fetch
formsRequest.forEach((form) => {
  form.addEventListener("submit", (e) => sendFormRequest(e));
});

// Set data de proveedor
btnsEdit.forEach((btn) => {
  btn.addEventListener("click", (e) => setDataSupplier(btn.dataset.key));
});

// Peticion para llenar campos de formulario para edición
async function setDataSupplier(supplierIdRUC) {
  try {
    const req = await axios.post(
      `${serverURL}/Request/getDataSupplierRequest.php`,
      new URLSearchParams(`supplierIdRUC=${supplierIdRUC}`)
    );
    const res = await req.data;

    document.querySelector(".form__title").textContent = "Modificar proveedor";
    document.querySelector(".form__submit").value = "Modificar";
    document.getElementById("supplierIdRUC").value = res.supplier_id;
    document.getElementById("ruc").value = res.RUC;
    document.getElementById("nombre").value = res.name;
    document.getElementById("direccion").value = res.address;
    document.getElementById("telefono").value = res.phone;
  } catch (error) {
    console.log(error);
  }
}
