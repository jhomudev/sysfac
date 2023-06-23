// TODO: DECLARACIÓN DE ELEMENTOS
const formRequest = document.querySelector(".formRequest");
const checkboxMain = document.getElementById("checkboxMain");
const checkboxAll = document.getElementsByName("p_checkeds[]");

const action = document.getElementById("action");
const localBox = document.getElementById("localBox");
const stateBox = document.getElementById("stateBox");

checkboxMain.addEventListener("change", () => {
  checkboxAll.forEach((checkbox) => {
    checkbox.checked = checkboxMain.checked;

    const row = checkbox.parentNode.parentNode;
    if (checkbox.checked) row.classList.add("checked");
    else row.classList.remove("checked");
  });
});

checkboxAll.forEach((checkbox) => {
  checkbox.addEventListener("change", () => {
    const row = checkbox.parentNode.parentNode;
    if (checkbox.checked) row.classList.add("checked");
    else row.classList.remove("checked");
  });
});

// Mostrar campo adicional al elegir accion
action.addEventListener("change", () => {
  if (action.value == "assign_local") {
    localBox.classList.remove("hidden");
    stateBox.classList.add("hidden");
  }
  if (action.value == "change_state") {
    stateBox.classList.remove("hidden");
    localBox.classList.add("hidden");
  }
});

formRequest.addEventListener("submit", (e) => {
  e.preventDefault();
  // Obtencion de los values de los checkbox seleccionados
  let arrIds = [];
  const checkboxAll = document.getElementsByName("p_checkeds[]");

  checkboxAll.forEach((checkbox) => {
    if (checkbox.checked) arrIds.push(checkbox.value);
  });

  const data = new FormData(e.target);
  data.append("prods", arrIds);
  const method = e.target.getAttribute("method");
  const action = e.target.getAttribute("action");

  const config = {
    method: method,
    url: action,
    data: data,
  };

  Swal.fire({
    title: "Estas seguro de ejecutar la operación?",
    text: "Presione Aceptar para continuar.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Aceptar",
    cancelButtonText: "Cancelar",
  }).then(async (result) => {
    try {
      if (result.isConfirmed) {
        const req = await axios(config);
        const res = await req.data;
        alertRequest(res);
      }
    } catch (error) {
      console.log(error);
    }
  });
});
