<div class="block__head">
  <h1 class="purchase__title">Nueva compra</h1>
  <nav class="nav__views">
    <ul class="nav__views__ul">
      <li class="nav__views__li">
        <a href="<?php echo SERVER_URL; ?>/dashboard" class="nav__views__link">Home</a>
      </li>
      <li class="nav__views__li">
        <a href="<?php echo SERVER_URL; ?>/compras" class="nav__views__link">Compras</a>
      </li>
      <li class="nav__views__li">
        <a href="" class="nav__views__link">Nueva compra</a>
      </li>
    </ul>
  </nav>
</div>
<div class="purchase__box">
  <div class="purchase__bottom">
    <div class="purchase__entries">
      <div class="product__data__box box__entries">
        <h1 class="purchase__subtitle">Productos</h1>
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
        <form action="<?php echo SERVER_URL; ?>/fetch/cartPurchaseFetch.php" method="POST" class="formFetch">
          <input type="hidden" name="action" value="clear">
          <button type="submit" class="purchase__btn__do form__submit" style="background:red;"><i class="ph ph-broom"></i> Limpiar lista de compra</button>
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
      <form action="<?php echo SERVER_URL; ?>/fetch/cartPurchaseFetch.php" method="POST" class="formFetch">
        <input type="hidden" name="action" value="do">
        <input type="hidden" name="tx_supplier_id" id="supplierIdRUC">
        <div class="supplier__data__box box__entries">
          <h1 class="purchase__subtitle"><i class="ph ph-user-circle-gear"></i> Datos del Proveedor</h1>
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
        <br>
        <fieldset class="form__group">
          <legend class="form__label">Detalles adicionales de la compra</legend>
          <textarea name="tx_add_info" class="form__input" placeholder="Puede añadir información adicional, como los datos del reponsable de parte del proveedor"></textarea>
        </fieldset>
        <br>
        <button type="submit" class="purchase__btn__do form__submit" style="background:var(--c_yellow);"><i class="ph ph-archive-tray"></i> Realizar compra</button>
      </form>
    </div>
  </div>
</div>