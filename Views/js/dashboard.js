// FUNCIONES PARA TRAER LOS DATOS DE LOS GRAFICOS
async function getDataGraphicSales(data, year = "", month = "") {
  try {
    const req = await axios.post(
      `${serverURL}/Request/getDataGraphicRequest.php`,
      new URLSearchParams(`graphic=${data}&year=${year}&month=${month}`)
    );
    const res = await req.data;
    return res;
  } catch (error) {
    console.log(error);
  }
}

// Funcion para traer los graficos
async function getGraphicSales() {
  const data = await getDataGraphicSales("graphicSales");
  const chrt = document.getElementById("graphicSales").getContext("2d");
  new Chart(chrt, {
    type: "line",
    data: {
      labels: data.quality,
      datasets: [
        {
          label: "Ventas",
          data: data.quantity.quantity_sales,
          backgroundColor: "#e45f2b",
          borderColor: "#e45f2b",
          borderWidth: 2,
          pointRadius: 5,
        },
        {
          label: "Compras",
          data: data.quantity.quantity_purchases,
          backgroundColor: "#a0e548",
          borderColor: "#a0e548",
          borderWidth: 2,
          pointRadius: 5,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "top",
        },
        title: {
          display: true,
          text: "Evolución mensual de ventas y compras",
        },
      },
    },
  });
}

getGraphicSales();

async function getGraphicStates() {
  const data = await getDataGraphicSales("graphicStates");
  const chrt = document.getElementById("graphicStates").getContext("2d");
  new Chart(chrt, {
    type: "pie",
    data: {
      labels: data.quality,
      datasets: [
        {
          label: "Cantidad",
          data: data.quantity,
          backgroundColor: ["#9ac1f0", "#a0e548", "#e45f2b"],
          hoverOffset: 5,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "top",
        },
        title: {
          display: true,
          text: "Productos por estados",
        },
      },
    },
  });
}

getGraphicStates();

async function getGraphicBestSelling() {
  const data = await getDataGraphicSales("graphicBestSelling");
  const chrt = document.getElementById("graphicBestSelling").getContext("2d");
  new Chart(chrt, {
    type: "bar",
    data: {
      labels: data.quality,
      datasets: [
        {
          label: "Ventas",
          data: data.quantity,
          backgroundColor: [
            "coral",
            "aqua",
            "#f6c445",
            "#9ac1f0",
            "#e45f2b",
            "#a0e548",
          ],
          borderWidth: 1,
          pointRadius: 4,
        },
      ],
    },
    options: {
      indexAxis: "y",
      elements: {
        bar: {
          borderWidth: 2,
        },
      },
      responsive: true,
      plugins: {
        legend: false,
        title: {
          display: true,
          text: "Productos más vendidos",
        },
      },
    },
  });
}
getGraphicBestSelling();
