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

// document.addEventListener("click", function (e) {
//   let clicEnElemento = e.target.closest("#menuBarResponsive");
//   if (!clicEnElemento) {
//     menuBarResponsive.classList.remove("show");
//   }
// });
