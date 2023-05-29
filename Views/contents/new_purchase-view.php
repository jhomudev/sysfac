<div class="purchase__box">
  <div class="purchase__top">
    <h1 class="purchase__top__text"><i class="ph ph-cardholder"></i>&nbsp; Nueva compra</h1>
    <a href="<?php echo SERVER_URL; ?>/compras" class="purchase__top__text"><i class="ph ph-clock-counter-clockwise"></i>&nbsp; Volver</a>
  </div>
  <div class="purchase__bottom">
    <div class="purchase__entries">
      <div class="supplier__data__box box__entries">
        <h1 class="purchase__title"><i class="ph ph-user-circle-gear"></i> Datos del Proveedor</h1>
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
      </div>
      <div class="person__supplier__data__box box__entries">
        <h1 class="purchase__title">Responsable</h1>
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
      </div>
      <div class="product__data__box box__entries">
        <h1 class="purchase__title">Productos</h1>
        <form action="" method="POST" class="purchase__products__form">
          <input type="hidden" name="tx_product_id" id="productId">
          <input type="hidden" name="action" value="Agregar">
          <fieldset class="form__group">
            <legend class="form__label">Producto*</legend>
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
          <fieldset class="form__group hidden nsBox">
            <legend class="form__label">Numero de serie*</legend>
            <input type="text" name="tx_product_ns" class="form__input" id="productNS" placeholder="Ejm: 123NZ2Z342, 54T65ZR5445">
          </fieldset>
          <fieldset class="form__group hidden quantityBox">
            <legend class="form__label">Cantidad*</legend>
            <input type="number" name="tx_quantity" class="form__input" id="productQuantity" number>
          </fieldset>
          <fieldset class="form__group">
            <legend class="form__label">Costo S/ *</legend>
            <input type="text" name="tx_product_price" class="form__input" id="productPrice" placeholder="Precio de compra por unidad" decimal>
          </fieldset>
          <fieldset class="form__group">
            <legend class="form__label">Ganancia (%)*</legend>
            <input type="text" name="tx_product_profit" class="form__input" id="productProfit" placeholder="Ganancia deseada en %" decimal>
          </fieldset>
          <fieldset class="form__group">
            <legend class="form__label">Precio de venta S/</legend>
            <input type="text" class="form__input" id="productPriceSale" placeholder="Defina la ganancia para obtener el precio de venta" decimal disabled>
          </fieldset>
          <input type="submit" value="Agregar a compra" class="form__submit">
        </form>
      </div>
      <div class="purchase__tableBox tableBox">
        <table class="purchase__table table">
          <thead class="table__thead">
            <tr>
              <th>#</th>
              <th>Producto</th>
              <th>NS</th>
              <th>Costo</th>
              <th>Precio de venta</th>
              <th>Cantidad</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody class="table__tbody" id="TableItems">
            <!-- peticion -->
          </tbody>
        </table>
      </div>
      <div class="purchase__total__pay">TOTAL: <span id="total"></span></div>
      <div class="purchase__btns__do">
        <form action="<?php echo SERVER_URL; ?>/fetch/cartPurchaseFetch.php" method="POSt" class="formFetch">
          <input type="hidden" name="action" value="clear">
          <button type="submit" class="purchase__btn__do form__submit" style="background:red;"><i class="ph ph-broom"></i> Limpiar lista de compra</button>
        </form>
        <form action="" class="form">
          <input type="hidden" name="tx_person_id" id="personId">
          <input type="hidden" name="tx_supplier_id" id="supplierIdRUC">
          <button type="submit" data-action="do" class="purchase__btn__do form__submit" style="background:var(--c_yellow);"><i class="ph ph-archive-tray"></i> Realizar compra</button>
        </form>
      </div>
    </div>
  </div>
</div>