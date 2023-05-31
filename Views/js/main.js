// Variables globales
const serverURL = "http://localhost/sysfac";

// Funcion toggle para mostrar elementos
function toggleShowElement(element) {
  element.classList.toggle("show");
}

// funcion de convettir a mayuculas
const mayus = document.querySelectorAll("[mayus]");
mayus.forEach((input) => {
  input.addEventListener(
    "input",
    () => (input.value = input.value.toUpperCase())
  );
});

//funcion toggle para mostrar el userBar
const btnUserbar = document.getElementById("btnUserbar");
const userBar = document.querySelector(".user__details");
const toggleShowNotibar = document.querySelectorAll(".toggleShowNotibar");
const notiBar = document.querySelector(".notifications__details");
const notiBarRes = document.querySelector(
  ".notifications__details__responsive"
);
const btncloseNotiBarRes = document.querySelector(
  ".notifications__details__responsive__close"
);

// !no funciona
// document.addEventListener("click", function (event) {
//   if (notiBar.classList.contains("show") && !event.target.closest(".notifications__details")) {
//     notiBar.classList.remove("show");
//     console.log("first");
//   } else if (event.target.closest(".notifications__details")) {
//     notiBar.classList.add("show");
//   }
// });
toggleShowNotibar.forEach((btn) => {
  btn.addEventListener("click", () => {
    toggleShowElement(notiBar);
    toggleShowElement(notiBarRes);
    toggleShowElement(menuBarResponsive);
  });
});

btncloseNotiBarRes.addEventListener("click", () =>
  toggleShowElement(notiBarRes)
);

btnUserbar.addEventListener("click", () => toggleShowElement(userBar));

// Funcionalidad mostrar cantidad de notificaciones
const notifications = document.querySelectorAll(".notification");
const notiCounts = document.querySelectorAll(".noti_icon_count");

notiCounts.forEach((notiCount) => {
  if (notifications.length > 0) {
    notiCount.style.visibility = "visible";
    notiCount.innerHTML = notifications.length / 2;
  } else {
    document.querySelector(".notifications__box").innerHTML =
      '<div class="empty">No hay notificaciones</div>';
  }
});

// función toggle para mostrar barra de menu responsive
const menuBarResponsive = document.getElementById("menuBarResponsive");
const btnToggleBar = document.querySelectorAll(".btnToggleBar");

btnToggleBar.forEach((btn) => {
  btn.addEventListener("click", () => toggleShowElement(menuBarResponsive));
});

// funcion para cambiar imagen al colocar link
const formImg = document.querySelector(".form__img");
const linkImage = document.getElementById("linkImage");
if (linkImage) {
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
