<?php

require 'modelo/modelo.php';

class Controlador {

    private $modelo;
    private $mensajes;

    public function __construct(){

        $this->modelo = new modelo();
        $this->mensajes = [];
    }

    public function index(){

        $parametros = ["titulo" => "Mi Pequeño Blog"];
        
        //se muestra la página inicio.php
        include_once 'vistas/inicio.php';
    }


}

?>