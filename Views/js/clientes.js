// Obtencion de elementos
const tbody = document.querySelector(".table__tbody");
const inputSearch = document.getElementById("inputSearch");

// Funcion obtener clientes
async function getClients(words = "") {
  try {
    const req = await fetch(`${serverURL}/fetch/getClientsFetch.php`, {
      method: "POST",
      body: new URLSearchParams("words=" + words),
    });
    const res = await req.json();
    if (res.length > 0) {
      tbody.innerHTML = "";
      res.forEach((client) => {
        tbody.innerHTML += `
        <tr>
          <td>${client.dni}</td>
          <td>${(client.RUC)?client.RUC:'N.A.'}</td>
          <td>${client.names} ${client.lastnames}</td>
          <td>${client.phone}</td>
          <td>${client.email}</td>
          <td>${client.address}</td>
        </tr>
        `;
      });
    } else {
      tbody.innerHTML = `
      <tr>
        <td aria-colspan="6" colspan="6">
          <div class="empty">
            <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/5445/5445197.png" alt="vacio" class="empty__img"></div>
          </div>
          <p class="empty__message">No hay registros</p>
        </td>
      </tr>
    `;
    }
  } catch (error) {
    console.log(error);
  }
}

getClients();

inputSearch.addEventListener("input", () => getClients(inputSearch.value));