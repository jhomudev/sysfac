// TODO: DECLARACIÓN DE ELEMENTOS
const modalForm = document.getElementById("modalForm");
const formsFetch = document.querySelectorAll(".formFetch");
const btnToggleForm = document.querySelectorAll(".toggleForm");
const categories = document.querySelectorAll(".category");
const formCreate = document.querySelector(".form__create");

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
  category.addEventListener("click", async function () {
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
  try {
    const req = await fetch(`${serverURL}/fetch/getCategoriesFetch.php`, {
      method: "POST",
      body: new URLSearchParams(`categoryId=${categoryId}`),
    });
    const res = await req.json();

    if (res.link_image)
      document.querySelector(".form__img").src = res.link_image;
    else document.querySelector(".form__img").src = res.file_image;
    document.querySelector(".form__title").textContent = "Modificar categoría";
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
