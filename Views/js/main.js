// Variables globales
const serverURL = "http://localhost/sysfac";

// Funcion toggle para mostrar elementos
function toggleShowElement(element) {
  element.classList.toggle("show");
}
//funcion toggle para mostrar el userBar
const btnUserbar = document.getElementById("btnUserbar");
const userBar = document.querySelector(".user__details");

btnUserbar.addEventListener("click", () => toggleShowElement(userBar));

// función toggle para mostrar barra de menu responsive
const menuBarResponsive = document.getElementById("menuBarResponsive");
const btnToggleBar = document.querySelectorAll(".btnToggleBar");

btnToggleBar.forEach((btn) => {
  btn.addEventListener("click", () => toggleShowElement(menuBarResponsive));
});

// funcion para cambiar imagen al colocar link
const formImg = document.querySelector(".form__img");
const linkImage = document.getElementById("linkImage");
if (linkImage !== null && linkImage !== undefined) {
  linkImage.addEventListener("input", (e) => {
    const link = e.target.value;
    formImg.setAttribute("src", link);
  });
}

// Funciones validaciones de inputs numeros
const inputsNumber = document.querySelectorAll("input[number]");

inputsNumber.forEach((input) => {
  input.addEventListener("input", (e) => {
    const valorAnterior = e.target.value;
    const valorNuevo = valorAnterior.replace(/[^0-9]/g, ""); // Solo permitir números

    if (valorAnterior !== valorNuevo) {
      e.target.value = valorNuevo; // Actualizar el valor del input
    }
  });
});

// Funciones validaciones de inputs decimales
const inputsDecimal = document.querySelectorAll("input[decimal]");

inputsDecimal.forEach((input) => {
  input.addEventListener("input", (e) => {
    const valorAnterior = e.target.value;
    const valorNuevo = valorAnterior.replace(/[^0-9.]/g, ""); // Solo permitir números y puntos
    const decimales = valorNuevo.split(".").length - 1;

    if (decimales > 1) {
      // Si hay más de un punto, eliminar el último
      const ultimoPunto = valorNuevo.lastIndexOf(".");
      valorNuevo = valorNuevo.substring(0, ultimoPunto);
    } else if (decimales === 1) {
      // Si hay un punto, asegurarse de que solo haya dos decimales
      const posicionPunto = valorNuevo.indexOf(".");
      const longitudDecimal = valorNuevo.substring(posicionPunto + 1).length;
      if (longitudDecimal > 2) {
        valorNuevo = valorNuevo.substring(0, posicionPunto + 3);
      }
    }

    if (valorAnterior !== valorNuevo) {
      e.target.value = valorNuevo; // Actualizar el valor del input
    }
  });
});
