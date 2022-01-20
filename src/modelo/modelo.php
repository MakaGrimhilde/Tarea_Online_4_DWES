<?php

class Modelo {

    private $conexion;
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $db = "bdblog";

    public function __construct(){

        $this->conectar();

    }

    public function conectar(){

        
        $resultado = ["correcto" => FALSE, "datos" => NULL, "error" => NULL];

        try{

            $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $resultado["correcto"] = TRUE;

        } catch(PDOException $ex) {

            $resultado["error"] = $ex->getMessage();

        }

        return $resultado;
            
    }


}


?>