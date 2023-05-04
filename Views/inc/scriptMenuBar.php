<script>
  // Funcion para traer las vistas con Fetch
  // const viewBox = document.querySelector(".view");

  // async function getView(view) {
  //   try {
  //     const req = await fetch(`<?php echo SERVER_URL; ?>/Views/contents/${view}-view.php`);
  //     const res = await req.text();

  //     viewBox.innerHTML = res;
  //     onToggleForm();
  //   } catch (error) {
  //     console.log(error);
  //   }
  // }
  // fuuncion de agrgar script 
  // async function replaceScript(view) {
  //   let scriptChange = document.querySelector("[scriptchange]");
  //   scriptChange.remove();
  //   let script = document.createElement("script");
  //   script.src = `${serverURL}/Views/js/${view}.js`;
  //   script.addEventListener("load", function() {
  //     console.log('listo')
  //   });
  //   script.setAttribute('scriptchange', '');
  //   document.head.appendChild(script);

  //   const req = await fetch(`${serverURL}/Views/js/${view}.js`);
  //   const res = await req.text();
  //   eval(res);
  // }

  // Cambiar URL hacer click en los itemsNav y traer vista
  // const itemsNav = document.querySelectorAll(".nav_item_link");

  // itemsNav.forEach((item) => {
  //   item.addEventListener("click", (e) => {
  //     // e.preventDefault();
  //     history.replaceState(null, null, item.getAttribute("href"));
  //     getView(item.dataset.view);
  //     replaceScript(item.dataset.view);
  //     toggleShowElement(menuBarResponsive);
  //     selectItem();
  //   });
  // });

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
    item.addEventListener("click", () => selectItem() );
  });
</script>