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
const notificationsBox = document.querySelectorAll(".notifications__box");
const notiCounts = document.querySelectorAll(".noti_icon_count");

notiCounts.forEach((notiCount) => {
  if (notifications.length > 0) {
    notiCount.style.visibility = "visible";
    notiCount.innerHTML = notifications.length / 2;
  } else {
    notificationsBox.forEach((box) => {
      box.innerHTML = `
      <div class="empty" style="background:transparent">
        <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/2326/2326137.png" alt="vacio" class="empty__img"></div>
        <p class="empty__message">No hay notificaciones</p>
      </div>
      `;
    });
  }
});

// función toggle para mostrar barra de menu responsive
const menuBarResponsive = document.getElementById("menuBarResponsive");
const btnToggleBar = document.querySelectorAll(".btnToggleBar");

btnToggleBar.forEach((btn) => {
  btn.addEventListener("click", () => toggleShowElement(menuBarResponsive));
});

// funcionalidad para cambiar imagen al colocar link
const formImg = document.querySelector(".form__img");
const btnLinkImage = document.querySelector(".btn__linkImage");
const linkImage = document.getElementById("linkImage");
if (linkImage) {
  btnLinkImage.addEventListener("click", (e) => {
    e.preventDefault();
    const link = linkImage.value
      ? linkImage.value
      : "https://cdn-icons-png.flaticon.com/512/1524/1524855.png";
    formImg.setAttribute("src", link);
    fileImage.value = "";
  });
}
// funcionalidad para cambiar imagen al colocar link
const fileImage = document.getElementById("file_cat");
if (fileImage) {
  fileImage.addEventListener("change", function (e) {
    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
      formImg.src = e.target.result;
    };

    reader.readAsDataURL(file);

    linkImage.value = "";
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

// funcionalidad para deshabiliutar campos
function disable(elements = [], boolean = true) {
  elements.forEach((element) => {
    if (boolean) {
      element.disabled = boolean;
      element.querySelector("input").required = !boolean;
      element.style.display = "none";
    } else {
      element.disabled = boolean;
      element.querySelector("input").required = !boolean;
      element.style.display = "block";
    }
  });
}