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
                  "mensaje" => "El usuario se registró correctamente"];

                } else {

                  $this->mensajes[] = [
                      "tipo" => "danger",
                      "mensaje" => "El usuario no pudo registrarse!! :( <br />({$resultado["error"]})"
                  ];

                }

              } else {

                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "Datos de registro de usuario erróneos"
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
            "mensajes" => $this->mensajes];

        include_once 'vistas/insertar.php';


    }


    public function listar(){

        $parametros = ["tituloventana" => "Base de Datos con PHP y PDO","datos" => NULL,"mensajes" => []];

        $resultado = $this->modelo->listar();

        if ($resultado["correcto"]){

            $parametros["datos"] = $resultado["datos"];

            $this->mensajes[] = ["tipo" => "success",
            "mensaje" => "El listado se realizó correctamente <br/>"];

        } else {

            $this->mensajes[] = ["tipo" => "danger","mensaje" => 
            "No se pudieron listar los usuarios correctamente <br/>"];
        }

        $parametros["mensajes"] = $this->mensajes;

        include_once 'vistas/listar.php';

    }

    public function eliminar(){

        $id = $_GET["id"];

            if (isset($_GET["id"]) and is_numeric($_GET["id"])){ //si existe un id y es numérico

                $resultado = $this->modelo->eliminar($id);

                if ($resultado["correcto"] == TRUE){

                    $this->mensajes[] = ["tipo" => "success","mensaje" => "El usuario se eliminó correctamente<br>"];

                } else {

                    $this->mensajes[] = ["tipo" => "danger","mensaje" => "Error al eliminar el usuario"];
                }

            } else {

                $this->mensajes[] = ["tipo" => "danger","mensaje" => 
                "No se pudo acceder al usuario que se intenta eliminar"];
            }

        $this->listar();
    }

    public function actualizar(){

        $errores = array();

        //variables para almacenar los valores de cada campo a actualizar
        $valorNombre = "";
        $valorApellidos = "";
        $valorEmail = "";
        $valorImagen = "";

        if (isset($_POST["boton"])) {
             
            $id = $_POST["id"]; //se recibe el id por el campo oculto
            $nuevoNombre = $_POST["nombre"];
            $nuevoApellidos = $_POST["apellidos"];
            $nuevoEmail  = $_POST["email"];
            $nuevaImagen = "";
      
            $imagen = NULL;
      
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

                        $errores["imagen"] = "La imagen no se cargó correctamente";
                        $this->mensajes[] = ["tipo" => "danger",
                            "mensaje" => "La imagen no se cargó correctamente"];

                    }

                }
            }

            $nuevaImagen = $imagen;
      
            if (count($errores) == 0){
              
              $resultado = $this->modelo->actualizar(['id' => $id,'nombre' => $nuevoNombre, 
              'apellidos' => $nuevoApellidos,'email' => $nuevoEmail,'imagen' => $nuevaImagen]);
              
              if ($resultado["correcto"]){

                $this->mensajes[] = ["tipo" => "success",
                    "mensaje" => "El usuario se actualizó correctamente"];

              }else{

                $this->mensajes[] = ["tipo" => "danger",
                "mensaje" => "El usuario no pudo actualizarse<br/>({$resultado["error"]})"];

              }

            } else {

              $this->mensajes[] = ["tipo" => "danger","mensaje" => "Los datos son erroneos"];

            }
      
            
            $valorNombre = $nuevoNombre;
            $valorApellidos = $nuevoApellidos;
            $valorEmail  = $nuevoEmail;
            $valorImagen = $nuevaImagen;

          } else { 

                if (isset($_GET['id']) && (is_numeric($_GET['id']))) {

                    $id = $_GET['id'];
                    $resultado = $this->modelo->listarUsuario($id);
                    

                    if ($resultado["correcto"]){

                        $this->mensajes[] = ["tipo" => "success",
                            "mensaje" => "Los datos del usuario se obtuvieron correctamente"];

                        $valorNombre = $resultado["datos"]["nombre"];
                        $valorApellidos = $resultado["datos"]["apellidos"];
                        $valorEmail  = $resultado["datos"]["email"];
                        $valorImagen = $resultado["datos"]["imagen"];

                    } else{

                        $this->mensajes[] = ["tipo" => "danger",
                        "mensaje" => "No se pudieron obtener los datos de usuario<br/>({$resultado["error"]})"];
                    }
                }
          }
          

          $parametros = ["tituloventana" => "Base de Datos con PHP y PDO",
              "datos" => ["nombre" => $valorNombre, "apellidos" => $valorApellidos,"email" => $valorEmail, 
              "imagen" => $valorImagen],"mensajes" => $this->mensajes];
          
          include_once 'vistas/actualizar.php';

    }


}

?>