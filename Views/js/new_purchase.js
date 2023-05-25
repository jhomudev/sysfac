// Definicion de elementos
const supplierRUC = document.getElementById("supplierRUC");
const personDNI = document.getElementById("personDNI");
const productName = document.getElementById("productName");
const btnsDeleteItem = document.querySelectorAll(".btnDeleteItem");
// datalists
const listProducts = document.getElementById("listProducts");
const listPersons = document.getElementById("listPersons");
const listSuppliers = document.getElementById("listPersons");

// Funcionalidad traer datos de proveedor
supplierRUC.addEventListener("input", () => {
  console.log("first");
});

// Funcionalidad traer datos de la person responsable
personDNI.addEventListener("input", () => {
  console.log("first");
});

// Funcionalidad traer datos del producto
productId.addEventListener("input", () => {
  console.log("first");
});

// ?FUNCIONES O PETICIONES
async function getDataSUpplier() {
  try {
    const req = await fetch("link", {
      method: "POST",
      body: new URLSearchParams("RUC=" + supplierRUC.value),
    });
  } catch (error) {
    console.log(error);
  }
}
async function getDataPerson() {
  try {
    const req = await fetch("link", {
      method: "POST",
      body: new URLSearchParams("RUC=" + supplierRUC.value),
    });
    const res = await req.json();
    document.getElementById("nameSupplier").value = res.name;
  } catch (error) {
    console.log(error);
  }
}

async function getDataProduct() {
  try {
    const req = await fetch("link", {
      method: "POST",
      body: new URLSearchParams("RUC=" + supplierRUC.value),
    });
  } catch (error) {
    console.log(error);
  }
}
