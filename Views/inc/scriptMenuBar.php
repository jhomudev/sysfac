<script>
  // seleccion de item del menu segun la URL
  const itemsNavSelect = document.querySelectorAll(".nav_item_link");

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