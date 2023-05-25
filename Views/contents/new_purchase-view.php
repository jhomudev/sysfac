<div class="purchase__box">
  <div class="purchase__top">
    <h1 class="purchase__top__text"><i class="ph ph-cardholder"></i>&nbsp; Nueva compra</h1>
    <a href="<?php echo SERVER_URL; ?>/compras" class="purchase__top__text"><i class="ph ph-clock-counter-clockwise"></i>&nbsp; Volver</a>
  </div>
  <div class="purchase__bottom">
    <div class="purchase__entries">
      <h1 class="purchase__title"><i class="ph ph-user-circle-gear"></i> Datos del Proveedor</h1>
      <input type="hidden" name="tx_supplier_id" id="supplierId">
      <fieldset class="form__group">
        <legend class="form__label">RUC*</legend>
        <input type="text" class="form__input" id="supplierRUC" list="listSuppliers" minlength="11" maxlength="11" number placeholder="Buscar por RUC">
        <datalist id="listSuppliers">
          <?php
          require_once "./Controllers/SupplierController.php";
          $IS = new SupplierController();

          $suppliers = json_decode($IS->getSuppliersController());

          foreach ($suppliers as $key => $supplier) {
            echo '<option value="' . $supplier->RUC . '">' . $supplier->name . '</option>';
          }
          ?>
        </datalist>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__label">Nombre*</legend>
        <input type="text" class="form__input" id="nameSupplier" disabled>
      </fieldset>
      <h1 class="purchase__title">Responsable</h1>
      <input type="hidden" name="tx_person_id" id="personId">
      <fieldset class="form__group">
        <legend class="form__label">DNI*</legend>
        <input type="text" class="form__input" id="personDNI" list="listPersons" placeholder="Buscar por DNI" minlength="8" maxlength="8" number>
        <datalist id="listPersons">
          <option value="00">Nuevo</option>
          <?php
          $suppliers = MainModel::executeQuerySimple("SELECT person_id, dni, CONCAT(names,' ',lastnames) AS fullname FROM persons WHERE kind=" . PERSON_TYPE->supplier);
          $suppliers = json_decode(json_encode($suppliers->fetchAll()));

          foreach ($suppliers as $key => $supplier) {
            echo '<option value="' . $supplier->dni . '">' . $supplier->fullname . '</option>';
          }
          ?>
        </datalist>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__label">Nombres*</legend>
        <input type="text" class="form__input" id="namePerson">
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__label">Apellidos*</legend>
        <input type="text" class="form__input" id="lastnamesPerson">
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__label">Teléfono</legend>
        <input type="text" class="form__input" id="phonePerson" number>
      </fieldset>
      <fieldset class="form__group">
        <legend class="form__label">Correo</legend>
        <input type="email" class="form__input" id="emailPerson">
      </fieldset>
      <h1 class="purchase__title">Productos</h1>
      <form action="" method="POST" class="purchase__products__form formFetch">
        <input type="hidden" name="tx_product_id" id="productId">
        <fieldset class="form__group">
          <legend class="form__label">Producto</legend>
          <input type="text" class="form__input" id="productName" list="listProducts" placeholder="Buscar por nombre">
          <datalist id="listProducts">
            <?php
            require_once "./Controllers/ProductController.php";
            $IP = new ProductController();

            $products = json_decode($IP->getProductsController());

            foreach ($products as $key => $product) {
              echo '<option value="' . $product->name . '">P-' . $product->product_id . '</option>';
            }
            ?>
          </datalist>
        </fieldset>
        <fieldset class="form__group">
          <legend class="form__label">Precio S/</legend>
          <input type="text" class="form__input" id="ProductPrice" disabled>
        </fieldset>
        <fieldset class="form__group">
          <legend class="form__label">Numero de serie</legend>
          <input type="text" class="form__input" id="ProductNS">
        </fieldset>
        <fieldset class="form__group">
          <legend class="form__label">Cantidad</legend>
          <input type="number" class="form__input" id="productQuantity" number>
        </fieldset>
        <input type="button" value="Agregar a compra" class="form__submit">
      </form>
      <div class="purchase__tableBox tableBox">
        <table class="purchase__table table">
          <thead class="table__thead">
            <tr>
              <th>#</th>
              <th>Producto</th>
              <th>Numero de serie</th>
              <th>Cantidad</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody class="table__tbody">
            <tr>
              <td>01</td>
              <td>Laptop Hp intel Corei 5</td>
              <td>1341565443</td>
              <td>1</td>
              <td><button class="cart__table__btn btnDeleteItem" data-id="" style="--cl:red;"><i class="ph ph-trash"></i></button></td>
            </tr>
          </tbody>
        </table>
      </div>
      <button class="purchase__btn__do form__submit" style="background:var(--c_yellow);">Realizar abastecimiento</button>
    </div>
  </div>
</div>