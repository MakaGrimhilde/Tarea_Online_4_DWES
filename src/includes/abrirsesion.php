<?php

    session_start();

    //si se intenta acceder a esta página sin haberse logeado ni con la sesión abierta se redirige a login
    if (!isset($_SESSION["login"]) and !isset($_COOKIE["mantener"])){

        Header('Location:login.php');

    }

?>