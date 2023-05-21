// Funcion showModal cart
const cartModal = document.querySelector(".cart__modal");
const cart = document.getElementById("cart");
// const cartTitle = document.querySelector(".cart__title");
const toggleCartModal = document.querySelectorAll(".toggleShowCart");
const typeProof = document.getElementById("typeProof");
const cartCount = document.querySelectorAll(".cart_icon_count");
const totalPay = document.getElementById("totalPay");
const totalImport = document.getElementById("totalImport");
const discount = document.getElementById("discount");
const discountvalue = document.getElementById("discountvalue");
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

    const config = {
      method: "POST",
      body: new FormData(e.target),
    };
    const req = await fetch(`${serverURL}/fetch/cartFetch.php`, config);
    const res = await req.json();
    console.log(res);
    alertFetch(res);
    getDataCart();
    getItemsCart();
  } catch (error) {
    console.log(error);
  }
}

async function getDataCart() {
  try {
    const config = {
      method: "POST",
      body: new URLSearchParams("action=getDataCart"),
    };
    const req = await fetch(`${serverURL}/fetch/cartFetch.php`, config);
    const res = await req.json();

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
getDataCart();

async function getItemsCart() {
  try {
    const cartTableItems = document.getElementById("cartTableItems");
    const config = {
      method: "POST",
      body: new URLSearchParams("action=getCart"),
    };
    const req = await fetch(`${serverURL}/fetch/cartFetch.php`, config);
    const res = await req.json();
    if (res.length > 0) {
      cartTableItems.innerHTML = "";
      res.forEach((item) => {
        cartTableItems.innerHTML += `
        <tr title="${item.details}">
          <td>${item.name + "" + res.length}</td>
          <td>${item.serial_number ? item.serial_number : "N-A"}</td>
          <td>S/${item.price}</td>
          <td>${item.quantity}</td>
          <td>S/${item.total}</td>
          <td><button class="cart__table__btn" data-col="${
            item.serial_number ? "serial_number" : "product_id"
          }" data-val ="${
          item.serial_number ? item.serial_number : item.product_id
        }" title="Eliminar" style="--cl:red;"><i class="ph ph-trash"></i></button></td>
        </tr>
        `;
      });
    } else {
      cartTableItems.innerHTML = `
        <td aria-colspan="7" colspan="7">
          <div class="empty">
            <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/5445/5445197.png" alt="vacio" class="empty__img"></div>
          </div>
          <p class="empty__message">Aun no hay productos en el carrito</p>
        </td>
      `;
    }

    getDataCart();

    const removeBtns = document.querySelectorAll(".cart__table__btn");
    removeBtns.forEach((btn) => {
      btn.addEventListener("click", () =>
        removeItem(btn.dataset.col, btn.dataset.val)
      );
    });
  } catch (error) {
    console.log(error);
  }
}
getItemsCart();

async function removeItem(col, val) {
  try {
    const config = {
      method: "POST",
      body: new URLSearchParams(`action=removeItem&col=${col}&val=${val}`),
    };
    const req = await fetch(`${serverURL}/fetch/cartFetch.php`, config);
    const res = await req.json();
    console.log(res);
    alertFetch(res);
    getItemsCart();
    getDataCart();
  } catch (error) {
    console.log(error);
  }
}

async function applyDiscount(e, discount) {
  try {
    e.preventDefault();

    const config = {
      method: "POST",
      body: new URLSearchParams("discount=" + discount),
    };
    const req = await fetch(
      `${serverURL}/fetch/applyDiscountFetch.php`,
      config
    );
    const res = await req.json();
    alertFetch(res);
    getItemsCart();
  } catch (error) {
    console.log(error);
  }
}

btnApplyDiscount.addEventListener("click", (e) => {
  applyDiscount(e, discountvalue.value);
  discountvalue.value = "";
});

// Poner placeholder de acuerdo al dato x el q buscar cliente
typeProof.addEventListener("change", () => {
  let doc = "";
  if (typeProof.value == 1) doc = "DNI";
  else if (typeProof.value == 2) doc = "RUC";

  dni_ruc.placeholder = "Escriba el " + doc;
});

// funcion getDataclient
async function getDataClient(e) {
  try {
    e.preventDefault();

    const req = await fetch(`${serverURL}/fetch/getDataClientFetch.php`, {
      method: "POST",
      body: new URLSearchParams(
        `typeProof=${typeProof.value}&dni_ruc=${dni_ruc.value}`
      ),
    });
    const res = await req.json();
    if (res.Alert) {
      alertFetch(res);
      document.getElementById("clientId").value = "";
      document.getElementById("clientDNI").value = "";
      document.getElementById("clientRUC").value = "";
      document.getElementById("clientNames").value = "";
      document.getElementById("clientLastnames").value = "";
    }
    if (!res.Alert && typeof res === "object") {
      document.getElementById("clientId").value = res.person_id;
      document.getElementById("clientDNI").value = res.dni;
      document.getElementById("clientRUC").value = res.RUC;
      document.getElementById("clientNames").value = res.names;
      document.getElementById("clientLastnames").value = res.lastnames;
    }
  } catch (error) {
    console.log(error);
  }
}

btnClientSearch.addEventListener("click", (e) => getDataClient(e));

// generate sell
formSell.addEventListener("submit", (e) => sendFormFetch(e));
