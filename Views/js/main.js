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

// Funcion para mostrar formulario
function onToggleForm() {
  const btnToggleForm = document.querySelectorAll(".toggleForm");
  const modalForm = document.querySelector(".modalForm");

  btnToggleForm.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      toggleShowElement(modalForm);
    });
  });
}

onToggleForm();

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
