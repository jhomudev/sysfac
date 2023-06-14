<?php
require_once "./Controllers/UserController.php";
require_once "./Controllers/ProductController.php";
require_once "./Controllers/SellController.php";
require_once "./Controllers/PurchaseController.php";

$IUser = new UserController();
$IProduct = new ProductController();
$ISell = new SellController();
$IPurchase = new PurchaseController();

$filters = [
  "words" => "",
  "column" => "",
  "value" => "",
];
?>
<section class="dashboard">
  <div class="ent__box">
    <article class="ent box__dash" style="--cl:var(--c_sky);">
      <a href="<?php echo SERVER_URL; ?>/productos" class="ent__link"></a>
      <div class="ent__box__info">
        <span class="ent__box__icon">
          <i class="ph ph-dropbox-logo"></i>
        </span>
        <div class="ent__data">
          <strong class="ent__quantity"><?php echo count(json_decode($IProduct->getProductsController())); ?></strong>
          <p class="ent__name">Productos</p>
        </div>
      </div>
      <svg class="ent__svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#0099ff" fill-opacity="1" d="M0,288L30,277.3C60,267,120,245,180,229.3C240,213,300,203,360,208C420,213,480,235,540,208C600,181,660,107,720,106.7C780,107,840,181,900,181.3C960,181,1020,107,1080,69.3C1140,32,1200,32,1260,32C1320,32,1380,32,1410,32L1440,32L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path></svg>
    </article>
    <article class="ent box__dash" style="--cl:var(--c_yellow);">
      <a href="<?php echo SERVER_URL; ?>/usuarios" class="ent__link"></a>
      <div class="ent__box__info">
        <span class="ent__box__icon">
          <i class="ph ph-user-gear"></i>
        </span>
        <div class="ent__data">
          <strong class="ent__quantity"><?php echo count(json_decode($IUser->getUsersController())); ?></strong>
          <p class="ent__name">Usuarios</p>
        </div>
      </div>
      <svg class="ent__svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#0099ff" fill-opacity="1" d="M0,288L30,277.3C60,267,120,245,180,229.3C240,213,300,203,360,208C420,213,480,235,540,208C600,181,660,107,720,106.7C780,107,840,181,900,181.3C960,181,1020,107,1080,69.3C1140,32,1200,32,1260,32C1320,32,1380,32,1410,32L1440,32L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path></svg>
    </article>
    <article class="ent box__dash" style="--cl:var(--c_orange);">
      <a href="<?php echo SERVER_URL; ?>/ventas" class="ent__link"></a>
      <div class="ent__box__info">
        <span class="ent__box__icon">
          <i class="ph ph-ticket"></i>
        </span>
        <div class="ent__data">
          <strong class="ent__quantity"><?php echo count(json_decode($ISell->getSellsController())); ?></strong>
          <p class="ent__name">Ventas</p>
        </div>
      </div>
      <svg class="ent__svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#0099ff" fill-opacity="1" d="M0,288L30,277.3C60,267,120,245,180,229.3C240,213,300,203,360,208C420,213,480,235,540,208C600,181,660,107,720,106.7C780,107,840,181,900,181.3C960,181,1020,107,1080,69.3C1140,32,1200,32,1260,32C1320,32,1380,32,1410,32L1440,32L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path></svg>
    </article>
    <article class="ent box__dash" style="--cl:var(--c_green)">
      <a href="<?php echo SERVER_URL; ?>/compras" class="ent__link"></a>
      <div class="ent__box__info">
        <span class="ent__box__icon">
          <i class="ph ph-shopping-cart"></i>
        </span>
        <div class="ent__data">
          <strong class="ent__quantity"><?php echo count(json_decode($IPurchase->getPurchasesController())); ?></strong>
          <p class="ent__name">Compras</p>
        </div>
      </div>
      <svg class="ent__svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#0099ff" fill-opacity="1" d="M0,288L30,277.3C60,267,120,245,180,229.3C240,213,300,203,360,208C420,213,480,235,540,208C600,181,660,107,720,106.7C780,107,840,181,900,181.3C960,181,1020,107,1080,69.3C1140,32,1200,32,1260,32C1320,32,1380,32,1410,32L1440,32L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path></svg>
    </article>
  </div>
  <div class="grid">
    <div class="sales__box box__dash">
      <h1 class="graphic__title">Análisis de movimientos</h1>
      <p class="graphic__descri">El gráfico muestra las ventas y compras mensuales a lo largo del tiempo. Puede ver cómo las transacciones han variado mes a mes.</p>
      <canvas class="graphic" id="graphicSales"></canvas>
    </div>
    <div class="box__bottom box__dash states">
      <h1 class="graphic__title">Gráfico por estados</h1>
      <p class="graphic__descri">Mostramos la cantidad de productos correspondiente a cada estado.</p>
      <canvas class="graphic" id="graphicStates"></canvas>
    </div>
    <div class="box__bottom box__dash bestSells">
      <h1 class="graphic__title">Productos más vendidos</h1>
      <p class="graphic__descri">Verifique los productos más con más ventas.</p>
      <canvas class="graphic" id="graphicBestSelling"></canvas>
    </div>
    <aside class="movs__box box__dash recent">
      <h1 class="graphic__title">Movimientos recientes</h1>
      <p class="graphic__descri">Observe la ventas y compras más recientes realizadas por los usuarios en el sistema.</p>
      <div class="movs">
        <ul class="movs__ul">
          <?php
          require_once './Models/MainModel.php';
          $query = "SELECT s.sell_id, s.sell_code,s.proof_code, s.operation_type, s.discount, s.total_import, s.total_pay, s.created_at, CONCAT(u.names,' ',u.lastnames) AS user FROM sells s INNER JOIN users u ON u.user_id=s.user_id ORDER BY s.created_at DESC LIMIT 10";
          $movements = MainModel::executeQuerySimple($query);
          $movements = json_decode(json_encode($movements->fetchAll()));

          if (count($movements) > 0) {
            foreach ($movements as $movement) {
              if ($movement->operation_type == OPERATION->input) {
                $icon = '<i class="ph ph-shopping-cart"></i>';
                $color = "var(--c_green)";
                $link = SERVER_URL . "/purchase?purchase_id=" . $ISell->encryption($movement->sell_code);
                $target = "";
              } else if ($movement->operation_type == OPERATION->output) {
                $icon = '<i class="ph ph-ticket"></i>';
                $color = "var(--c_orange)";
                $link = SERVER_URL . "/proof?proof_code=" . $ISell->encryption($movement->proof_code);
                $target = "_blank";
              }

              // Obtencion de tiempo transcurrido
              $date_mov = $movement->created_at; // Fecha almacenada en la base de datos
              $now = date('Y-m-d H:i:s'); // Fecha actual

              // Calcula la diferencia en minutos
              $diff_minutes = floor((strtotime($now) - strtotime($date_mov)) / 60);

              // Calcula la diferencia en horas
              $diff_hours = floor((strtotime($now) - strtotime($date_mov)) / 3600);

              // Calcula la diferencia en días
              $diff_days = floor((strtotime($now) - strtotime($date_mov)) / 86400);

              // Calcula la diferencia en semanas
              $diff_weeks = floor((strtotime($now) - strtotime($date_mov)) / 604800);

              // Calcula la diferencia en meses
              $diff_months = floor((strtotime($now) - strtotime($date_mov)) / 2592000);

              // Imprime los resultados según el intervalo de tiempo transcurrido
              $time_elapsed = "Hace un momento";
              if ($diff_minutes < 60 && $diff_minutes >= 1) {
                if ($diff_minutes == 1) $time_elapsed = "Hace un minuto";
                else $time_elapsed = "Hace $diff_minutes minutos";
              } elseif ($diff_hours < 24) {
                if ($diff_hours == 1) $time_elapsed = "Hace una hora";
                else $time_elapsed = "Hace $diff_hours horas";
              } elseif ($diff_days < 7) {
                if ($diff_days == 1) $time_elapsed = "Hace un día";
                else $time_elapsed = "Hace $diff_days días";
              } elseif ($diff_weeks < 4) {
                if ($diff_weeks == 1) $time_elapsed = "Hace una semana";
                else $time_elapsed = "Hace $diff_weeks semanas";
              } elseif ($diff_months < 12) {
                if ($diff_months == 1) $time_elapsed = "Hace un mes";
                else $time_elapsed = "Hace $diff_months mesese";
              }

              echo '
              <li class="movs__li" style="--cl:' . $color . ';">
                <a href="' . $link . '" target="' . $target . '" class="movs__link">
                  <span class="movs__item__icon">' . $icon . '</span>
                  <div class="movs__info">
                    <strong class="movs__item__username">' . $movement->user . '</strong>
                    <p class="movs__item__p"><i class="ph ph-clock"></i> ' . $time_elapsed . '</p>
                  </div>
                </a>
              </li>
              ';
            }
          } else {
            echo '
              <div class="empty">
                <div class="empty__imgBox"><img src="https://cdn-icons-png.flaticon.com/512/5445/5445197.png" alt="vacio" class="empty__img"></div>
                <p class="empty__message" style="text-align:center;">Las ventas y compras realizadas aparecerán aquí.</p>
              </div>
            ';
          }
          ?>
        </ul>
      </div>
    </aside>
  </div>
</section>