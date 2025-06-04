<?php
require_once 'PDOSingleton.php';
function iniciarSesion(): bool
{
    $iniciada = true;
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $iniciada = session_start();
    }
    return $iniciada;
}


function addMensajeSesion(string $msg, string $tipo)
{
    iniciarSesion();
    $_SESSION["msg"][$tipo][] = $msg;
}

function mostrarMensajeSesion()
{

    if (isset($_SESSION['msg'])) {
        foreach ($_SESSION["msg"] as $key => $array) {
            echo "<div class=\"alert alert-$key\" role=\"alert\">";
            foreach ($array as $error) {
                echo "<p>$error</p>";

            }
        }



        unset($_SESSION['msg']);
        echo "</div>";
    }
}

function register(string $email, string $pass): string
{

    try {

        $conn = PDOSingleton::getInstance();

        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO users( email, password_hash, is_verified ) VALUES (:username, :hashp, :is_verified) ");

        $pwdhash = password_hash($pass, PASSWORD_BCRYPT);
        $stmt->bindParam("username", $email);
        $stmt->bindParam("hashp", $pwdhash);
        //para simplificar vamos a suponer que el email se ha verificado automáticamente
        $stmt->bindValue("is_verified", true);


        $stmt->execute();




        $userId = $conn->lastInsertId();



        $stmt2 = $conn->prepare("INSERT INTO email_verifications (
   
    user_id,
    token ) VALUES (?, ?)");
        $token = generarToken();
        $stmt2->execute([$userId, $token]);

        $conn->commit();

        return $token;
    } catch (Exception $ex) {

        error_log("No se ha podido crear el usuario $email: " . $ex->getTraceAsString().  " " . $ex->getMessage());
        $conn->rollBack();
        addMensajeSesion("No se ha podido crear el usuario", "danger");
        return false;
    } finally {
        $conn = false;
        $stmt = false;
        $stmt2 = false;
    }

}

function login(string $user, string $pass): ?int
{

    try {

        //ESta consulta está mal, arréglala
        $consulta = "select * from users where email = ?";
        $conProyecto = PDOSingleton::getInstance();
        $stmt = $conProyecto->prepare($consulta);

        $stmt->bindValue(1, $user);

        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario === false) {
            addMensajeSesion("Error, Nombre de usuario o password incorrecto", "danger");
            return null;
        } else {
            if( password_verify($pass, $usuario["password_hash"])){
                return $usuario["id"];
            } else
                return null;
        }
    } catch (Exception $ex) {
        error_log("Ha ocurrido una excepción en login $user: " . $ex->getTraceAsString());
        addMensajeSesion("Error en la consulta a la base de datos en login.", "danger");
    } finally {
        $conProyecto = null;
        $stmt = null;
    }
    return false;

}


function generarToken($length = 32)
{
    return bin2hex(random_bytes($length));
}

function cerrarSesion(){
    //Tal y como se recomienda en https://www.php.net/manual/es/function.session-destroy.php
      iniciarSesion();
  
    //Vaciamos el array
    $_SESSION = array();
  
    if (ini_get("session.use_cookies")) {
        //obtenemos los parámetros de creación de la cookie de sesión
        $params = session_get_cookie_params();
        //borramos la cookie de sesión
        setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
        );
    }
    //Eliminamos los datos relacionados con la sesión en el almacenamiento servidor 
    session_destroy();
  
    //Eliminamos la cookie de noMostrar
      setcookie("saltar", "", time() - 1000);
  
  }
