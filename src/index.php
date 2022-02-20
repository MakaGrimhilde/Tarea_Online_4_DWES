<?php

require_once 'controlador/controlador.php';

//definición de objeto tipo controlador
$controlador = new Controlador();

if ($_GET && isset($_GET["accion"])){

    //se sanitizan los datos recibidos mediante el GET
    $accion = filter_input(INPUT_GET, "accion", FILTER_SANITIZE_STRING);

     //se comprueba que el objeto controlador implemente el método que se le ha pasado por GET
     if (method_exists($controlador, $accion)){

        //se comprueba si el método tiene como parámetro el id del usuario
        /*if($accion = "actualizar" || $accion = "eliminar"){

            if (isset($_GET['id']) && (is_numeric($_GET['id']))){

                $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
                $controlador->$accion($id);
                
            } else {

                $controlador->$accion();
            }

        } */

        $controlador->$accion();

     } else { //si el método no existe en el Controlador
            
        $controlador->index(); //se redirige a la página inicio.php mediante el método index()
        
    }

} else {

    $controlador->index(); //se redirige a la página inicio.php mediante el método index()
}
    

?>