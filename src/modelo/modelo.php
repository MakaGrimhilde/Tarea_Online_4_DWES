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
     * método que permite insertar un registro en la tabla usuarios de la base de datos bdblog
     *
     * @param string $datos
     * @return $resultado
     */
    public function insertar($datos){

        $resultado = ["correcto" => FALSE, "error" => NULL];

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

        $resultado = ["correcto" => FALSE, "datos" => NULL, "error" => NULL];

        try {

            //instrucción SQL
            $sql = "SELECT * FROM usuarios;";

            //se realiza la consulta directamente al no tener parámetros
            $query = $this->conexion->query($sql);

            if ($query){ //si no ocurren errores en la operación

                $resultado["correcto"] = TRUE;
                $resultado["datos"] = $query->fetchAll(PDO::FETCH_ASSOC);
            }

        } catch(PDOException $ex){

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
     * método que permite actualizar los datos de un registro de la tabla usuarios
     *
     * @param string $datos
     * @return $resultado
     */
    public function actualizar($datos){

        $resultado = ["correcto" => FALSE, "error" => NULL];

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
            } 

        } catch (PDOException $ex){

            $this->conexion->rollback(); //se revierten los cambios realizados
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

        if ($id && is_numeric($id)){ //si existe un id y es numérico

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

            } catch (PDOException $ex) {

              $resultado["error"] = $ex->getMessage();

            }

        }
      
        return $resultado;

    }


}


?>