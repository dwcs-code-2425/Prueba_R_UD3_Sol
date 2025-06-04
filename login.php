<div?php session_start(); require_once 'PDOSingleton.php' ; require_once 'util.php' ; ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CDN -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Login</title>
    </head>

    <body>

        <div class="container mt-5">
            <div class="d-flex justify-content-center h-100">
                <div class="card">
                    <div class="card-header">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <form method='POST' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
                            <div class="input-group form-group">

                                <input type="text" class="form-control" placeholder="usuario" name='username' required>

                            </div>
                            <div class="input-group form-group">

                                <input type="password" class="form-control" placeholder="contraseña" name='password'
                                    required>
                            </div>



                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1" name="saltar">
                                <label class="form-check-label" for="exampleCheck1">Saltar página de bienvenida</label>
                            </div>
                            <div class="form-group mt-3">
                                <input type="submit" value="Login" class="btn float-right btn-success"
                                    name='iniciarsesion'>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </body>

    <?php
    require_once 'util.php';
    iniciarSesion();
    if (isset($_SESSION["user"])) {
        //usuario autenticado
    
        if (isset($_COOKIE["saltar"])) {
            if ($_COOKIE["saltar"] == 1) {
                header('location: home.php');
                exit;
            }

        }

        header('location: bienvenida.php');
        exit;

    }


    if (isset($_POST['iniciarsesion'])) {
        $email = trim($_POST['username']);
        $pass = trim($_POST['password']);
        if (strlen($email) == 0 || strlen($pass) == 0) {
            addMensajeSesion("Error, El email o la contraseña no pueden contener solo espacios en blancos.", "danger");
        }

        if (($userId = login($email, $pass) != null)) {

            //Nos hemos validado correctamente creamos la sesion de usuario con el email de usuario
    
            iniciarSesion();
            $_SESSION['user'] = $userId;

            if (isset($_POST["saltar"])) {
                $saltar = $_POST["saltar"];

                if ($saltar) {
                    setcookie("saltar", 1, time() + 60 * 60 * 24 * 30);
                    header('location: home.php');
                    exit;
                } 
            }
            else {
                    header('location: bienvenida.php');
                    exit;
                }



        } else {
            addMensajeSesion("Las credenciales no son correctas", "danger");
        }

    }

    mostrarMensajeSesion();
    ?>

    </html>