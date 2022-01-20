<?php

class Controlador {

    private $modelo;
    private $mensajes;

    public function __construct(){

        $this->modelo = new Modelo();
        $this->mensajes = [];
    }


}

?>