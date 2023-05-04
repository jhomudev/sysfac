// TODO: DECLARACIÓN DE ELEMENTOS
const tbody = document.querySelector(".table__tbody");

const btnToggleForm = document.querySelectorAll(".toggleForm");
const modalForm = document.getElementById("modalForm");
const modalDetails = document.getElementById("modalDetails");

const formsFecth = document.querySelectorAll(".formFetch");
const formCreate = document.querySelector(".formCreate");

// TODO: FUNCIONALIDADES
btnToggleForm.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    toggleShowElement(modalForm);
    document.querySelector(".form__title").textContent = "Agregar usuario";
    document.querySelector(".form__submit").value = "Agregar";
    formCreate.reset();
  });
});

// funcion para hablitar funcionalidades de lso botones
function habilityBtns() {
  const formsDelete = document.querySelectorAll(".formDelete");
  const btnsEdit = document.querySelectorAll(".btn_edit");
  const btnToggleDetails = document.querySelectorAll(".toggleDetails");

  btnsEdit.forEach((btn) => {
    btn.addEventListener("click", function () {
      toggleShowElement(modalForm);
      setDataUser(this.dataset.key);
    });
  });

  btnToggleDetails.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      toggleShowElement(modalDetails);
      setDetailsUser(this.dataset.key);
    });
  });

  // Funcion eliminar usuarios
  formsDelete.forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      sendFormFetch(e, () => {
        getUsers();
      });
    });
  });
}

// Peticion para llenar campos de formulario para edición
async function setDataUser(userId) {
  const req = await fetch(`${serverURL}/fetch/getDataUserFetch.php`, {
    method: "POST",
    body: new URLSearchParams(`userId=${userId}`),
  });
  const res = await req.json();

  document.querySelector(".form__title").textContent = "Modificar usuario";
  document.querySelector(".form__submit").value = "Modificar";
  document.getElementById("userId").value = res.user_id;
  document.getElementById("dni").value = res.dni;
  document.getElementById("nombres").value = res.names;
  document.getElementById("apellidos").value = res.lastnames;
  document.getElementById("username").value = res.username;
  document.getElementById("password").value = res.password;
  document.getElementById("correo").value = res.email;
}
// Peticion para llenar campos de formulario para edición
async function setDetailsUser(userId) {
  const req = await fetch(`${serverURL}/fetch/getDataUserFetch.php`, {
    method: "POST",
    body: new URLSearchParams(`userId=${userId}`),
  });
  const res = await req.json();

  document.getElementById("userDNI").textContent = res.dni;
  document.getElementById("userNames").textContent = res.names;
  document.getElementById("userLastnames").textContent = res.lastnames;
  document.getElementById("userUsername").textContent = res.username;
  document.getElementById("userPassword").textContent = res.password;
  document.getElementById("userEmail").textContent = res.email;
  document.getElementById("userIsAdmin").textContent =
    res.is_active == 1 ? "ADMINISTRADOR" : "VENDEDOR";
  document.getElementById("userIsActive").textContent =
    res.is_active == 1 ? "SI" : "NO";
  document.getElementById("userDate").textContent = res.created_at;
}

// petición para listar todos usuarios
async function getUsers() {
  const req = await fetch(`${serverURL}/fetch/getUsersFetch.php`);
  const res = await req.json();
  if (res.length > 0) {
    tbody.innerHTML = "";
    res.forEach((user) => {
      tbody.innerHTML += `
      <tr>
        <td>${user.dni}</td>
        <td>${user.names} ${user.lastnames}</td>
        <td>${user.email}</td>
        <td>${user.is_admin == 0 ? "VENDEDOR" : "ADMINISTRADOR"}</td>
        <td>${user.is_active == 1 ? "SI" : "NO"}</td>
        <td class="actions">
          <button data-key="${
            user.user_id
          }" class="actions__btn btn_edit" style="--cl:var(--c_sky);" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <form action="${serverURL}/fetch/deleteUserFetch.php" method="POST" class="formFetch formDelete">
            <input type="hidden" value="${user.user_id}" name="tx_user_id">
            <button class="actions__btn btn_delete" style="--cl:red;" title="Eliminar"><i class="ph ph-trash"></i></button>
          </form>
          <button data-key="${
            user.user_id
          }" class="actions__btn toggleDetails" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
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
  habilityBtns();
}

// Peticion para crear/editar y usuarios
formCreate.addEventListener("submit", function (e) {
  e.preventDefault();
  sendFormFetch(e, function () {
    toggleShowElement(modalForm);
    getUsers();
  });
});

getUsers();
