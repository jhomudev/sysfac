// TODO: DECLARACIÓN DE ELEMENTOS
const tbody = document.querySelector(".table__tbody");
const modalForm = document.getElementById("modalForm");
const modalDetails = document.getElementById("modalDetails");

// funcion para hablitar funcionalidades de lso botones

const formsRequest = document.querySelectorAll(".formRequest");
const btnToggleForm = document.querySelectorAll(".toggleForm");
const btnsEdit = document.querySelectorAll(".btn_edit");
const btnToggleDetails = document.querySelectorAll(".toggleDetails");
const formCreate = document.querySelector(".form__create");

btnToggleDetails.forEach((btn) => {
  btn.addEventListener("click", function (e) {
    toggleShowElement(modalDetails);
    setDetailsUser(this.dataset.key);
  });
});

btnToggleForm.forEach((btn) => {
  btn.addEventListener("click", () => {
    toggleShowElement(modalForm);
    document.querySelector(".form__title").textContent = "Agregar usuario";
    document.querySelector(".form__submit").value = "Agregar";
    document.getElementById("userId").value = "";
    document.getElementById("label__password").innerText = "Contraseña";
    formCreate.reset();
  });
});

btnsEdit.forEach((btn) => {
  btn.addEventListener("click", function () {
    toggleShowElement(modalForm);
    setDataUser(this.dataset.key);
  });
});

// Funcionalidad de envio de forms con fetch
formsRequest.forEach((form) => {
  form.addEventListener("submit", (e) => {
    sendFormRequest(e);
  });
});

// Peticion para llenar campos de formulario para edición
async function setDataUser(userId) {
  try {
    const req = await axios.post(
      `${serverURL}/Request/getDataUserRequest.php`,
      new URLSearchParams(`userId=${userId}`)
    );
    const res = await req.data;

    document.querySelector(".form__title").textContent = "Modificar usuario";
    document.querySelector(".form__submit").value = "Modificar";
    document.getElementById("userId").value = res.user_id;
    document.getElementById("dni").value = res.dni;
    document.getElementById("nombres").value = res.names;
    document.getElementById("apellidos").value = res.lastnames;
    document.getElementById("username").value = res.username;
    document.getElementById("correo").value = res.email;
    document.getElementById("label__password").innerText = "Nueva Contraseña";
  } catch (error) {
    console.log(error);
  }
}
// Peticion para llenar campos de formulario para edición
async function setDetailsUser(userId) {
  try {
    const req = await axios.post(
      `${serverURL}/Request/getDataUserRequest.php`,
      new URLSearchParams(`userId=${userId}`)
    );
    const res = await req.data;

    document.getElementById("userDNI").textContent = res.dni;
    document.getElementById("userNames").textContent = res.names;
    document.getElementById("userLastnames").textContent = res.lastnames;
    document.getElementById("userUsername").textContent = res.username;
    document.getElementById("userEmail").textContent = res.email;
    document.getElementById("userIsAdmin").textContent =
      res.is_active == 1 ? "ADMINISTRADOR" : "VENDEDOR";
    document.getElementById("userIsActive").textContent =
      res.is_active == 1 ? "SI" : "NO";
    document.getElementById("userDate").textContent = res.created_at;
  } catch (error) {
    console.log(error);
  }
}
