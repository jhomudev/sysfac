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

$proof_code = $parametros['proof_code'];
$proof_code = $IS->decryption($proof_code);
$_GET['proof_code'] = $proof_code;

$sell = json_decode($IS->getSellDataController());

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo SERVER_URL; ?>/assets/iconLogo.png" type="image/x-icon">
    <title>Boleta_<?php echo $sell->proof_code; ?></title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "arial";
        }

        .hoja {
            position: relative;
            width: 660px;
            height: 1000px;
            padding: 40px 70px;
        }

        .empresa {
            width: 100%;
            height: auto;
        }

        .empresa img {
            width: 270px;
            height: 240px;
        }

        .empresa h1 {
            font-size: 18px;
            font-family: "Century Gothic";
            color: #010127;
        }

        .boleta {
            position: absolute;
            outline: 2px solid #0f0f0f;
            right: 30px;
            top: 40px;
            margin: 70px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: center;
            padding: 30px 60px;
            border-radius: 10px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            -ms-border-radius: 10px;
            -o-border-radius: 10px;
        }

        table.datos-cliente {
            margin: 20px 0;
            font-size: 15px;
            text-transform: uppercase;
        }

        table.detalle {
            width: 100%;
            font-size: 13px;
            border-collapse: collapse;
            text-transform: uppercase;
        }

        table.detalle th {
            padding: 7px 20px;
            background: #22a7be;
            color: white;
            border: 2px solid #2370b8;
        }

        table.detalle td {
            padding: 5px;
        }

        .seccion-2 {
            position: relative;
            width: 100%;
            margin: 20px 0;
            font-size: 14px;
        }

        .seccion-2 .aviso {
            width: 50%;
            text-align: justify;
            float: left;
            margin: 0 20px 0 0;
        }

        .seccion-2 table.paga {
            width: 45%;
            height: 50px;
            font-size: 15px;
            float: right;
        }

        .seccion-2 table.paga td {
            padding: 4px;
        }

        .seccion-2 table.paga .impo {
            background: rgb(232, 238, 241);
        }

        .seccion-2 table.paga .t-impo {
            background: rgb(210, 229, 238);
        }

        .tex-right {
            text-align: right;
        }

        footer {
            width: 100%;
            text-align: justify;
            font-size: 14px;
            margin: 250px 0 0 0;
        }
    </style>
</head>

<body>
    <div class="hoja">
        <div class="empresa">
            <img src="http://localhost/transportes/assets/img/logo.png" alt="logo">
            <h1>EMPRESA DE TRANSPORTE CARGO TRANSPORT S.A.</h1>
        </div>
        <div class="boleta">
            <p>R.U.C. 20154673626 </p>
            <p>BOLETA DE VENTA </p>
            <strong>BTVI-0000</strong>
        </div>
        <table class="datos-cliente">
            <tr height="25">
                <td>Cliente </td>
                <td>:&nbsp;&nbsp; </td>
            </tr>
            <tr height="25">
                <td>DNI </td>
                <td>:&nbsp;&nbsp;</td>
            </tr>
            <tr height="25">
                <td>Fecha </td>
                <td>:&nbsp;&nbsp; </td>
            </tr>
        </table>
        <table class="detalle">
            <th colspan="2">DETALLE</th>
            <th>IMPORTE</th>
            <tr>
                <td>SERVICIO DE TRANPORTE DE PASAJEROS</td>
                <td>RUTA : </td>
                <td class="tex-right">S/ </td>
            </tr>
            <tr>
                <td>FECHA DE VIAJE:</td>
                <td>HORA: &nbsp;&nbsp;&nbsp; ASIENTO : </td>
                <td></td>
            </tr>
            <tr>
                <td>PASAJERO:</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>DNI:</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">EMBARQUE : TERMINAL DE LA CIUDAD</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">DESEMBARQUE : TERMINAL DE LA CIUDAD </td>
                <td></td>
            </tr>
        </table>
        <div class="seccion-2">
            <div class="aviso">
                <p>EL PASAJERO deberá presentarse 45 minutos antes de la hora de su viaje para entregar su equipaje. El boleto no puedes ser postergado.</p>
                <p>Cualquier cambio deberá ser solicitado de manera presencial y hasta 6 horas antes de la hora de viaje impresa en el boleto, previo integro en caso de incremento de tarifa. Después de la hora de partida del bus, no habrá lugar a realizar cambios ni devolución de dinero.</p>
            </div>
            <table class="paga">
                <tr>
                    <td class="t-impo"><strong>SUBTOTAL</strong> </td>
                    <td class="impo tex-right"> S/ <?php echo $monto; ?></td>
                </tr>
                <tr>
                    <td class="t-impo"><strong>DESCUENTO</strong> </td>
                    <td class="impo tex-right"> S/ 0.00</td>
                </tr>
                <tr>
                    <td class="t-impo"><strong>IGV 18%</strong> </td>
                    <td class="impo tex-right"> S/</td>
                </tr>
                <tr>
                    <td class="t-impo"><strong>IMPORTE TOTAL</strong></td>
                    <td class="impo tex-right"> S/ </td>
                </tr>
            </table>
        </div>
        <footer>
            La hora de embarque en escala es referencial, ya que estando el bus en tránsito esta sujeto a factores ajenos de transporte. Al recibir el presente DOCUMENTO, acepta todos los términos y condiciones del contrato de transporte publicado en esta boleta. Debe imprimir este documento y presentarlo al momento del embarque.
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