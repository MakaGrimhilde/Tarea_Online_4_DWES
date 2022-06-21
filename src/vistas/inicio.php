
<html lang="es">
    <head>
        <?php require '../includes/head.php';?>
    </head>
    <body>
        <?php require '../includes/header.php';?>
        <br/><br/><br/>
        <div class="row justify-content-center">
            <h2>Mi Pequeño Blog - Inicio</h2>
            <?php

                session_start();

                //si se intenta acceder a esta página sin haberse logeado ni con la sesión abierta se redirige a login
                if (!isset($_SESSION["login"]) and !isset($_COOKIE["mantener"])){

                    Header('Location:../login.php?error=fuera');

                }

            ?>
        </div>
        <br/>
        <div class="row justify-content-center">
            <h4>Bienvenido/a <?php 
                
                //muestra el nombre de usuario almacenado en la cookie de mantener sesión
                if (isset($_COOKIE["mantener"])){

                    echo $_COOKIE["mantener"];

                } else { //si no, se muestra el nombre almacenado al iniciar sesión

                    echo $_SESSION["usuario"];

                }
                
                ?></h4>
        </div>
        <br>
        <div class="row justify-content-center">
            <img src="../img/blog.png" width="700" height="350">
        </div>
        <br>
    </body>
</html>