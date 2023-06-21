// TODO: DECLARACIÓN DE ELEMENTOS
const inputSearch = document.getElementById("inputSearch");
const allBtn = document.getElementById("all");
const filterSelect = document.querySelectorAll(".filter__select");
const tbody = document.querySelector(".table__tbody");
const modalForm = document.getElementById("modalForm");
const modalDetails = document.getElementById("modalDetails");

const formCreate = document.querySelector(".form__create");
const formsRequest = document.querySelectorAll(".formRequest");
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
formsRequest.forEach((form) => {
  form.addEventListener("submit", (e) => {
    sendFormRequest(e);
  });
});

// Peticion para llenar campos de formulario para edición
async function setDataProduct(productIdName) {
  try {
    const req = await axios.post(
      `${serverURL}/Request/getDataProductRequest.php`,
      new URLSearchParams(`productIdName=${productIdName}`)
    );
    const res = await req.data;

    document.querySelector(".form__title").textContent = "Modificar usuario";
    document.querySelector(".form__submit").value = "Modificar";
    document.getElementById("productIdName").value = res.product_id;
    document.getElementById("nombre").value = res.name;
    document.getElementById("precio").value = res.price_sale;
    document.getElementById("unidad").value = res.unit;
    document.getElementById("minimo").value = res.inventary_min;
    document.getElementById("linkImage").value = res.link_image;
    if (res.link_image)
      document.querySelector(".form__img").src = res.link_image;
    else if (res.file_image)
      document.querySelector(".form__img").src = res.file_image;
    else
      document.querySelector(".form__img").src =
        "https://cdn-icons-png.flaticon.com/512/1524/1524855.png";
  } catch (error) {
    console.log(error);
  }
}