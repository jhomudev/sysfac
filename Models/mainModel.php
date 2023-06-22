<?php
if ($request) require_once "../config/SERVER.php";
else require_once "./config/SERVER.php";

class MainModel
{

  // Funcion para conectar a la BD
  protected static function connect()
  {
    $connection = new PDO(SGBD, USER, PASS);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $connection;
  }

  //Funcion para ejecutar consultas simples 
  public static function executeQuerySimple($query)
  {
    $sql = self::connect()->prepare($query);
    $sql->execute();
    return $sql;
  }

  // Encriptación de cadenas
  public function encryption(string $string): string
  {
    $output = FALSE;
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    $output = openssl_encrypt($string, METHOD, $key, 0, $iv);
    $output = base64_encode($output);
    return $output;
  }

  // Desencriptación de cadenas
  public function decryption(string $string): string
  {
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    $output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
    return $output;
  }

  // Generar codigos aleatorios
  public static function generateRandomcode(string $letter, int $length, int $num): string
  {
    for ($i = 0; $i < $length; $i++) {
      $random = rand(0, 9);
      $letter .= $random;
    }
    return "$letter-$num";
  }

  // Funcion para limpiar cadenas, para evitar inyecciones SQL
  public static function clearString(string $string): string
  {
    $string = trim($string);
    $string = stripslashes($string);
    $string = str_ireplace("<script>", "", $string);
    $string = str_ireplace("</script>", "", $string);
    $string = str_ireplace("</script src", "", $string);
    $string = str_ireplace("</script type=", "", $string);
    $string = str_ireplace("SELECT * FROM", "", $string);
    $string = str_ireplace("DELETE FROM", "", $string);
    $string = str_ireplace("UPDATE", "", $string);
    $string = str_ireplace("INSERT INTO", "", $string);
    $string = str_ireplace("DROP TABLE", "", $string);
    $string = str_ireplace("DROP DATABASE", "", $string);
    $string = str_ireplace("TRUNCATE TABLE", "", $string);
    $string = str_ireplace("SHOW TABLES", "", $string);
    $string = str_ireplace("SHOW DATABASES", "", $string);
    $string = str_ireplace("<?php", "", $string);
    $string = str_ireplace("?>", "", $string);
    $string = str_ireplace("--", "", $string);
    $string = str_ireplace(">", "", $string);
    $string = str_ireplace("<", "", $string);
    $string = str_ireplace("[", "", $string);
    $string = str_ireplace("]", "", $string);
    $string = str_ireplace("^", "", $string);
    $string = str_ireplace("==", "", $string);
    $string = str_ireplace(";", "", $string);
    $string = str_ireplace("::", "", $string);
    $string = stripslashes($string);
    $string = trim($string);
    return $string;
  }


  // Funcion para obtener valor limpio de un valor $_POST,
  public static function getCleanPostValue(string $name_key): string
  {
    $value = isset($_POST[$name_key]) && $_POST[$name_key] != "" ? self::clearString($_POST[$name_key]) : "";

    return $value;
  }

  // Funcion para obtener el valor limpio de un valor GET,
  public static function getCleanGetValue(string $name_key): string
  {
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // Descomponemos la URL
    $componentes_url = parse_url($url);
    // Obtenemos los valores de los parámetros
    $parametros = [];

    if (isset($componentes_url['query']) && !empty($componentes_url['query'])) parse_str($componentes_url['query'], $parametros);

    return isset($parametros[$name_key]) && $parametros[$name_key] != "" ? self::clearString($parametros[$name_key]) : "";
  }

  // Funcion para obtener los parametros GEt de la URL,
  public static function getParamsUrl(string $url = null): array
  {
    $url = $url == null ? "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : $url;

    // Descomponemos la URL
    $componentes_url = parse_url($url);
    // Obtenemos los valores de los parámetros
    $parametros = [];
    if (isset($componentes_url['query']) && !empty($componentes_url['query'])) parse_str($componentes_url['query'], $parametros);

    return $parametros;
  }

  // Funcion para validar fechas de los inputs, retorna un bool dependiendo si el dato es correcto
  protected static function verifyInputDate(string $date): bool
  {
    $values_array = explode("-", $date);
    if (count($values_array) == 3 && checkdate($values_array[1], $values_array[0], $values_array[2])) {
      return true;
    } else return false;
  }
}
