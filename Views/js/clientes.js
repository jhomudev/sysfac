// Obtencion de elementos
const tbody = document.querySelector(".table__tbody");
const inputSearch = document.getElementById("inputSearch");
const modalForm = document.getElementById("modalForm");
const formsFetch = document.querySelectorAll(".formFetch");
const btnToggleForm = document.querySelectorAll(".toggleForm");

btnToggleForm.forEach((btn) => {
  btn.addEventListener("click", () => toggleShowElement(modalForm));
});

// Funcionalidad de envio de forms con fetch
formsFetch.forEach((form) => {
  form.addEventListener("submit", (e) => {
    sendFormFetch(e);
  });
});

// Funcion obtener clientes
async function getClients(words = "") {
  try {
    const req = await fetch(`${serverURL}/fetch/getClientsFetch.php`, {
      method: "POST",
      body: new URLSearchParams("words=" + words),
    });
    const res = await req.json();
    if (res.length > 0) {
      tbody.innerHTML = "";
      res.forEach((client) => {
        tbody.innerHTML += `
        <tr>
          <td>${client.dni ? client.dni : "--"}</td>
          <td>${client.RUC ? client.RUC : "--"}</td>
          <td>${client.names} ${client.lastnames}</td>
          <td>${client.phone ? client.phone : "--"}</td>
          <td>${client.email ? client.email : "--"}</td>
          <td>${client.address ? client.address : "--"}</td>
          <td class="actions">
            <button data-key="${
              client.client_id
            }" class="actions__btn btn__edit" style="--cl:var(--c_sky);" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
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

    // habilitar btns
    const btnsEdit = document.querySelectorAll(".btn__edit");

    btnsEdit.forEach((btn) => {
      btn.addEventListener("click", function () {
        toggleShowElement(modalForm);
        setDataClient(this.dataset.key);
      });
    });
  } catch (error) {
    console.log(error);
  }
}

getClients();

inputSearch.addEventListener("input", () => getClients(inputSearch.value));

// Peticion para llenar campos de formulario para edición de cliente
async function setDataClient(clientId) {
  try {
    const req = await fetch(`${serverURL}/fetch/getDataClientFetch.php`, {
      method: "POST",
      body: new URLSearchParams(`client_id=${clientId}`),
    });
    const res = await req.json();

    document.getElementById("userId").value = res.client_id;
    document.getElementById("dni").value = res.dni;
    document.getElementById("RUC").value = res.RUC;
    document.getElementById("nombres").value = res.names;
    document.getElementById("apellidos").value = res.lastnames;
    document.getElementById("phone").value = res.phone;
    document.getElementById("address").value = res.address;
    document.getElementById("correo").value = res.email;
  } catch (error) {
    console.log(error);
  }
}
