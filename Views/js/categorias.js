// TODO: DECLARACIÓN DE ELEMENTOS
const modalForm = document.getElementById("modalForm");
const formsRequest = document.querySelectorAll(".formRequest");
const btnToggleForm = document.querySelectorAll(".toggleForm");
const categories = document.querySelectorAll(".category");
const formCreate = document.querySelector(".form__create");
const formDelete = document.querySelector(".form_delete");

btnToggleForm.forEach((btn) => {
  btn.addEventListener("click", () => {
    toggleShowElement(modalForm);
    document.querySelector(".form__title").textContent = "Agregar categoría";
    document.querySelector(".form__submit").value = "Agregar";
    document.getElementById("categoryId").value = "";
    formDelete.style.display = "none";
    formCreate.reset();
  });
});

categories.forEach((category) => {
  category.addEventListener("click", async function () {
    await setDataCategory(this.dataset.key);
    await toggleShowElement(modalForm);
  });
});

// Funcionalidad de envio de forms con fetch
formsRequest.forEach((form) => {
  form.addEventListener("submit", (e) => {
    sendFormRequest(e);
  });
});

// Peticion para llenar campos de formulario para edición
async function setDataCategory(categoryId) {
  try {
    const req = await axios.post(
      `${serverURL}/Request/getCategoriesRequest.php`,
      new URLSearchParams(`categoryId=${categoryId}`)
    );
    const res = await req.data;

    if (res.link_image)
      document.querySelector(".form__img").src = res.link_image;
    else if (res.file_image)
      document.querySelector(".form__img").src = res.file_image;
    else
      document.querySelector(".form__img").src =
        "https://cdn-icons-png.flaticon.com/512/1524/1524855.png";
    document.querySelector(".form__title").textContent = "Modificar categoría";
    formDelete.style.display = "block";
    document.querySelector(".form__submit").value = "Modificar";
    document.getElementById("categoryId").value = res.cat_id;
    document.getElementById("categoryIdDel").value = res.cat_id;
    document.getElementById("nombreCategoria").value = res.name;
    document.getElementById("linkImage").value = res.link_image;
    document.getElementById("descripcion").value = res.description;
  } catch (error) {
    console.log(error);
  }
}
