<?php
ob_start();

session_name(NAMESESSION);
session_start();
if (empty($_SESSION["token"])) {
    session_unset();
    session_destroy();
    header("Location:" . SERVER_URL . "/login");
    exit();
}

require_once "./Controllers/SellController.php";
$IS = new SellController();

$url_actual = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
// Descomponemos la URL
$componentes_url = parse_url($url_actual);
// Obtenemos los valores de los parámetros
parse_str($componentes_url['query'], $parametros);

$proof_code_enc = $parametros['proof_code'];
$proof_code = $IS->decryption($proof_code_enc);
$_GET['proof_code'] = $proof_code;

$sell = json_decode($IS->getSellDataController());
if ($sell == []) {
    echo "Comprobante $proof_code_enc inexistente.";
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo SERVER_URL; ?>/Views/assets/iconLogo.png" type="image/x-icon">
    <title>Sysfac_<?php echo $sell->proof_code; ?></title>
    <style>
        @import url(http://localhost/sysfac/Views/css/main.css);

        .hoja {
            position: relative;
            width: 660px;
            height: 1000px;
            padding: 3em;
            margin: auto;
        }

        .company {
            width: 100%;
            height: auto;
        }

        .company__img {
            width: 270px;
            height: 240px;
        }

        .company__name {
            font-size: medium;
        }

        .proof {
            position: absolute;
            outline: 2px solid #0f0f0f;
            right: 0;
            top: 40px;
            margin: 70px;
            display: flex;
            flex-direction: column;
            gap: 1em;
            text-align: center;
            padding: 30px 60px;
            border-radius: 10px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            -ms-border-radius: 10px;
            -o-border-radius: 10px;
        }

        table.data__sell {
            margin: 20px 0;
            font-size: small;
        }

        table.table__sell__items {
            width: 100%;
            font-size: small;
            border: 1px solid gray;
            border-collapse: collapse;
            text-align: center;
        }

        table.table__pay,
        table.table__sell__items {
            font-size: x-small;
        }

        table.table__pay th,
        table.table__sell__items th {
            padding: 7px 20px;
            border: 1px solid gray;
        }

        table.table__pay td,
        table.table__sell__items td {
            padding: 5px;
            border: 1px solid gray;
        }

        table.table__pay {
            border-collapse: collapse;
            width: 250px;
            font-size: small;
            float: right;
            margin-top: 2em;
        }

        table.table__pay th {
            text-align: left;
        }

        table.table__pay td {
            padding: 4px 1em;
        }

        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: small;
        }
    </style>
</head>

<body>
    <div class="hoja">
        <div class="company">
            <img src="<?php echo SERVER_URL; ?>/Views/assets/logoMax.png" class="company__img" alt="logo Sysfac">
            <h1 class="company__name">SYSTEC CORP PERÚ S.A.C.</h1>
        </div>
        <div class="proof">
            <p style="color:red;">R.U.C. 20608004590 </p>
            <p><?php echo ($sell->proof_type == TYPE_PROOF->boleta) ? "BOLETA" : "FACTURA" ?> DE VENTA </p>
            <strong style="color:blue;"><?php echo $sell->proof_code; ?></strong>
        </div>
        <table class="data__sell">
            <tr>
                <td>Cliente </td>
                <td>:&nbsp;&nbsp; <?php echo $sell->client; ?></td>
            </tr>
            <tr>
                <td><?php echo $sell->proof_type == TYPE_PROOF->boleta ? "DNI" : "RUC"; ?> </td>
                <td>:&nbsp;&nbsp; <?php echo $sell->proof_type == TYPE_PROOF->boleta ? $sell->dni : $sell->RUC; ?></td>
            </tr>
            <tr>
                <td>Fecha de emisión </td>
                <td>:&nbsp;&nbsp; <?php echo date("d-m-Y", strtotime($sell->created_at)); ?></td>
            </tr>
        </table>
        <table class="table__sell__items">
            <thead>
                <tr>
                    <th>#</th>
                    <th>DESCRIPCIÓN</th>
                    <th>CANTIDAD</th>
                    <th>P.U.</th>
                    <th>IMPORTE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($sell->ops as $key => $op) {
                    $op = json_decode(json_encode($op));
                    $ns = ($op->serial_number) ? " / N.S." . $op->serial_number : "";
                    echo '
                        <tr>
                            <td>' . sprintf("%02d", $key + 1) . '</td>
                            <td style="text-align:left;">' . MainModel::executeQuerySimple("SELECT name FROM products WHERE product_id=" . $op->product_id)->fetchColumn() . $ns . '</td>
                            <td>' . $op->quantity . '</td>
                            <td>S/' . $op->price . '</td>
                            <td>S/' . $op->import . '</td>
                        </tr>
                        ';
                }
                ?>
            </tbody>
        </table>

        <table class="table__pay">
            <tr>
                <th>Subtotal</th>
                <td> S/ <?php echo $sell->total_import; ?></td>
            </tr>
            <tr>
                <th>IGV 18%</th>
                <td> S/ <?php echo $sell->total_import * 0.18; ?></td>
            </tr>
            <tr>
                <th>Descuento</th>
                <td> S/ <?php echo $sell->discount; ?></td>
            </tr>
            <tr>
                <th>Importe total</th>
                <td> S/ <?php echo $sell->total_pay; ?></td>
            </tr>
        </table>
        <footer class="footer">
            <p>Representación impresa de comprobante electrónico. Fecha <?php echo date("d-m-Y") ?> Código Hash: <?php echo $proof_code_enc; ?></p>
        </footer>
    </div>
</body>

</html>

<?php
$html = ob_get_clean();
// echo $html;

require_once 'libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('A4');

$dompdf->render();

$dompdf->stream("recibo_.pdf", array("Attachment" => false));
?>