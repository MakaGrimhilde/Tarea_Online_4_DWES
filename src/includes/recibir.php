<?php

//variables para el usuario o contraseña válidos para el login de la página
$nicks = null;
$passwords = null;

$resultado = $this->modelo->login();

foreach ($resultado["datos"] as $d){

    $nicks[] = $d['nick'];
    $passwords[] = $d['password'];
}



if (isset($_POST["boton"])){ //si existe el botón

    //si existe el campo usuario y contraseña y si no están vacíos
    if (isset($_POST["usuario"]) and isset($_POST["password"]) || !empty($_POST["usuario"] and !empty($_POST["password"]))){

        //se comprueba que los valores introducidos en usuario y contraseña coincidan con las variables creadas previamente
        if (in_array($_POST['usuario'], $nicks) and in_array(sha1($_POST['password']), $passwords)){

            //se crea una sesión con el usuario
            session_start();
            $_SESSION["login"] = $_POST["usuario"];
            $_SESSION["usuario"] = $_POST["usuario"];

            $permisos = $this->modelo->permisos($_POST["usuario"]);
            $usuario = $permisos["datos"]["id"];

            setcookie("usuario", $_POST["usuario"], time() + (365 * 24 * 60 * 60));
            setcookie("permisos", $usuario, time() + (365 * 24 * 60 * 60));

            //si existe el campo recuerdame y se ha seleccionado
            if (isset($_POST["recuerdame"]) and ($_POST["recuerdame"] == "on")){

                //se crean cookies para contraseña y para recordar el usuario
                
                setcookie("password", $_POST["password"], time() + (365 * 24 * 60 * 60));
                setcookie("recuerdame", $_POST["recuerdame"], time() + (30 * 24 * 60 * 60));

            } else { //de lo contrario se eliminan las cookies


                if (isset($_COOKIE["password"])){

                    setcookie("password", "");
                }

                if (isset($_COOKIE["recuerdame"])){

                    setcookie("recuerdame", "");
                }
            }

            //si se ha seleccionado la casilla de mantener la sesión abierta se crea una cookie
            if (isset($_POST["mantener"]) and $_POST["mantener"] == "on"){

                setcookie("mantener", $_POST["usuario"], time() + (15 * 24 * 60 * 60));

            } else { //si no se borra la cookie

                if (isset($_COOKIE["mantener"])){

                    setcookie("mantener", "");

                }
            }

            Header('Location:../vistas/inicio.php'); //se redirige a la página de inicio

        } else { //si se introducen usuario o contraseña incorrectos devuelve a la página de login.php

            Header('Location:../vistas/login.php?error=dato');
        }

    }

}

?>