<?php

require 'modelo/modelo.php'; //se añade la clase modelo para poder acceder a sus métodos

/**
 * Clase Controlador. Su función es obtener los datos de la base de datos para cada operación y enviarlos 
 * a las correspondientes vistas 
 */
class Controlador {

    private $modelo;
    private $mensajes;

    /**
     * Constructor de la clase Controlador, inicializa el objeto modelo y el array de mensajes
     */
    public function __construct(){

        
        $this->modelo = new modelo(); //creación de un objeto modelo
        $this->mensajes = []; //array que almacenará los mensajes que aparecerán según cada acción que se realice

    }


    //ZONA DE MÉTODOS

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

    /**
     * método que permite insertar un registro en la tabla usuarios de la base de datos bdblog
     *
     * @return void
     */
    public function insertar(){

        $errores = array(); //array para almacenar los errores que se puedan generar

        //si se ha pulsado el botón del formulario y ha recibido datos
        if (isset($_POST) && !empty($_POST) && isset($_POST["boton"])){

            //se almacenan en las variables los valores que se obtengan de cada campo del formulario
            $nick = $_POST["nick"];
            $nombre = $_POST["nombre"];
            $apellidos = $_POST["apellidos"];
            $email = $_POST["email"];
            $password = sha1($_POST["password"]);
            $imagen = NULL;


            //IMAGEN
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

                        $errores["imagen"] = "Error: La imagen no se cargó correctamente";
                    }

                }
            }


            //ZONA DE LLAMADA A LA FUNCIÓN INSERTAR DEL MODELO

            if (count($errores) == 0) { //si no hay errores

                //se llama al método insertar de la clase modelo
                $resultado = $this->modelo->insertar(['nick' => $nick,'nombre' => $nombre, 
                'apellidos' => $apellidos,"password" => $password,'email' => $email,
                'imagen' => $imagen]);

                if ($resultado["correcto"]){

                  $this->mensajes[] = ["tipo" => "success",
                  "mensaje" => "El usuario se registró correctamente"];

                } else {

                  $this->mensajes[] = [
                      "tipo" => "danger",
                      "mensaje" => "El usuario no pudo registrarse<br/>({$resultado["error"]})"
                  ];

                }

            } else {

                $this->mensajes[] = [
                    "tipo" => "danger",
                    "mensaje" => "Datos de registro de usuario erróneos"
                ];
                
            }
        }

        $parametros = ["tituloventana" => "Base de Datos con PHP y PDO","datos" => [
                "nick" => isset($nick) ? $nick : "",
                "nombre" => isset($nombre) ? $nombre : "",
                "apellidos" => isset($apellidos) ? $apellidos : "",
                "password" => isset($password) ? $password : "",
                "email" => isset($email) ? $email : "",
                "imagen" => isset($imagen) ? $imagen : ""],"mensajes" => $this->mensajes];

        
        include_once 'vistas/insertar.php'; //se visualiza la vista que aparece al registrar un usuario

    }


    /**
     * método que permite obtener todos los registros de la tabla usuarios 
     *
     * @return void
     */
    public function listar(){

        //se almacenan en este array los valores que se van a mostrar en la vista
        $parametros = ["tituloventana" => "Base de Datos con PHP y PDO","datos" => NULL,"mensajes" => []];

        //LLAMADA AL MÉTODO LISTAR DE LA CLASE MODELO
        $resultado = $this->modelo->listar();

        if ($resultado["correcto"]){ //si no hay errores en la operación listar

            //los datos obtenidos se transfieren al array parametros["datos"]
            $parametros["datos"] = $resultado["datos"]; 

            $this->mensajes[] = ["tipo" => "success",
            "mensaje" => "El listado se realizó correctamente <br/>"];

        } else {

            $this->mensajes[] = ["tipo" => "danger","mensaje" => 
            "No se pudieron listar los usuarios correctamente <br/>"];
        }

        /**
         * se asigna al campo 'mensajes' del array parametros el valor del atributo mensaje mostrando 
         * lo que ocurrió al realizarse la operación
         */
        $parametros["mensajes"] = $this->mensajes; 

        include_once 'vistas/listar.php'; //se visualiza la vista en la que aparecerán los registros

    }


    /**
     * método que permite eliminar un registro de la tabla mediante su id
     *
     * @return void
     */
    public function eliminar(){

        $id = $_GET["id"]; //se obtiene mediante GET la id

        if (isset($_GET["id"]) and is_numeric($_GET["id"])){ //si existe un id y es numérico

            //LLAMADA AL MÉTODO ELIMINAR DE LA CLASE MODELO MEDIANTE EL ID
            $resultado = $this->modelo->eliminar($id);

            if ($resultado["correcto"] == TRUE){ //si se ha eliminado correctamente

                $this->mensajes[] = ["tipo" => "success","mensaje" => "El usuario se eliminó correctamente<br>"];

            } else {

                $this->mensajes[] = ["tipo" => "danger","mensaje" => "Error al eliminar el usuario"];
            }

        } else { //si no se ha podido acceder al usuario por su id

            $this->mensajes[] = ["tipo" => "danger","mensaje" => 
            "No se pudo acceder al usuario que se intenta eliminar"];
        }

        $this->listar(); //se listan los usuarios, ya sin aparecer el que acaba de ser eliminado
    }


    /**
     * método que permite actualizar los campos de un registro de la tabla usuarios
     *
     * @return void
     */
    public function actualizar(){

        $errores = array(); //array para almacenar los errores que se puedan generar

        //variables para almacenar los valores de cada campo a actualizar
        $valorNombre = "";
        $valorApellidos = "";
        $valorEmail = "";
        $valorImagen = "";


        //si se ha pulsado el botón de actualizar
        if (isset($_POST["boton"])) {
             
            $id = $_POST["id"]; //se recibe el id por el campo oculto

            //se almacenan en las variables los nuevos valores que se obtengan de cada campo del formulario
            $nuevoNombre = $_POST["nombre"];
            $nuevoApellidos = $_POST["apellidos"];
            $nuevoEmail  = $_POST["email"];
            $nuevaImagen = "";
            
            $imagen = NULL;


            //IMAGEN
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

                        $errores["imagen"] = "La imagen no se cargó correctamente";
                        $this->mensajes[] = ["tipo" => "danger",
                            "mensaje" => "La imagen no se cargó correctamente"];

                    }

                }
            }

            $nuevaImagen = $imagen; //la variable almacenará la nueva imagen subida


            //ZONA DE LLAMADA A LA FUNCIÓN INSERTAR DEL MODELO

            if (count($errores) == 0){ //si no hay errores

              //se llama al método actualizar de la clase modelo
              
              $resultado = $this->modelo->actualizar(['id' => $id,'nombre' => $nuevoNombre, 
              'apellidos' => $nuevoApellidos,'email' => $nuevoEmail,'imagen' => $nuevaImagen]);
              
              if ($resultado["correcto"]){ //si no se encuentran errores durante la operación

                $this->mensajes[] = ["tipo" => "success",
                    "mensaje" => "El usuario se actualizó correctamente"];

              }else{

                $this->mensajes[] = ["tipo" => "danger",
                "mensaje" => "El usuario no pudo actualizarse<br/>({$resultado["error"]})"];

              }

            } else {

              $this->mensajes[] = ["tipo" => "danger","mensaje" => "Los datos son erroneos"];

            }
      
            //se almacenan los nuevos valores obtenidos del formulario de actualizar usuario
            $valorNombre = $nuevoNombre;
            $valorApellidos = $nuevoApellidos;
            $valorEmail  = $nuevoEmail;
            $valorImagen = $nuevaImagen;

        } else { //se rellenan los campos del formulario con los valores obtenidos del usuario

                if (isset($_GET['id']) && (is_numeric($_GET['id']))) {

                    $id = $_GET['id'];

                    //se llama al método del modelo para listar los datos de un usuario concreto según su id
                    $resultado = $this->modelo->listarUsuario($id);
                    
                    //se generan los mensajes correspondientes según si hubo errores o no en la operación
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
          
          //en el array se introducen los valores a rellenar en la página de actualizar
          $parametros = ["tituloventana" => "Base de Datos con PHP y PDO",
              "datos" => ["nombre" => $valorNombre, "apellidos" => $valorApellidos,"email" => $valorEmail, 
              "imagen" => $valorImagen],"mensajes" => $this->mensajes];
          
          //se añade la vista en donde aparecerá el formulario con los datos del usuario a actualizar
          include_once 'vistas/actualizar.php'; 

    }


}

?>