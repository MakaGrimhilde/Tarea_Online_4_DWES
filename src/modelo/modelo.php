<?php

/**
 * Clase Modelo. Se encarga de gestionar el acceso a la base de datos en una capa especializada
 */
class modelo {

    private $conexion;
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $db = "bdblog";

    /**
     * constructor de la clase modelo. Ejecuta directamente el método conectar con la base de datos
     */
    public function __construct(){

        $this->conectar();
    }


    //ZONA DE MÉTODOS

    /**
     * método que conecta a la base de datos bdblog mediante PDO
     *
     * @return void
     */
    public function conectar(){


        try{

            $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $ex) {

            return $ex->getMessage();
        }         
    }

    /**
     * función para realizar el login obteniendo los registros de la tabla usuarios
     */
    public function login(){

        $resultado = ["correcto" => FALSE, "datos" => NULL];

        $sql = "SELECT * FROM usuarios;";

        $query = $this->conexion->query($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $datos = $query->fetchAll();

        if ($query){ 

            $resultado["correcto"] = TRUE;
            $resultado["datos"] = $datos;

        } 

        return $resultado;
    }

    /**
     * función para obtener un usuario en cuestión según el nick a la hora de crear una cookie para los permisos
     *
     * @param string $nick
     * @return void
     */
    public function permisos($nick){

        $sql = "SELECT * FROM usuarios WHERE nick = :nick;";

        $query = $this->conexion->prepare($sql);

        $query->execute(['nick' => $nick]);

        if ($query) { 

            $resultado["datos"] = $query->fetch(PDO::FETCH_ASSOC);

        }

        return $resultado;
    }

    
    //FUNCIONES PARA TRABAJAR CON LA TABLA USUARIOS

    /**
     * método que permite insertar un registro en la tabla usuarios de la base de datos bdblog
     *
     * @param string $datos
     * @return $resultado
     */
    public function insertar($datos){

        $resultado = ["correcto" => FALSE, "error" => NULL];
        $fecha = date("Y-m-d H:i:s");
        $usuario_id = $_COOKIE["permisos"];

        try {

            $this->conexion->beginTransaction(); //se inicia la transacción

            //instrucción SQL de insertar
            $sql = "INSERT into usuarios VALUES(NULL, :nick, :nombre, :apellidos, :email, :password, :imagen);";

            $query = $this->conexion->prepare($sql); //se prepara la consulta

            //se ejecuta la consulta
            $query->execute(['nick' => $datos["nick"], 'nombre' => $datos["nombre"],'apellidos' => $datos["apellidos"],
            'email' => $datos["email"],'password' => $datos["password"],'imagen' => $datos["imagen"]]);

            if ($query){ //si se realiza la operación correctamente

                $this->conexion->commit(); //se confirman los cambios realizados

                $resultado["correcto"] = TRUE;

                //inserción de la acción en la tabla logs
                $logSql = "INSERT into logs VALUES(NULL, :usuario, :operacion, :fecha);";
                $logQuery = $this->conexion->prepare($logSql);
                $logQuery->execute(['usuario' => $usuario_id, 'operacion' => 'Registrado un nuevo usuario', 'fecha' => $fecha]);
            }                


        } catch(PDOException $ex){

            $this->conexion->rollback();

            $resultado["error"] = $ex->getMessage();
        }

        return $resultado;

    }

    /**
     * método que muestra todos los registros de la tabla usuarios de la base de datos bdblog
     *
     * @return $resultado
     */
    public function listar(){

        $resultado = ["correcto" => FALSE, "datos" => NULL, "pagina" => NULL, "num_paginas" => NULL, "error" => NULL];

        try {

            //variables para la paginación
            $pagina = isset($_GET["pagina"]) ? (int) $_GET["pagina"] : 1;
            $resultado["pagina"] = $pagina;
            $filaporpag = 3;
            $inicio = ($pagina > 1) ? ($pagina * $filaporpag - $filaporpag) : 0;

            //sentencia sql para listar las filas existentes en la tabla usuarios y el número de filas por pág que mostrará
            $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM usuarios LIMIT $inicio, $filaporpag;";

            //preparación y ejecución de la sentencia sql previamente definida
            $query = $this->conexion->query($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC); 
            $resultFilas = $query->fetchAll();

            if ($query){ //si no ocurren errores en la operación

                $resultado["correcto"] = TRUE;
                $resultado["datos"] = $resultFilas;
            }

            //variable que recoge el número total de elementos de la tabla usuarios
            $totalFilas = $this->conexion->query('SELECT FOUND_ROWS() as total;');
            $totalFilas = $totalFilas->fetch()['total'];

            //variable para el número de páginas según el nº de elementos de la tabla y el número de filas por página
            $numPagina = ceil($totalFilas / $filaporpag);
            $resultado["num_paginas"] = $numPagina;


        } catch(PDOException $ex){

            $resultado["error"] = $ex->getMessage();
        }

        return $resultado;
    }

    /**
     * método que muestra los datos de un registro concreto por el id que este contenga en la tabla usuarios
     *
     * @param int $id
     * @return $resultado
     */
    public function listarUsuario($id){

        $resultado = ["correcto" => FALSE, "datos" => NULL, "error" => NULL];

        if ($id and is_numeric($id)){ //si existe un id y es numérico

            try {

                //sentencia SQL para listar los datos de un usuario mediante el id
                $sql = "SELECT * FROM usuarios WHERE id=:id;";

                //se prepara la consulta
                $query = $this->conexion->prepare($sql);

                //se ejecuta la consulta
                $query->execute(['id' => $id]);
                 
                if ($query) { //si no ocurren errores en la operación

                    $resultado["correcto"] = TRUE;
                    $resultado["datos"] = $query->fetch(PDO::FETCH_ASSOC);

                }

            } catch (PDOException $ex){

              $resultado["error"] = $ex->getMessage();

            }

        }
      
        return $resultado;
    }

    /**
     * método que permite actualizar los datos de un registro de la tabla usuarios
     *
     * @param string $datos
     * @return $resultado
     */
    public function actualizar($datos){

        $resultado = ["correcto" => FALSE, "error" => NULL];
        $fecha = date("Y-m-d H:i:s");

        try{

            $this->conexion->beginTransaction(); //se inicia la transacción

            //instrucción SQL de actualizar un usuario mediante el id
            $sql = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, email = :email, 
            imagen = :imagen WHERE id= :id;";

            //se prepara la consulta
            $query = $this->conexion->prepare($sql);

            //se ejecuta la consulta
            $query->execute(['id' => $datos["id"], 'nombre' => $datos["nombre"], 'apellidos' => $datos["apellidos"],
             'email' => $datos["email"], 'imagen' => $datos["imagen"]]);

            if ($query){ //si no ocurren errores en la operación

                $this->conexion->commit(); //se confirman los cambios realizados
                $resultado["correcto"] = TRUE;

                //inserción de la acción en la tabla logs
                $logSql = "INSERT into logs VALUES(NULL, :usuario, :operacion, :fecha);";
                $logQuery = $this->conexion->prepare($logSql);
                $logQuery->execute(['usuario' => $datos["id"],'operacion' => 'Edición de datos de usuario', 'fecha' => $fecha]);
            } 

        } catch (PDOException $ex){

            $this->conexion->rollback(); //se revierten los cambios realizados
            $resultado["error"] = $ex->getMessage();

        }

        return $resultado;
    }

    /**
     * método que elimina un registro de la tabla usuarios mediante el id 
     *
     * @param int $id
     * @return $resultado
     */
    public function eliminar($id){

        $resultado = ["correcto" => FALSE, "error" => NULL];
        $fecha = date("Y-m-d H:i:s");

        if ($id and is_numeric($id)){ //si existe un id y es numérico

            try{

                //instrucción SQL para eliminar registros de la tabla de la base de datos
                $sql = "DELETE FROM usuarios WHERE id = :id;";

                //se prepara la consulta
                $query = $this->conexion->prepare($sql);

                //se ejecuta la consulta
                $query->execute(['id' => $id]);
        
                if ($query){ //si no ocurren errores en la operación
                    
                   $resultado["correcto"] = TRUE;
                
                   //inserción de la acción en la tabla logs
                   $logSql = "INSERT into logs VALUES(NULL, :usuario, :operacion, :fecha);";
                   $logQuery = $this->conexion->prepare($logSql);
                   $logQuery->execute(['usuario' => $id,'operacion' => 'Eliminación de usuario', 'fecha' => $fecha]);
        
                }
        
            } catch (PDOException $ex){ 
        
                $resultado["error"] = $ex->getMessage();
            }
        
        } else {

            $resultado["correcto"] = FALSE;
        }

        return $resultado;
    }

    /**
     * método que muestra todos los registros de la tabla usuarios para incluirlos en la vista para el PDF
     *
     * @return $resultado
     */
    public function listarPdf(){

        $resultado = ["datos" => NULL, "error" => NULL];
        
        try {  

          $sql = "SELECT * FROM usuarios;";
         
          $query = $this->conexion->query($sql);
           
          if ($query){

            $resultado["correcto"] = TRUE;
            $resultado["datos"] = $query->fetchAll(PDO::FETCH_ASSOC);

          }
             
        } catch (PDOException $ex) {

          $resultado["error"] = $ex->getMessage();
        }
    
        return $resultado;
    }

    /**
     * función que obtiene todos los registros de la tabla usuarios para poder extraerlos en una hoja EXCEL
     *
     * @return void
     */
    public function listadoExcel(){

        try{

            $sql = "SELECT * FROM usuarios;";
         
            $query = $this->conexion->query($sql);

        } catch (PDOException $ex) {

            $ex->getMessage();
           
        }

        return $query;
    }


    //FUNCIONES PARA TRABAJAR CON LA TABLA ENTRADAS


    /**
     * función que permite realizar inserción de entradas en la tabla entradas 
     */
    public function insertarEntrada($datos){

        $resultado = ["correcto" => FALSE, "error" => NULL];
        $fecha = date("Y-m-d H:i:s");

        try {

            $this->conexion->beginTransaction(); //se inicia la transacción

            //instrucción SQL de insertar
            $sql = "INSERT into entradas VALUES(NULL, :usuario_id, :categoria_id, :titulo, :imagen, :descripcion, :fecha);";

            $query = $this->conexion->prepare($sql); //se prepara la consulta

            //se ejecuta la consulta
            $query->execute(['usuario_id' => $datos["usuario_id"], 'categoria_id' => $datos["categoria_id"],
            'titulo' => $datos["titulo"], 'imagen' => $datos["imagen"], 'descripcion' => $datos["descripcion"],
            'fecha' => $datos["fecha"]]);

            if ($query){ //si se realiza la operación correctamente

                $this->conexion->commit(); //se confirman los cambios realizados

                $resultado["correcto"] = TRUE;

                //inserción de la acción en la tabla logs
                $logSql = "INSERT into logs VALUES(NULL, :usuario, :operacion, :fecha);";
                $logQuery = $this->conexion->prepare($logSql);
                $logQuery->execute(['usuario' => $datos["usuario_id"],'operacion' => 'Subida una nueva entrada', 'fecha' => $fecha]);
            }                


        } catch(PDOException $ex){

            $this->conexion->rollback();

            $resultado["error"] = $ex->getMessage();
        }

        return $resultado;

    }

    /**
     * función para mostrar todos los registros de la tabla entradas de la base de datos bdblog
     *
     * @return $resultado
     */
    public function listarEntradas(){

        $resultado = ["correcto" => FALSE, "datos" => NULL, "pagina" => NULL, "num_paginas" => NULL, "error" => NULL];

        try {

            //variables para la paginación
            $pagina = isset($_GET["pagina"]) ? (int) $_GET["pagina"] : 1;
            $resultado["pagina"] = $pagina;
            $filaporpag = 3;
            $inicio = ($pagina > 1) ? ($pagina * $filaporpag - $filaporpag) : 0;

            //sentencia sql para listar las filas existentes en la tabla usuarios y el número de filas por pág que mostrará
            $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM entradas LIMIT $inicio, $filaporpag;";

            //preparación y ejecución de la sentencia sql previamente definida
            $query = $this->conexion->query($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC); 
            $resultFilas = $query->fetchAll();

            if ($query){ //si no ocurren errores en la operación

                $resultado["correcto"] = TRUE;
                $resultado["datos"] = $resultFilas;
            }

            //variable que recoge el número total de elementos de la tabla usuarios
            $totalFilas = $this->conexion->query('SELECT FOUND_ROWS() as total;');
            $totalFilas = $totalFilas->fetch()['total'];

            //variable para el número de páginas según el nº de elementos de la tabla y el número de filas por página
            $numPagina = ceil($totalFilas / $filaporpag);
            $resultado["num_paginas"] = $numPagina;


        } catch(PDOException $ex){

            $resultado["error"] = $ex->getMessage();
        }

        return $resultado;

    }

    /**
     * método que muestra los datos de un registro concreto por el id que este contenga en la tabla entradas
     *
     * @param int $id
     * @return $resultado
     */
    public function listarEntrada($id){

        $resultado = ["correcto" => FALSE, "datos" => NULL, "error" => NULL];

        if ($id and is_numeric($id)){ //si existe un id y es numérico

            try {

                //sentencia SQL para listar los datos de un usuario mediante el id
                $sql = "SELECT * FROM entradas WHERE id=:id;";

                //se prepara la consulta
                $query = $this->conexion->prepare($sql);

                //se ejecuta la consulta
                $query->execute(['id' => $id]);
                 
                if ($query) { //si no ocurren errores en la operación

                    $resultado["correcto"] = TRUE;
                    $resultado["datos"] = $query->fetch(PDO::FETCH_ASSOC);
                }

            } catch (PDOException $ex){

              $resultado["error"] = $ex->getMessage();

            }

        }
      
        return $resultado;
    }

    /**
     * método que permite actualizar los datos de un registro de la tabla entradas
     *
     * @param string $datos
     * @return $resultado
     */
    public function actualizarEntrada($datos){

        $resultado = ["correcto" => FALSE, "error" => NULL];
        $fecha = date("Y-m-d H:i:s");
        $usuario_id = $_COOKIE["permisos"];

        try{

            $this->conexion->beginTransaction(); //se inicia la transacción

            //instrucción SQL de actualizar un usuario mediante el id
            $sql = "UPDATE entradas SET titulo = :titulo, descripcion = :descripcion, 
            imagen = :imagen WHERE id= :id;";

            //se prepara la consulta
            $query = $this->conexion->prepare($sql);

            //se ejecuta la consulta
            $query->execute(['id' => $datos["id"], 'titulo' => $datos["titulo"], 'descripcion' => $datos["descripcion"],
            'imagen' => $datos["imagen"]]);

            if ($query){ //si no ocurren errores en la operación

                $this->conexion->commit(); //se confirman los cambios realizados
                $resultado["correcto"] = TRUE;

                //inserción de la acción en la tabla logs
                $logSql = "INSERT into logs VALUES(NULL, :usuario, :operacion, :fecha);";
                $logQuery = $this->conexion->prepare($logSql);
                $logQuery->execute(['usuario' => $usuario_id,'operacion' => 'Edición de entradas', 'fecha' => $fecha]);
            } 

        } catch (PDOException $ex){

            $this->conexion->rollback(); //si hay excepción se revierten los cambios realizados
            $resultado["error"] = $ex->getMessage();

        }

        return $resultado;
    }

    /**
     * método que elimina un registro de la tabla entradas mediante el id 
     *
     * @param int $id
     * @return $resultado
     */
    public function eliminarEntrada($id){

        $resultado = ["correcto" => FALSE, "error" => NULL];
        $fecha = date("Y-m-d H:i:s");
        $usuario_id = $_COOKIE["permisos"];

        if ($id and is_numeric($id)){ //si existe un id y es numérico

            try{

                //instrucción SQL para eliminar registros de la tabla de la base de datos
                $sql = "DELETE FROM entradas WHERE id = :id;";

                //se prepara la consulta
                $query = $this->conexion->prepare($sql);

                //se ejecuta la consulta
                $query->execute(['id' => $id]);
        
                if ($query){ //si no ocurren errores en la operación
                    
                   $resultado["correcto"] = TRUE;
                   
                   //inserción de la acción en la tabla logs
                   $logSql = "INSERT into logs VALUES(NULL, :usuario, :operacion, :fecha);";
                   $logQuery = $this->conexion->prepare($logSql);
                   $logQuery->execute(['usuario' => $usuario_id,'operacion' => 'Eliminación de entradas', 'fecha' => $fecha]);
        
                }
        
            } catch (PDOException $ex){ 
        
                $resultado["error"] = $ex->getMessage();
            }
        
        } else {

            $resultado["correcto"] = FALSE;
        }

        return $resultado;
    }


    public function buscar(){

        $resultado = ["correcto" => FALSE, "datos" => NULL, "error" => NULL];

        $query = $this->conexion->query("SELECT * FROM usuarios where nombre = ?;");

        return $resultado;
    }


    //FUNCIONES PARA LA TABLA logs

    /**
     * función que obtiene todos los registros de la tabla logs
     *
     * @return void
     */
    public function listarLogs(){

        $resultado = ["correcto" => FALSE, "datos" => NULL, "error" => NULL];

        try { 

          $sql = "SELECT * FROM logs;"; //sentencia SQL
          $query = $this->conexion->query($sql); //se prepara
           
          if ($query){

            $resultado["correcto"] = TRUE;
            $resultado["datos"] = $query->fetchAll(PDO::FETCH_ASSOC);

          }
            
          
        } catch (PDOException $ex) {

          $resultado["error"] = $ex->getMessage();
        }
    
        return $resultado;
    }

    /**
     * función que obtiene los datos de un registro concreto según su id
     *
     * @param int $id
     * @return void
     */
    public function listarLog($id){

        $resultado = ["correcto" => FALSE, "datos" => NULL, "error" => NULL];

        if ($id and is_numeric($id)){ //si existe un id y es numérico

            try {

                //sentencia SQL para listar los datos de un registro mediante el id
                $sql = "SELECT * FROM logs WHERE id=:id;";

                //se prepara la consulta
                $query = $this->conexion->prepare($sql);

                //se ejecuta la consulta
                $query->execute(['id' => $id]);
                 
                if ($query) { //si no ocurren errores en la operación

                    $resultado["correcto"] = TRUE;
                    $resultado["datos"] = $query->fetch(PDO::FETCH_ASSOC);
                }

            } catch (PDOException $ex){

              $resultado["error"] = $ex->getMessage();

            }
        }
      
        return $resultado;
    }

    /**
     * función que elimina un registro de la tabla logs según su id
     *
     * @param int $id
     * @return void
     */
    public function eliminarLog($id){

        $resultado = ["correcto" => FALSE, "error" => NULL];

        if ($id and is_numeric($id)){ //si existe un id y es numérico

            try{

                //instrucción SQL para eliminar registros de la tabla de la base de datos
                $sql = "DELETE FROM logs WHERE id = :id;";

                //se prepara la consulta
                $query = $this->conexion->prepare($sql);

                //se ejecuta la consulta
                $query->execute(['id' => $id]);
        
                if ($query){ //si no ocurren errores en la operación
                    
                   $resultado["correcto"] = TRUE;
        
                }
        
            } catch (PDOException $ex){ 
        
                $resultado["error"] = $ex->getMessage();
            }
        
        } else {

            $resultado["correcto"] = FALSE;
        }

        return $resultado;
    }


}


?>