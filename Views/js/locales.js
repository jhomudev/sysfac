// TODO: DECLARACIÓN DE ELEMENTOS
const modalForm = document.getElementById("modalForm");
const formsRequest = document.querySelectorAll(".formRequest");
const btnToggleForm = document.querySelectorAll(".toggleForm");
const btnsEdit = document.querySelectorAll(".btn_edit");
const formCreate = document.querySelector(".form__create");

btnToggleForm.forEach((btn) => {
  btn.addEventListener("click", () => {
    toggleShowElement(modalForm);
    document.querySelector(".form__title").textContent = "Agregar local";
    document.querySelector(".form__submit").value = "Agregar";
    document.getElementById("localId").value = "";
    formCreate.reset();
  });
});

btnsEdit.forEach((btn) => {
  btn.addEventListener("click", function () {
    setDataLocal(this.dataset.key);
    toggleShowElement(modalForm);
  });
});

// Funcionalidad de envio de forms con fetch
formsRequest.forEach((form) => {
  form.addEventListener("submit", (e) => {
    sendFormRequest(e);
  });
});

// Peticion para llenar campos de formulario para edición
async function setDataLocal(localId) {
  try {
    const req = await axios.post(
      `${serverURL}/Request/getDataLocalRequest.php`,
      new URLSearchParams(`localId=${localId}`)
    );
    const res = await req.data;

    document.querySelector(".form__title").textContent = "Modificar local";
    document.querySelector(".form__submit").value = "Modificar";
    document.getElementById("localId").value = res.local_id;
    document.getElementById("nombre").value = res.name;
    document.getElementById("direccion").value = res.address;
  } catch (error) {
    console.log(error);
  }
}
