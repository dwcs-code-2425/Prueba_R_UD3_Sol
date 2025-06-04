<?php
require_once 'util.php';
if(isset($_POST["cerrar"])){
    cerrarSesion()    ;

    header('location: login.php');
    exit;
}