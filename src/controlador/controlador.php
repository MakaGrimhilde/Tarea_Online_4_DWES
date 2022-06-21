<?php

require '../modelo/modelo.php'; //se añade la clase modelo para poder acceder a sus métodos
require "../phpmailer/Exception.php";
require "../phpmailer/PHPMailer.php";
require "../phpmailer/SMTP.php";
require '../pdf/vendor/autoload.php';

require '../excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPMailer\PHPMailer\PHPMailer;

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
     * método que muestra la página de inicio, login.php en este caso
     *
     * @return void
     */
    public function index(){

        //se muestra la página inicio.php
        include_once '../vistas/login.php';
    }

    /**
     * función que filtra los valores introducidos en los campos del formulario
     *
     * @param string $dato
     * @return string $dato 
     */
    function filtrar($dato){

        $dato = trim($dato); 
        $dato = stripslashes($dato); 
        $dato = htmlspecialchars($dato); 
        
        return $dato;

    }


    //FUNCIONES  PARA TRABAJAR CON USUARIOS

    /**
     * método que permite insertar un registro en la tabla usuarios de la base de datos bdblog
     *
     * @return void
     */
    public function insertar(){

        $errores = array(); //array para almacenar los errores que se puedan generar

        //si se ha pulsado el botón del formulario y ha recibido datos
        if (isset($_POST) and !empty($_POST) and isset($_POST["boton"])){

            //se almacenan en las variables los valores que se obtengan de cada campo del formulario si todo está correcto

            //NICK
            if (!empty($_POST["nick"])){

                $nick = $_POST["nick"];

            } else {

                $errores["nick"] = "El nick es obligatorio";
            }

            //NOMBRE
            if (!empty($_POST["nombre"]) and strlen($_POST["nombre"]) <= 20 and !preg_match("/[0-9]/", $_POST["nombre"])
            and !is_numeric($_POST["nombre"])){

                //se filtran y sanitizan los valores introducidos
                $nombre = $this->filtrar($_POST["nombre"]);
                $nombre = filter_var($nombre, FILTER_SANITIZE_STRING);
           

            } else { //de lo contrario, se mostrará el siguiente mensaje de error

                $errores["nombre"] = "El nombre solo puede estar formado por letras y tener una longitud
                máxima de 20 caracteres";

            }

            //APELLIDOS
            //si el campo no está vacío y cumple los criterios de validación para el formulario
            if (!empty($_POST["apellidos"]) and !preg_match("/[0-9]/", $_POST["apellidos"]) && 
            !is_numeric($_POST["apellidos"])){

                //se filtran y sanitizan los valores introducidos
                $apellidos = $this->filtrar($_POST["apellidos"]);
                $apellidos = filter_var($apellidos, FILTER_SANITIZE_STRING);
                

            } else { //de lo contrario, se mostrará el siguiente mensaje de error

                $errores["apellidos"] = "El apellido solo puede estar formado por letras";
            }

            //EMAIL
            //si el campo no está vacío y cumple los criterios de validación para el formulario
            if (!empty($_POST["email"]) and filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){

                //se filtran y sanitizan los valores introducidos
            $email = $this->filtrar($_POST["email"]);
            $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
            

            } else { //de lo contrario, se mostrará el siguiente mensaje de error

                $errores["email"] = "El email tiene que ser válido";

            }

            //CONTRASEÑA
            //si el campo no está vacío y cumple los criterios de validación para el formulario
            if (!empty($_POST["password"]) and strlen($_POST["password"]) >= 6){

                $password = sha1($_POST["password"])."<br/>";

            } else { //de lo contrario, se mostrará el siguiente mensaje de error

                $errores["password"] = "La contraseña debe tener una longitud mayor que 6 caracteres";
            
            }

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

                    $nombreImagen = $_FILES["imagen"]["name"];

                    $moverImagen = move_uploaded_file($_FILES["imagen"]["tmp_name"], "../fotos/".$nombreImagen);

                    $imagen = $nombreImagen;

                    if ($moverImagen){

                        $imgCargada = true;

                    } else {

                        $imgCargada = false;

                        $errores["imagen"] = "Error al cargar la imagen";
                    }

                }
            }


            //ZONA DE LLAMADA A LA FUNCIÓN INSERTAR DEL MODELO

            if (count($errores) == 0) { //si no hay errores

                //se llama al método insertar de la clase modelo
                $resultado = $this->modelo->insertar(['nick' => $nick,'nombre' => $nombre, 'apellidos' => $apellidos,
                "password" => $password, 'email' => $email, 'imagen' => $imagen]);

                if ($resultado["correcto"]){

                  $this->mensajes[] = ["tipo" => "success", "mensaje" => "El usuario se registró correctamente<br>"];

                } else {

                  $this->mensajes[] = ["tipo" => "danger", "mensaje" => "El usuario no pudo registrarse"];

                }

            } else {

                $this->mensajes[] = ["tipo" => "danger", "mensaje" => "Datos de registro de usuario erróneos"];
                
            }
        } 

        //se alamcenan los datos recibidos por POST
        $parametros = ["datos" => ["nick" => isset($nick) ? $nick : "",
                "nombre" => isset($nombre) ? $nombre : "",
                "apellidos" => isset($apellidos) ? $apellidos : "",
                "password" => isset($password) ? $password : "",
                "email" => isset($email) ? $email : "",
                "imagen" => isset($imagen) ? $imagen : ""],
                "mensajes" => $this->mensajes];

        
        include_once '../vistas/insertar.php'; //se visualiza la vista que aparece al registrar un usuario

    }


    /**
     * método que permite obtener todos los registros de la tabla usuarios 
     *
     * @return void
     */
    public function listar(){

        //se almacenan en este array los valores que se van a mostrar en la vista
        $parametros = ["datos" => NULL, "pagina" => NULL, "num_paginas" => NULL, "mensajes" => []];

        //LLAMADA AL MÉTODO LISTAR DE LA CLASE MODELO
        $resultado = $this->modelo->listar();

        if ($resultado["correcto"]){ //si no hay errores en la operación listar

            //los datos obtenidos se transfieren al array parametros["datos"]
            $parametros["datos"] = $resultado["datos"];
            $parametros["pagina"] = $resultado["pagina"];
            $parametros["num_paginas"] = $resultado["num_paginas"]; 

            $this->mensajes[] = ["tipo" => "success", "mensaje" => "El listado se realizó correctamente <br/>"];

        } else {

            $this->mensajes[] = ["tipo" => "danger", "mensaje" => "No se pudieron listar los usuarios correctamente <br/>"];
        }

        /**
         * se asigna al campo 'mensajes' del array parametros el valor del atributo mensaje mostrando 
         * lo que ocurrió al realizarse la operación
         */
        $parametros["mensajes"] = $this->mensajes; 

        include_once '../vistas/listar.php'; //se visualiza la vista en la que aparecerán los registros

    }

    /**
     * función que obtiene los datos de un registro concreto según su id mediante la función listarUsuario del modelo
     *
     * @return void
     */
    public function listarUsuario(){

        $id = $_GET["id"]; //se obtiene mediante GET la id

        if (isset($_GET["id"]) and is_numeric($_GET["id"])){ //si existe un id y es numérico

            //LLAMADA AL MÉTODO ELIMINAR DE LA CLASE MODELO MEDIANTE EL ID
            $resultado = $this->modelo->listarUsuario($id);

            if ($resultado["correcto"]){ //si se ha eliminado correctamente

                //los datos obtenidos se transfieren al array parametros["datos"]
                $parametros["datos"] = $resultado["datos"];

                $this->mensajes[] = ["tipo" => "success","mensaje" => "El usuario se listó correctamente<br>"];

            } else {

                $this->mensajes[] = ["tipo" => "danger","mensaje" => "Error al listar el usuario"];
            }

        } else { //si no se ha podido acceder al usuario por su id

            $this->mensajes[] = ["tipo" => "danger","mensaje" => "No se han podido mostrar los datos del usuario"];
        }

        /**
         * se asigna al campo 'mensajes' del array parametros el valor del atributo mensaje mostrando 
         * lo que ocurrió al realizarse la operación
         */
        $parametros["mensajes"] = $this->mensajes; 

        include_once '../vistas/listarUsuario.php'; //se visualiza la vista en la que aparecerán los registros

    }


    /**
     * método que permite actualizar los campos de un registro de la tabla usuarios mediante la función del modelo
     *
     * @return void
     */
    public function actualizar(){

        $errores = array(); //array para almacenar los errores que se puedan generar

        //variables para almacenar los valores de cada campo a actualizar
        

        //si se ha pulsado el botón de actualizar
        if (isset($_POST["boton"])) {
             
            $id = $_POST["id"]; //se recibe el id por el campo oculto

            //se almacenan en las variables los nuevos valores que se obtengan de cada campo del formulario si todo es correcto

            //NOMBRE
            //si el campo no está vacío y cumple los criterios de validación para el formulario
            if (!empty($_POST["nombre"]) and strlen($_POST["nombre"]) <= 20 and !preg_match("/[0-9]/", $_POST["nombre"])
            && !is_numeric($_POST["nombre"])){

                //se filtran y sanitizan los valores introducidos
                $nuevoNombre = $this->filtrar($_POST["nombre"]);
                $nuevoNombre = filter_var($nuevoNombre, FILTER_SANITIZE_STRING);
            
            } else { //de lo contrario, se mostrará el siguiente mensaje de error

                $errores["nombre"] = "El nombre solo puede estar formado por letras y tener una longitud
                máxima de 20 caracteres";
            }


            //APELLIDOS
             //si el campo no está vacío y cumple los criterios de validación para el formulario
            if (!empty($_POST["apellidos"]) and !preg_match("/[0-9]/", $_POST["apellidos"]) and
            !is_numeric($_POST["apellidos"])){

                //se filtran y sanitizan los valores introducidos
                $nuevoApellidos = $this->filtrar($_POST["apellidos"]);
                $nuevoApellidos = filter_var($nuevoApellidos, FILTER_SANITIZE_STRING);
                

            } else { //de lo contrario, se mostrará el siguiente mensaje de error

                $errores["apellidos"] = "El apellido solo puede estar formado por letras";
            }
           
            
            //EMAIL
            //si el campo no está vacío y cumple los criterios de validación para el formulario
            if (!empty($_POST["email"]) and filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){

                //se filtran y sanitizan los valores introducidos
                $nuevoEmail = $this->filtrar($_POST["email"]);
                $nuevoEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
            

            } else { //de lo contrario, se mostrará el siguiente mensaje de error

                $errores["email"] = "El email tiene que ser válido";

            }
            

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

                    $nombreImagen = $_FILES["imagen"]["name"];

                    $moverImagen = move_uploaded_file($_FILES["imagen"]["tmp_name"], "../fotos/".$nombreImagen);

                    $imagen = $nombreImagen;

                    if ($moverImagen){

                        $imgCargada = true;

                    } else {

                        $imgCargada = false;

                        $errores["imagen"] = "La imagen no se cargó correctamente";

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

                $this->mensajes[] = ["tipo" => "success", "mensaje" => "El usuario se actualizó correctamente"];

              }else{

                $this->mensajes[] = ["tipo" => "danger", "mensaje" => "El usuario no pudo ser actualizado"];

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

                        $this->mensajes[] = ["tipo" => "danger", "mensaje" => "No se pudieron obtener los datos de usuario"];
                    }
                }
          }
          
          //en el array se introducen los valores a rellenar en la página de actualizar
          $parametros = ["datos" => ["nombre" => $valorNombre, "apellidos" => $valorApellidos,"email" => $valorEmail, 
              "imagen" => $valorImagen],"mensajes" => $this->mensajes];
          
          //se añade la vista en donde aparecerá el formulario con los datos del usuario a actualizar
          include_once '../vistas/actualizar.php'; 

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

            if ($resultado["correcto"]){ //si se ha eliminado correctamente

                $this->mensajes[] = ["tipo" => "success", "mensaje" => "El usuario se eliminó correctamente<br>"];

            } else {

                $this->mensajes[] = ["tipo" => "danger", "mensaje" => "Error al eliminar el usuario"];
            }

        } else { //si no se ha podido acceder al usuario por su id

            $this->mensajes[] = ["tipo" => "danger", "mensaje" => "No se pudo acceder al usuario que se intenta eliminar"];
        }

        $this->listar(); //se listan los usuarios, ya sin aparecer el que acaba de ser eliminado
    }

    /**
     * función que obtiene todos los registros de usuarios para la vista en PDF
     *
     * @return void
     */
    public function vistaPdf(){

        $parametros = ["datos" => NULL];
       
        $resultado = $this->modelo->listarPdf();
       
        $parametros["datos"] = $resultado["datos"];
          
        include_once '../vistas/print_vista.php';
    }


    
    //FUNCIONES  PARA TRABAJAR CON ENTRADAS
    
    /**
     * función que permite insertar entradas a la base de datos mediante la función del modelo
     *
     * @return void
     */
    public function insertarEntradas(){

        $errores = array(); //array para almacenar los errores que se puedan generar

        //si se ha pulsado el botón del formulario y ha recibido datos
        if (isset($_POST) and !empty($_POST) and isset($_POST["boton"])){

            //se almacenan en las variables los valores que se obtengan de cada campo del formulario
            $titulo = $_POST["titulo"];
            $descripcion = $_POST["descripcion"];

            //condicionales para asignar el valor de categoría según el día y mes en el que se publique la entrada
            $diaHoy = date('m-d');

            if($diaHoy >= '09-23' && $diaHoy < '12-21'){

                $categoria = 1;

            } else if($diaHoy >= '12-21' && $diaHoy < '03-20'){

                $categoria = 2;

            } else if($diaHoy >= '03-20' && $diaHoy < '06-21'){

                $categoria = 3;

            } else if($diaHoy >= '06-21' && $diaHoy < '09-23'){

                $categoria = 4;
            }


            $usuario_id = $_COOKIE["permisos"];
            $imagen = NULL;
            $fecha = date('Y-m-d H:i:s');


            //IMAGEN
            //si existe un archivo tipo imagen y no está vacío el campo
            if (isset($_FILES["imagen"]) and (!empty($_FILES["imagen"]["tmp_name"]))){

                if (!is_dir("fotos")){ //si no existe un directorio llamado 'fotos', lo creará

                    $carpeta = mkdir("fotos", 0777, true);

                } else {

                    $carpeta = true;
                }

                if ($carpeta){ //si está el directorio fotos, la imagen se moverá a dicho directorio

                    $nombreImagen = $_FILES["imagen"]["name"];

                    $moverImagen = move_uploaded_file($_FILES["imagen"]["tmp_name"], "../fotos/".$nombreImagen);

                    $imagen = $nombreImagen;

                    if ($moverImagen){

                        $imgCargada = true;

                    } else {

                        $imgCargada = false;

                        $errores["imagen"] = "Error al cargar la imagen";
                    }

                }
            }


            //ZONA DE LLAMADA A LA FUNCIÓN INSERTAR DEL MODELO

            if (count($errores) == 0) { //si no hay errores

                //se llama al método insertar entradas de la clase modelo
                $resultado = $this->modelo->insertarEntrada(['usuario_id' => $usuario_id, 'categoria_id' => $categoria,
                'titulo' => $titulo,'descripcion' => $descripcion,'imagen' => $imagen, 'fecha' => $fecha]);

                if ($resultado["correcto"]){

                  $this->mensajes[] = ["tipo" => "success", "mensaje" => "La entrada se ha subido correctamente"];

                } else {

                  $this->mensajes[] = ["tipo" => "danger", "mensaje" => "La entrada no pudo publicarse <br/>"];

                }

            } else {

                $this->mensajes[] = ["tipo" => "danger", "mensaje" => "Errores al registrar la entrada"];
                
            }
        }

        $parametros = ["datos" => [
                "titulo" => isset($titulo) ? $titulo : "",
                "descripcion" => isset($descripcion) ? $descripcion : "",
                "imagen" => isset($imagen) ? $imagen : ""],
                "mensajes" => $this->mensajes];


        include_once '../vistas/insertarEntradas.php';

    }

    /**
     * función que obtiene los registros de la tabla entradas mediante la función del modelo
     *
     * @return void
     */
    public function listarEntradas(){

        //se almacenan en este array los valores que se van a mostrar en la vista
        $parametros = ["datos" => NULL, "pagina" => NULL, "num_paginas" => NULL, "mensajes" => []];

        //LLAMADA AL MÉTODO LISTAR DE LA CLASE MODELO
        $resultado = $this->modelo->listarEntradas();

        if ($resultado["correcto"]){ //si no hay errores en la operación listar

            //los datos obtenidos se transfieren al array parametros["datos"]
            $parametros["datos"] = $resultado["datos"];
            $parametros["pagina"] = $resultado["pagina"];
            $parametros["num_paginas"] = $resultado["num_paginas"]; 

            $this->mensajes[] = ["tipo" => "success", "mensaje" => "El listado se realizó correctamente <br/>"];

        } else {

            $this->mensajes[] = ["tipo" => "danger", "mensaje" => "No se pudieron listar las entradas correctamente <br/>"];
        }

        /**
         * se asigna al campo 'mensajes' del array parametros el valor del atributo mensaje mostrando 
         * lo que ocurrió al realizarse la operación
         */
        $parametros["mensajes"] = $this->mensajes; 

        include_once '../vistas/listarEntradas.php'; //se visualiza la vista en la que aparecerán los registros

    }

    /**
     * función que permite listar una entrada según su id mediante la función del modelo
     *
     * @return void
     */
    public function listarEntrada(){

        $id = $_GET["id"]; //se obtiene mediante GET la id

        if (isset($_GET["id"]) and is_numeric($_GET["id"])){ //si existe un id y es numérico

            //LLAMADA AL MÉTODO LISTARENTRADA DE LA CLASE MODELO MEDIANTE EL ID
            $resultado = $this->modelo->listarEntrada($id);

            if ($resultado["correcto"]){ //si se ha eliminado correctamente

                //los datos obtenidos se transfieren al array parametros["datos"]
                $parametros["datos"] = $resultado["datos"];

                $this->mensajes[] = ["tipo" => "success","mensaje" => "Entrada listada correctamente<br>"];

            } else {

                $this->mensajes[] = ["tipo" => "danger","mensaje" => "Error al listar la entrada"];
            }

        } else { //si no se ha podido acceder al usuario por su id

            $this->mensajes[] = ["tipo" => "danger", "mensaje" => "No se ha podido mostrar la entrada en detalle"];
        }

        /**
         * se asigna al campo 'mensajes' del array parametros el valor del atributo mensaje mostrando 
         * lo que ocurrió al realizarse la operación
         */
        $parametros["mensajes"] = $this->mensajes; 

        include_once '../vistas/listarEntrada.php'; //se visualiza la vista en la que aparecerá la entrada en cuestión

    }


    /**
     * método que permite actualizar los campos de un registro de la tabla usuarios
     *
     * @return void
     */
    public function actualizarEntrada(){

        $errores = array(); //array para almacenar los errores que se puedan generar

        //variables para almacenar los valores de cada campo a actualizar
        $valorTitulo = "";
        $valorDescripcion = "";
        $valorImagen = "";


        //si se ha pulsado el botón de actualizar
        if (isset($_POST["boton"])) {
             
            $id = $_POST["id"]; //se recibe el id por el campo oculto

            //se almacenan en las variables los nuevos valores que se obtengan de cada campo del formulario
            $nuevoTitulo = $_POST["titulo"];
            $nuevaDescripcion = $_POST["descripcion"];
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

                    $nombreImagen = $_FILES["imagen"]["name"];

                    $moverImagen = move_uploaded_file($_FILES["imagen"]["tmp_name"], "../fotos/".$nombreImagen);

                    $imagen = $nombreImagen;

                    if ($moverImagen){

                        $imgCargada = true;

                    } else {

                        $imgCargada = false;

                        $errores["imagen"] = "Error al cargar la imagen";

                    }

                }
            }

            $nuevaImagen = $imagen; //la variable almacenará la nueva imagen subida


            //ZONA DE LLAMADA A LA FUNCIÓN INSERTAR DEL MODELO

            if (count($errores) == 0){ //si no hay errores

              //se llama al método actualizar de la clase modelo
              
              $resultado = $this->modelo->actualizarEntrada(['id' => $id,'titulo' => $nuevoTitulo, 
              'descripcion' => $nuevaDescripcion,'imagen' => $nuevaImagen]);
              
              if ($resultado["correcto"]){ //si no se encuentran errores durante la operación

                $this->mensajes[] = ["tipo" => "success", "mensaje" => "Entrada editada correctamente"];

              } else{

                $this->mensajes[] = ["tipo" => "danger", "mensaje" => "La entrada no se pudo editar<br/>"];

              }

            } else {

              $this->mensajes[] = ["tipo" => "danger", "mensaje" => "Los datos son erroneos"];

            }
      
            //se almacenan los nuevos valores obtenidos del formulario de actualizar usuario
            $valorTitulo = $nuevoTitulo;
            $valorDescripcion = $nuevaDescripcion;
            $valorImagen = $nuevaImagen;

        } else { //se rellenan los campos del formulario con los valores obtenidos del usuario

                if (isset($_GET['id']) && (is_numeric($_GET['id']))) {

                    $id = $_GET['id'];

                    //se llama al método del modelo para listar los datos de un usuario concreto según su id
                    $resultado = $this->modelo->listarEntrada($id);
                    
                    //se generan los mensajes correspondientes según si hubo errores o no en la operación
                    if ($resultado["correcto"]){

                        $this->mensajes[] = ["tipo" => "success", "mensaje" => "Los datos de la entrada se obtuvieron correctamente"];

                        $valorTitulo = $resultado["datos"]["titulo"];
                        $valorDescripcion = $resultado["datos"]["descripcion"];
                        $valorImagen = $resultado["datos"]["imagen"];

                    } else{

                        $this->mensajes[] = ["tipo" => "danger", "mensaje" => "No se pudieron obtener los datos de la entrada"];
                    }
                }
          }
          
          //en el array se introducen los valores a rellenar en la página de actualizar
          $parametros = ["datos" => ["titulo" => $valorTitulo, "descripcion" => $valorDescripcion, 
              "imagen" => $valorImagen],"mensajes" => $this->mensajes];
          
          //se añade la vista en donde aparecerá el formulario con los datos del usuario a actualizar
          include_once '../vistas/actualizarEntrada.php'; 

    }


    /**
     * método que permite eliminar un registro de la tabla mediante su id
     *
     * @return void
     */
    public function eliminarEntrada(){

        $id = $_GET["id"]; //se obtiene mediante GET la id

        if (isset($_GET["id"]) and is_numeric($_GET["id"])){ //si existe un id y es numérico

            //LLAMADA AL MÉTODO ELIMINAR DE LA CLASE MODELO MEDIANTE EL ID
            $resultado = $this->modelo->eliminarEntrada($id);

            if ($resultado["correcto"]){ //si se ha eliminado correctamente

                $this->mensajes[] = ["tipo" => "success","mensaje" => "Entrada eliminada correctamente<br>"];

            } else {

                $this->mensajes[] = ["tipo" => "danger","mensaje" => "Error al eliminar la entrada"];
            }

        } else { //si no se ha podido acceder a la entrada en cuestión

            $this->mensajes[] = ["tipo" => "danger", "mensaje" => "No se pudo acceder a la entrada que se intenta eliminar"];
        }

        $this->listarEntradas(); //se listan las entradas, ya sin aparecer la que acaba de ser eliminada
    }


    public function buscar(){

        if(isset($_GET["buscar"]) and !empty($_GET["buscar"])){

            $busqueda = $_GET["buscar"];

            $resultado = $this->modelo->buscar($busqueda);

            if ($resultado["correcto"]){

                $parametros["datos"] = $resultado["datos"]; 

            }

        }

        include_once '../vistas/buscar.php';
    }


    //FUNCIONES PARA LA TABLA LOGS

    /**
     * función que obtiene todos los registros de la tabla logs mediante la función del modelo
     *
     * @return void
     */
    public function listarLogs(){

        $parametros = ["datos" => NULL, "mensajes" => []];
       
        $resultado = $this->modelo->listarLogs(); //llamada a la función del modelo
        
        if ($resultado["correcto"]){

          $parametros["datos"] = $resultado["datos"];
          
          $this->mensajes[] = ["tipo" => "success", "mensaje" => "El listado se realizó correctamente"];

        } else {

            $this->mensajes[] = ["tipo" => "danger", "mensaje" => "No se pudieron recuperar los registros de Logs <br/>"];

        }

        $parametros["mensajes"] = $this->mensajes;
       
        include_once '../vistas/listarLogs.php';
    }

    /**
     * función que obtiene los datos de un log concreto según su id mediante la funcion del modelo
     *
     * @return void
     */
    public function listarLog(){

        $id = $_GET["id"]; //se obtiene mediante GET la id

        if (isset($_GET["id"]) and is_numeric($_GET["id"])){ //si existe un id y es numérico

            //LLAMADA AL MÉTODO LISTARLOG DE LA CLASE MODELO MEDIANTE EL ID
            $resultado = $this->modelo->listarLog($id);

            if ($resultado["correcto"]){ //si se ha eliminado correctamente

                //los datos obtenidos se transfieren al array parametros["datos"]
                $parametros["datos"] = $resultado["datos"];

                $this->mensajes[] = ["tipo" => "success", "mensaje" => "Operación listada correctamente<br>"];

            } else {

                $this->mensajes[] = ["tipo" => "danger", "mensaje" => "Error al listar la operación"];
            }

        } else { //si no se ha podido acceder al usuario por su id

            $this->mensajes[] = ["tipo" => "danger", "mensaje" => "No se han podido mostrar la operación en detalle"];
        }

        $parametros["mensajes"] = $this->mensajes; 

        include_once '../vistas/listarLog.php'; //se visualiza la vista en la que aparecerá el log en cuestión
    }

    /**
     * función que elimina un registro de logs según su id mediante la función del modelo
     *
     * @return void
     */
    public function eliminarLog(){

        $id = $_GET["id"]; //se obtiene mediante GET la id

        if (isset($_GET["id"]) and is_numeric($_GET["id"])){ //si existe un id y es numérico

            //LLAMADA AL MÉTODO ELIMINARLOG DE LA CLASE MODELO MEDIANTE EL ID
            $resultado = $this->modelo->eliminarLog($id);

            if ($resultado["correcto"]){ //si se ha eliminado correctamente

                $this->mensajes[] = ["tipo" => "success","mensaje" => "Operación del log eliminada correctamente<br>"];

            } else {

                $this->mensajes[] = ["tipo" => "danger","mensaje" => "Error al eliminar la operación"];
            }

        } else { //si no se ha podido acceder a la entrada en cuestión

            $this->mensajes[] = ["tipo" => "danger","mensaje" => "No se pudo acceder a la operación del log que se intenta eliminar"];
        }

        $this->listarLogs();

    }

    /**
     * función que obtiene todos los registros de logs para mostrarlos en la vista del PDF
     *
     * @return void
     */
    public function vistaPdfLogs(){

        $parametros = ["datos" => NULL];
       
        $resultado = $this->modelo->listarLogs();
       
        $parametros["datos"] = $resultado["datos"];
          
        include_once '../vistas/print_vistaLogs.php';
    }

    
    //EXCEL

    /**
     * función que exporta en una hoja de excel los registros que obtiene de la tabla usuarios
     *
     * @return void
     */
    public function exportarExcel(){

        $resultado = $this->modelo->listadoExcel(); //llamada a la función listar del modelo

        //creación de objeto Spreadsheet y definición de la hoja
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //definición de las columnas de la hoja
        $sheet->setTitle('usuarios');
        $sheet->setCellValue('A1', 'id');
        $sheet->setCellValue('B1', 'nick');
        $sheet->setCellValue('C1', 'nombre');
        $sheet->setCellValue('D1', 'apellidos');
        $sheet->setCellValue('E1', 'email');
        $sheet->setCellValue('F1', 'imagen');

        $fila = 2;

        //bucle que añade a cada columna las filas que encuentra en la tabla usuarios
        while($filas = $resultado->fetch(PDO::FETCH_ASSOC)){

            $sheet->setCellValue('A'.$fila, $filas["id"]);
            $sheet->setCellValue('B'.$fila, $filas["nick"]);
            $sheet->setCellValue('C'.$fila, $filas["nombre"]);
            $sheet->setCellValue('D'.$fila, $filas["apellidos"]);
            $sheet->setCellValue('E'.$fila, $filas["email"]);
            $sheet->setCellValue('F'.$fila, $filas["imagen"]);

            $fila++;
        }

        //headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="usuarios.xlsx"');
        header('Cache-Control: max-age=0');

        //genera el fichero y lo guarda
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    /**
     * función que permite importar hojas excel mediante las que insertar usuarios a la tabla
     *
     * @return void
     */
    public function importarExcel(){

        if(isset($_POST['botonExcel'])){ //si se pulsa el botón

            //obtiene el name del input file
            $nombreExcel = $_FILES['excel']['name']; 
            $extension = pathinfo($nombreExcel, PATHINFO_EXTENSION);

            $tipo = ['xls','csv','xlsx']; //extensiones permitidas

            if(in_array($extension, $tipo)){ //si se ha subido un archivo permitido

                //se carga el documento mediante la librería
                $inputFileNamePath = $_FILES['excel']['tmp_name'];
                $spreadsheet = IOFactory::load($inputFileNamePath);
                $datos = $spreadsheet->getActiveSheet()->toArray();

                //se definen las filas en las que irá cada dato a subir
                foreach($datos as $row){

                    $nick = $row["0"];
                    $nombre = $row["1"];
                    $apellidos = $row["2"];
                    $email = $row["3"];
                    $password = sha1($row["4"]);
                    $imagen = $row["5"];

                    //se llama a la función insertar usuarios del modelo 
                    $resultado = $this->modelo->insertar(['nick' => $nick, 'nombre' => $nombre,
                'apellidos' => $apellidos, 'email' => $email, 'password' => $password, 'imagen' => $imagen]);
                
                }

                $this->mensajes[] = ["tipo" => "success", "mensaje" => "Registros añadidos correctamente"];


            } else {

                $this->mensajes[] = ["tipo" => "danger", "mensaje" => "Error al subir el archivo"];
            }
        }

        $parametros = ["datos" => ["nick" => $nick, "nombre" => $nombre, "apellidos" => $apellidos,
        "email" => $email, "password" => $password, "imagen" => $imagen], "mensajes" => $this->mensajes];

        $this->listar();
        
    }

    
    //PHPMAILER

    public function enviarEmail(){

        if(isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['mensaje'])){

            $nombre = $_POST['nombre'];
            $correo = $_POST['email'];
            $mensaje = $_POST['mensaje'];

            $email = new PHPMailer();
            $email->isSMTP();
            $email->Host = "ssl://smtp.gmail.com";
            $email->Port = 587;
            $email->SMTPSecure = 'tls';
            $email->SMTPAutoTLS = false;
            $email->SMTPAuth = false;
            $email->Username = "pruebaservidoremail@gmail.com";
            $email->Password = "ABCD1234!";
            $email->setFrom("pruebaservidoremail@gmail.com","Pepito");
            $email->addAddress("pruebaservidoremail@gmail.com");
            $email->isHTML(true); 
            $email->Subject = "Hola buenas tardes";
            $email->Body = 'Nombre: '.$nombre.'<br/>Correo: '.$correo.'<br/>'.$mensaje;
            
            if(!$email->send()){

                echo $email->ErrorInfo;

            }
            
            include_once '../vistas/contacto.php';

        }

    }

}

?>