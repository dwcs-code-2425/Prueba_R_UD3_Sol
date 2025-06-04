<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <h1>Home</h1>

    <form method='post' action="cerrar.php">
        <button type="submit" class="btn btn-dark" name="cerrar">Cerrar sesión</button>
    </form>
    <?php
    require_once 'util.php';
    if (isset(($_COOKIE))) {
        foreach ($_COOKIE as $key => $value) {
            addMensajeSesion("<p>Cookie name: $key y valor : $value</p>", "primary");
        }
    } else {
        addMensajeSesion("<p>Non existen cookies</p>", "primary");
    }
    mostrarMensajeSesion();
    ?>
</body>

</html>