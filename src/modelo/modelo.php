<?php

/**
 * Clase Modelo
 */
class modelo {

    private $conexion;
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $db = "bdblog";

    /**
     * constructor de la clase modelo
     */
    public function __construct(){

        $this->conectar();

    }

    /**
     * método que conecta a la base de datos bdblog
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

    public function insertar($datos){

        $resultado = ["correcto" => FALSE, "error" => NULL];

        try {

            $this->conexion->beginTransaction();

            $sql = "INSERT into usuarios VALUES(NULL, :nick, :nombre, :apellidos, :email, :password, :imagen);";

            $query = $this->conexion->prepare($sql);

            $query->execute(['nick' => $datos["nick"], 'nombre' => $datos["nombre"],'apellidos' => $datos["apellidos"],
            'email' => $datos["email"],'password' => $datos["password"],'imagen' => $datos["imagen"]]);

            if ($query){

                $this->conexion->commit(); 

                $resultado["correcto"] = TRUE;
            }                


        } catch(PDOException $ex){

            $this->conexion->rollback();

            $resultado["error"] = $ex->getMessage();
        }

        return $resultado;
    }

    public function listar(){

        $resultado = ["correcto" => FALSE, "datos" => NULL, "error" => NULL];

        try {

            $sql = "SELECT * FROM usuarios;";

            $query = $this->conexion->query($sql);

            if ($query){

                $resultado["correcto"] = TRUE;
                $resultado["datos"] = $query->fetchAll(PDO::FETCH_ASSOC);
            }

        } catch(PDOException $ex){

            $resultado["error"] = $ex->getMessage();
        }

        return $resultado;
    }


}


?>