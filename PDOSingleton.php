<?php


/**
 * Description of PDOSingleton
 *
 * @author wadmin
 */
class PDOSingleton
{


    private static ?PDO $connection = null;
    //fichero con datos de configuración de acceso a DB
    private static $ruta_fichero = "database.ini";

    private function __construct()
    {

    }

    private function __clone()
    {
    }

    public static function getInstance()
    {

        try {
            if (is_null(self::$connection)) {


                if (!$settings = parse_ini_file(self::$ruta_fichero, TRUE)) {
                    throw new Exception('Unable to open ' . self::$ruta_fichero . '.');
                }

                $dns = $settings['database']['driver'] .
                    ':host=' . $settings['database']['host'] .
                    ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') .
                    ';dbname=' . $settings['database']['schema'];

                self::$connection = new PDO(
                    $dns,
                    $settings['database']['username'],
                    $settings['database']['password'],
                    // array(
                    //     PDO::ATTR_PERSISTENT => $settings['database']['persistent']
                    // )
                );
            }
            return self::$connection;
        } catch (Exception $e) {
            error_log("Excepción en la obtención de la conexión: " .$e->getTraceAsString());
            addError("No se ha podido crear la conexión");
            throw $e;
        }
    }
}