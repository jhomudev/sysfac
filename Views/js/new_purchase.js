// Definicion de elementos
const TableItems = document.getElementById("TableItems");
const supplierRUC = document.getElementById("supplierRUC");
const productName = document.getElementById("productName");
const productNS = document.getElementById("productNS");
const productQuantity = document.getElementById("productQuantity");
const productProfit = document.getElementById("productProfit");
const productPrice = document.getElementById("productPrice");
const productPriceSale = document.getElementById("productPriceSale");
const formAdd = document.querySelector(".purchase__products__form");

const formsFetch = document.querySelectorAll(".formFetch");

// datalists
const listProducts = document.getElementById("listProducts");
const listPersons = document.getElementById("listPersons");
const listSuppliers = document.getElementById("listPersons");

// Funcionalidad traer datos de proveedor
supplierRUC.addEventListener("input", () => {
  getDataSupplier(supplierRUC.value);
});

// Funcionalidad traer datos del producto
productName.addEventListener("input", () => {
  getDataProduct(productName.value);
});

// ?FUNCIONES O PETICIONES PARA ENTRADAS
async function getDataSupplier(supplierRUC) {
  try {
    const req = await fetch(`${serverURL}/fetch/getDataSupplierFetch.php`, {
      method: "POST",
      body: new URLSearchParams("supplierIdRUC=" + supplierRUC),
    });
    const res = await req.json();

    document.getElementById("nameSupplier").value = res.name ? res.name : "";
    document.getElementById("supplierIdRUC").value = res.supplier_id
      ? res.supplier_id
      : "";
  } catch (error) {
    console.log("No encontrado");
    document.getElementById("supplierIdRUC").value = "";
  }
}

async function getDataProduct(productIdName) {
  try {
    const req = await fetch(`${serverURL}/fetch/getDataProductFetch.php`, {
      method: "POST",
      body: new URLSearchParams("productIdName=" + productIdName),
    });
    const res = await req.json();

    document.getElementById("productId").value = res.product_id
      ? res.product_id
      : "";
    if (res.sale_for == 1) {
      document.querySelector(".quantityBox").classList.remove("hidden");
      document.querySelector(".nsBox").classList.add("hidden");
    } else if (res.sale_for == 2) {
      document.querySelector(".nsBox").classList.remove("hidden");
      document.querySelector(".quantityBox").classList.add("hidden");
    }
  } catch (error) {
    console.log("No encontrado");
    document.getElementById("productId").value = "";
  }
}

//* FUNCIONES PETICIONES PARA CARRITO DE COMPRA/LISTA
async function getDataList() {
  try {
    const config = {
      method: "POST",
      body: new URLSearchParams("action=getDataList"),
    };
    const req = await fetch(`${serverURL}/fetch/cartPurchaseFetch.php`, config);
    const res = await req.json();

    const items = res.items;
    const total = res.total;

    if (items.length > 0) {
      TableItems.innerHTML = "";
      items.forEach((item, id) => {
        TableItems.innerHTML += `
        <tr">
          <td>${id.toString().length == 1 ? "0" + (id + 1) : id + 1}</td>
          <td>${item.name}</td>
          <td>${item.serial_number ? item.serial_number : "N-A"}</td>
          <td>S/${item.price.toFixed(2)}</td>
          <td>S/${item.price_sale.toFixed(2)}</td>
          <td>${item.quantity}</td>
          <td>S/${item.total.toFixed(2)}</td>
          <td><button class="cart__table__btn" data-col="${
            item.serial_number ? "serial_number" : "product_id"
          }" data-val ="${
          item.serial_number ? item.serial_number : item.product_id
        }" title="Eliminar" style="--cl:red;"><i class="ph ph-trash"></i></button></td>
        </tr>
        `;
      });
    } else {
      TableItems.innerHTML = `
        <td aria-colspan="8" colspan="8">
          <div class="empty">
            <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/5445/5445197.png" alt="vacio" class="empty__img"></div>
            <p class="empty__message">Aun no agregó productos.</p>
          </div>
        </td>
      `;
    }

    document.getElementById("total").innerHTML = "S/" + total.toFixed(2);

    // habilitar boton delete de xcada item
    const btnsDeleteItem = document.querySelectorAll(".cart__table__btn");
    btnsDeleteItem.forEach((btn) => {
      btn.addEventListener("click", () =>
        removeItem(btn.dataset.col, btn.dataset.val)
      );
    });
  } catch (error) {
    console.log(error);
  }
}
getDataList();

async function addProduct(e) {
  try {
    e.preventDefault();

    const config = {
      method: "POST",
      body: new FormData(e.target),
    };
    const req = await fetch(`${serverURL}/fetch/cartPurchaseFetch.php`, config);
    const res = await req.json();

    alertFetch(res);
    if (res.icon == "success") {
      getDataList();
      e.target.reset();
    }
  } catch (error) {
    console.log(error);
  }
}

async function removeItem(col, val) {
  try {
    const config = {
      method: "POST",
      body: new URLSearchParams(`action=removeItem&col=${col}&val=${val}`),
    };
    const req = await fetch(`${serverURL}/fetch/cartPurchaseFetch.php`, config);
    const res = await req.json();

    alertFetch(res);
    getDataList();
  } catch (error) {
    console.log(error);
  }
}

async function clearDataList() {
  try {
    const config = {
      method: "POST",
      body: new URLSearchParams(`action=clear`),
    };
    const req = await fetch(`${serverURL}/fetch/cartPurchaseFetch.php`, config);
    const res = await req.json();

    alertFetch(res);
    getDataList();
  } catch (error) {
    console.log(error);
  }
}

function calcPrice_sale() {
  let price = parseFloat(productPrice.value);
  let profit = parseInt(productProfit.value);
  let priceSale = price + (profit / 100) * price;

  productPriceSale.value = priceSale ? priceSale : "";
}

productPrice.addEventListener("input", () => calcPrice_sale());
productProfit.addEventListener("input", () => calcPrice_sale());

productNS.addEventListener("input", () => {
  productQuantity.value = "";
});
productQuantity.addEventListener("input", () => {
  productNS.value = "";
});

formAdd.addEventListener("submit", (e) => addProduct(e));

formsFetch.forEach((form) => {
  form.addEventListener("submit", (e) => {
    sendFormFetch(e, () => (window.location.href = serverURL + "/compras"));
  });
});
