<?php

require 'modelo/modelo.php';

/**
 * Clase Controlador
 */
class Controlador {

    private $modelo;
    private $mensajes;

    /**
     * constructor de la clase Controlador
     */
    public function __construct(){

        
        $this->modelo = new modelo(); //creación de un objeto modelo
        $this->mensajes = [];

    }

    /**
     * método que muestra la página inicio.php
     *
     * @return void
     */
    public function index(){

        $parametros = ["titulo" => "Mi Pequeño Blog"];
        
        //se muestra la página inicio.php
        include_once 'vistas/inicio.php';
    }

    public function insertar(){

        $errores = array();

        if (isset($_POST) && !empty($_POST) && isset($_POST["boton"])){

            $nick = $_POST["nick"];
            $nombre = $_POST["nombre"];
            $apellidos = $_POST["apellidos"];
            $email = $_POST["email"];
            $password = sha1($_POST["password"]);
            $imagen = NULL;

            //si existe un archivo tipo imagen y no está vacío el campo
            if (isset($_FILES["imagen"]) and (!empty($_FILES["imagen"]["tmp_name"]))){

                if (!is_dir("fotos")){ //si no existe un directorio llamado 'fotos', lo creará

                    $carpeta = mkdir("fotos", 0777, true);

                } else {

                    $carpeta = true;
                }

                if ($carpeta){ //si está el directorio fotos, la imagen se moverá a dicho directorio

                    $nombreImagen= time()."-".$_FILES["imagen"]["name"];

                    $moverImagen = move_uploaded_file($_FILES["imagen"]["tmp_name"], "fotos/".$nombreImagen);

                    $imagen = $nombreImagen;

                    if ($moverImagen){

                        $imgCargada = true;

                    } else {

                        $imgCargada = false;

                        $errores["imagen"] = "Error: La imagen no se cargó correctamente :(";
                    }

                }
            }

            if (count($errores) == 0) {
                $resultado = $this->modelo->insertar(['nick' => $nick,'nombre' => $nombre, 
                'apellidos' => $apellidos,"password" => $password,'email' => $email,
                'imagen' => $imagen]);

                if ($resultado["correcto"]){

                  $this->mensajes[] = ["tipo" => "success",
                  "mensaje" => "El usuarios se registró correctamente!! :)"];

                } else {

                  $this->mensajes[] = [
                      "tipo" => "danger",
                      "mensaje" => "El usuario no pudo registrarse!! :( <br />({$resultado["error"]})"
                  ];

                }

              } else {

                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "Datos de registro de usuario erróneos!! :("
                ];
                
              }
        }

        $parametros = [
            "tituloventana" => "Base de Datos con PHP y PDO",
            "datos" => [
                "nick" => isset($nick) ? $nick : "",
                "nombre" => isset($nombre) ? $nombre : "",
                "apellidos" => isset($apellidos) ? $apellidos : "",
                "password" => isset($password) ? $password : "",
                "email" => isset($email) ? $email : "",
                "imagen" => isset($imagen) ? $imagen : ""
            ],
            "mensajes" => $this->mensajes
        ];

        include_once 'vistas/insertar.php';


    }


    public function listar(){

        $parametros = ["tituloventana" => "Base de Datos con PHP y PDO","datos" => NULL,"mensajes" => []];

        $resultado = $this->modelo->listar();

        if ($resultado["correcto"]){

            $parametros["datos"] = $resultado["datos"];

            $this->mensajes[] = ["tipo" => "success",
            "mensaje" => "El listado se realizó correctamente"];

        } else {

            $this->mensajes[] = ["tipo" => "danger","mensaje" => 
            "No se pudieron listar los usuarios correctamente <br/>({$resultado["error"]})"];
        }

        $parametros["mensajes"] = $this->mensajes;

        include_once 'vistas/listar.php';
    }


}

?>