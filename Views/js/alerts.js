function alertFetch(alert = {}) {
  if (alert.Alert === "simple") {
    Swal.fire({
      icon: alert.icon,
      title: alert.title,
      text: alert.text,
      confirmButtonText: "Aceptar",
    });
  } else if (alert.Alert === "clear") {
    Swal.fire({
      icon: alert.icon,
      title: alert.title,
      text: alert.text,
      confirmButtonText: "Aceptar",
    });
  } else if (alert.Alert === "reload") {
    window.location.reload();
  } else if (alert.Alert === "alert&reload") {
    Swal.fire({
      icon: alert.icon,
      title: alert.title,
      text: alert.text,
      confirmButtonText: "Aceptar",
    }).then((e) => window.location.reload());
  }
}

// Funcio enviar formulario
function sendFormFetch(e, exec = () => {}) {
  e.preventDefault();
  const data = new FormData(e.target);
  const method = e.target.getAttribute("method");
  const action = e.target.getAttribute("action");

  const config = {
    method: method,
    body: data,
  };

  Swal.fire({
    title: "Estas seguro de realizar la operación?",
    text: "Esta acción es irreversible",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Aceptar",
    cancelButtonText: "Cancelar",
  }).then(async (result) => {
    try {
      if (result.isConfirmed) {
        const req = await fetch(action, config);
        const res = await req.json();
        console.log(res);
        alertFetch(res);
        exec();
      }
    } catch (error) {
      console.log(error);
    }
  });
}
