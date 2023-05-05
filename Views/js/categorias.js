// TODO: DECLARACIÓN DE ELEMENTOS
const categoriesBox = document.querySelector(".categoriesBox");
const modalForm = document.getElementById("modalForm");
const modalDetails = document.getElementById("modalDetails");

const formsFetch = document.querySelectorAll(".formFetch");
const btnToggleForm = document.querySelectorAll(".toggleForm");
const categories = document.querySelectorAll(".category");
// const btnToggleDetails = document.querySelectorAll(".toggleDetails");
const formCreate = document.querySelector(".form__create");

// btnToggleDetails.forEach((btn) => {
//   btn.addEventListener("click", function (e) {
//     toggleShowElement(modalDetails);
//     setDetailsUser(this.dataset.key);
//   });
// });

btnToggleForm.forEach((btn) => {
  btn.addEventListener("click", () => {
    toggleShowElement(modalForm);
    document.querySelector(".form__title").textContent = "Agregar usuario";
    document.querySelector(".form__submit").value = "Agregar";
    document.getElementById("categoryId").value = "";
    formCreate.reset();
  });
});

categories.forEach((category) => {
  category.addEventListener("click",async function () {
    await setDataCategory(this.dataset.key);
    await toggleShowElement(modalForm);
  });
});

// Funcionalidad de envio de forms con fetch
formsFetch.forEach((form) => {
  form.addEventListener("submit", (e) => {
    sendFormFetch(e);
  });
});

// Peticion para llenar campos de formulario para edición
async function setDataCategory(categoryId) {
  const req = await fetch(`${serverURL}/fetch/getCategoriesFetch.php`, {
    method: "POST",
    body: new URLSearchParams(`categoryId=${categoryId}`),
  });
  const res = await req.json();

  document.querySelector(".form__title").textContent = "Modificar categoría";
  document.querySelector(".form__submit").value = "Modificar";
  document.querySelector(".form__img").src = res.link_image;
  document.getElementById("categoryId").value = res.cat_id;
  document.getElementById("nombreCategoria").value = res.name;
  document.getElementById("linkImage").value = res.link_image;
  document.getElementById("descripcion").value = res.description;
}
// Peticion para llenar campos de formulario para edición
async function setDetailsProduct(userId) {
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

// peticion para traer productos
async function getProducts() {
  const req = await fetch(`${serverURL}/fetch/getProductsFetch.php`);
  const res = await req.json();
  if (res.length > 0) {
    categoriesBox.innerHTML = "";
    res.forEach((product) => {
      categoriesBox.innerHTML += `
      <tr>
        <td>${product.name}</td>
        <td>${product.description}</td>
        <td>${product.price_sale}</td>
        <td>${product.unit}</td>
        <td>${product.category}</td>
        <td>${product.is_active}</td>
        <td class="actions">
          <button data-key="${product.user_id}" class="actions__btn btn_edit" style="--cl:var(--c_sky);" title="Editar"><i class="ph ph-pencil-simple-line"></i></button>
          <form action="${serverURL}/fetch/deleteUserFetch.php" method="POST" class="formFetch formDelete">
            <input type="hidden" value="${product.user_id}" name="tx_user_id">
            <button class="actions__btn btn_delete" style="--cl:red;" title="Eliminar"><i class="ph ph-trash"></i></button>
          </form>
          <button data-key="${product.user_id}" class="actions__btn toggleDetails" style="--cl:var(--c_green);" title="Detalles"><i class="ph ph-note"></i></button>
        </td>
      </tr>
      `;
    });
  } else {
    categoriesBox.innerHTML = `
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
