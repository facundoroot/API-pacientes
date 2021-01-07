<?php

class Conexion {

    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;

    public function __construct(){
        $listadatos = $this->datosConexion();
        foreach($listadatos as $key => $value){

            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];

        }

        $this->conexion = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
        if($this->conexion->connect_errno){
            echo "error de conexion";
            die();
        }
    }

    // traigo la info de config
    private function datosConexion(){

        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion."/"."config");
        // paso de formato json a array asociativo
        return json_decode($jsondata,true);
    }

    // convierto valores raros con tildes, etc de la db
    private function convertirUTF8($array){
        // mira los elementos del array
        array_walk_recursive($array,function(&$item,$key){
            // si el encode no es ut8, lo pasa a utf8
            if(!mb_detect_encoding($item,'utf-8',true)){
                $item = utf8_encode($item);
            }
        });
        return $array;

    }

    public function obtenerDatos($sqlstr){

        $results = $this->conexion->query($sqlstr);
        // ahora los resultados los paso a un array vacio
        $resultArray = array();
        foreach($results as $key){
            // creo una fila en resultArray por cada fila recorrida
            $resultArray[] = $key;
        }
        // los paso a utf-8 
        return $this->convertirUTF8($resultArray);
    }

    // ahora hago un non-query para guardar,editar y eliminar
    public function nonQuery($sqlstr){
        $results = $this->conexion->query($sqlstr);
        // muestro cantidad de filas afectadas
        return $this->conexion->affected_rows;
    }

    // hago una funcion especifica para que cuando inserte me de el id de las filas guardadas
    public function nonQueryId($sqlstr){
        $results = $this->conexion->query($sqlstr);
        $filas = $this->conexion->affected_rows;
        if($filas >= 1){
            return $this->conexion->insert_id;
        }else{
            return 0;
        }
    }

    protected function encriptar($string){
        return md5($string);
    }
}