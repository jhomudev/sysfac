<script>
  // Funcion para traer las vistas con Fetch
  const viewBox = document.querySelector(".view");

  async function getView(view) {
    try {
      const req = await fetch(`<?php echo SERVER_URL; ?>/Views/contents/${view}-view.php`);
      const res = await req.text();

      viewBox.innerHTML = res;
      onToggleForm();
    } catch (error) {
      console.log(error);
    }
  }

  // Cambiar URL hacer click en los itemsNav y traer vista
  const itemsNav = document.querySelectorAll(".nav_item_link");

  itemsNav.forEach((item) => {
    item.addEventListener("click", (e) => {
      e.preventDefault();
      history.replaceState(null, null, item.getAttribute("href"));
      getView(item.dataset.view);
      toggleShowElement(menuBarResponsive);
    });
  });

  // seleccion de item del menu segun la URL
  const itemsNavSelect = document.querySelectorAll(".nav__link");

  function selectItem() {
    const URL = window.location.href;
    const arrURL = URL.split("/");

    itemsNavSelect.forEach((item) => {
      item.classList.remove("selected");
      if (item.dataset.view == arrURL[arrURL.length - 1])
        item.classList.add("selected");
    });
  }
  selectItem();
  itemsNavSelect.forEach((item) => {
    item.addEventListener("click", () => {
      selectItem();

      // let cssFile = document.querySelector("link[change]");
      // cssFile.setAttribute('href', `<?php echo SERVER_URL; ?>/Views/css/${item.dataset.view}.css`);
    });
  });
</script>