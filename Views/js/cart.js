// Funcion showModal cart
const cartModal = document.querySelector(".cart__modal");
const cart = document.getElementById("cart");
const cartTableItems = document.getElementById("cartTableItems");
const toggleCartModal = document.querySelectorAll(".toggleShowCart");
const typeDiscount = document.getElementById("typeDiscount");
const cartCount = document.querySelectorAll(".cart_icon_count");
const totalPay = document.getElementById("totalPay");
const totalImport = document.getElementById("totalImport");
const discount = document.getElementById("discount");
const discountValue = document.getElementById("discountValue");
const btnApplyDiscount = document.getElementById("btnApplyDiscount");
const btnClientSearch = document.getElementById("btnClientSearch");
const formSell = document.getElementById("formSell");

document.addEventListener("click", function (e) {
  if (cartModal.contains(e.target) && !cart.contains(e.target)) {
    cartModal.classList.remove("show");
  }
});

toggleCartModal.forEach((btn) => {
  btn.addEventListener("click", () => toggleShowElement(cartModal));
});

// peticion para ADDITEM
async function addProduct(e) {
  try {
    e.preventDefault();

    const req = await axios.post(
      `${serverURL}/Request/cartRequest.php`,
      new FormData(e.target)
    );
    const res = await req.data;

    alertRequest(res);
    getDataCart();
    getItemsCart();
  } catch (error) {
    console.log(error);
  }
}

async function getDataCart() {
  try {
    const req = await axios.post(
      `${serverURL}/Request/cartRequest.php`,
      new URLSearchParams("action=getDataCart")
    );
    const res = await req.data;

    cartCount.forEach(
      (cartCountItem) => (cartCountItem.innerHTML = res.items_count)
    );
    discount.innerHTML = "S/" + res.discount.toFixed(2);
    totalPay.innerHTML = "S/" + res.total_pay;
    totalImport.innerHTML = "S/" + res.total_import;

    cartCount.forEach((cartCountItem) => {
      if (res.items_count < 1) {
        cartCountItem.style.visibility = "hidden";
      } else {
        cartCountItem.style.visibility = "visible";
      }
    });
  } catch (error) {
    console.log(error);
  }
}
// getDataCart();

async function getItemsCart() {
  try {
    const req = await axios.post(
      `${serverURL}/Request/cartRequest.php`,
      new URLSearchParams("action=getCart")
    );
    const res = await req.data;
    if (res.length > 0) {
      cartTableItems.innerHTML = "";
      res.forEach((item) => {
        cartTableItems.innerHTML += `
        <tr title="${item.details}">
          <td>${item.name}</td>
          <td>${item.serial_number ? item.serial_number : "N-A"}</td>
          <td>S/${item.price}</td>
          <td>${item.quantity}</td>
          <td>S/${item.total}</td>
          <td>
          <div class="actions">
            <button class="cart__table__btn gratify_item" data-col="${
              item.serial_number ? "serial_number" : "product_id"
            }" data-val ="${
            item.serial_number ? item.serial_number : item.product_id
          }" title="Gratificar" style="--cl:var(--c_green);"><i class="ph ph-tag"></i>
            </button>
            <button class="cart__table__btn delete_item" data-col="${
              item.serial_number ? "serial_number" : "product_id"
            }" data-val ="${
            item.serial_number ? item.serial_number : item.product_id
          }" title="Eliminar" style="--cl:red;"><i class="ph ph-trash"></i>
          </button>
          <div/>
        </td>
        </tr>
        `;
      });
    } else {
      cartTableItems.innerHTML = `
        <td aria-colspan="7" colspan="7">
          <div class="empty">
            <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/5445/5445197.png" alt="vacio" class="empty__img"></div>
            <p class="empty__message">Aun no hay productos en el carrito</p>
          </div>
        </td>
      `;
    }

    getDataCart();
    // habilitar DOm
    const removeBtns = document.querySelectorAll(".delete_item");
    removeBtns.forEach((btn) => {
      btn.addEventListener("click", () =>
        removeItem(btn.dataset.col, btn.dataset.val)
      );
    });

    const gratifyBtns = document.querySelectorAll(".gratify_item");
    gratifyBtns.forEach((btn) =>
      btn.addEventListener("click", () =>
        gratifyProduct(btn.dataset.col, btn.dataset.val)
      )
    );
  } catch (error) {
    console.log(error);
  }
}

getItemsCart();

async function removeItem(col, val) {
  try {
    const req = await axios.post(
      `${serverURL}/Request/cartRequest.php`,
      new URLSearchParams(`action=removeItem&col=${col}&val=${val}`)
    );
    const res = await req.data;

    alertRequest(res);
    getItemsCart();
    getDataCart();
  } catch (error) {
    console.log(error);
  }
}

async function applyDiscount(e, type, discount) {
  try {
    e.preventDefault();

    const req = await axios.post(
      `${serverURL}/Request/applyDiscountRequest.php`,
      new URLSearchParams(`type=${type}&discount=${discount}`)
    );
    const res = await req.data;
    alertRequest(res);
    getItemsCart();
  } catch (error) {
    console.log(error);
  }
}

async function gratifyProduct(col, val) {
  try {
    const req = await axios.post(
      `${serverURL}/Request/cartRequest.php`,
      new URLSearchParams(`action=gratify&col=${col}&val=${val}`)
    );
    const res = await req.data;

    alertRequest(res);
    getItemsCart();
    getDataCart();
  } catch (error) {
    console.log(error);
  }
}

// Poner placeholder de acuerdo al dato x el q aplicar descuento
typeDiscount.addEventListener("change", () => {
  let placeholder = "";
  if (typeDiscount.value == 1) placeholder = "Descuento en %";
  else if (typeDiscount.value == 2) placeholder = "Descuento en soles";

  discountValue.placeholder = placeholder;
  discountValue.value = "";
});

// aplicar descuento
btnApplyDiscount.addEventListener("click", (e) => {
  applyDiscount(e, typeDiscount.value, discountValue.value);
  discountValue.value = "";
});

// funcion getDataclient
async function getDataClient(e) {
  try {
    e.preventDefault();

    const req = await axios.post(
      `${serverURL}/Request/getDataClientRequest.php`,
      new URLSearchParams(`id_dni_ruc=${dni_ruc.value}`)
    );
    const res = await req.data;

    if (res.Alert) {
      alertRequest(res);
      document.getElementById("clientId").value = "";
      document.getElementById("clientDNI").value = "";
      document.getElementById("clientNames").value = "";
      document.getElementById("clientLastnames").value = "";
    }
    if (!res.Alert && typeof res === "object") {
      document.getElementById("clientId").value = res.client_id;
      document.getElementById("clientDNI").value = res.dni;
      document.getElementById("clientNames").value = res.names;
      document.getElementById("clientLastnames").value = res.lastnames;
    }
  } catch (error) {
    console.log(error);
  }
}

btnClientSearch.addEventListener("click", (e) => getDataClient(e));

// generate sell
formSell.addEventListener("submit", (e) => sendFormRequest(e));
