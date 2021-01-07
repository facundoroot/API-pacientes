<?php 
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class Paciente extends Conexion{

    private $table = 'pacientes';
    private $pacienteid = '';
    private $dni = '';
    private $nombre = '';
    private $direccion = '';
    private $codigoPostal = '';
    private $genero = '';
    private $telefono = '';
    private $fechaNacimiento = '0000-00-00';
    private $correo = '';
    private $token = '';


    public function listaPacientes($pagina = 1){
        // muestro por paginas asi no muestro muchisimos pacientes de una
        $inicio = 0;
        $cantidad = 100;

        // la idea es que si la pagina 1 muestra de 1 a 100 con inicio y cantidad o sea del paciente 1 al 100, luego si uso pagina 2
        // inicio = 101, cantidad = 200, de esta manera "muestro la pagina 2" o sea del paciente 101 al 200, si pongo pagina 3, de 201 al 300.etc
        if($pagina > 1){
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
        }

        $query = "SELECT PacienteId,Nombre,DNI,Telefono,Correo FROM " .$this->table. " limit ".$inicio.", ".$cantidad;
        $datos = parent::obtenerDatos($query);
        return $datos;

    }

    public function obtenerPaciente($id){
        $query = "SELECT * FROM ".$this->table." WHERE PacienteId = '$id'";
        $datos = parent::obtenerDatos($query);
        return $datos;
    }

    public function post($json){

        $_respuesta = new Respuesta;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuesta->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();

            if($arrayToken){

                if(!isset($datos['nombre']) || !isset($datos['dni']) || !isset($datos['correo'])){
                    return $_respuesta->error_400();
                }else{

                    if(isset($datos['nombre'])){$this->nombre = $datos['nombre'];}
                    if(isset($datos['dni'])){$this->dni = $datos['dni'];}
                    if(isset($datos['correo'])){$this->correo = $datos['correo'];}
                    if(isset($datos['telefono'])){$this->telefono = $datos['telefono'];}
                    if(isset($datos['direccion'])){$this->direccion = $datos['direccion'];}
                    if(isset($datos['codigoPostal'])){$this->codigoPostal = $datos['codigoPostal'];}
                    if(isset($datos['genero'])){$this->genero = $datos['genero'];}
                    if(isset($datos['fechaNacimiento'])){$this->fechaNacimiento = $datos['fechaNacimiento'];}

                    $insertar = $this->insertarPaciente();

                    if($insertar){
                        $respuesta = $_respuesta->response;
                        $respuesta['result'] = array(
                            "pacienteId" => $insertar
                        );
                        return $respuesta;

                    }else{
                        return $_respuesta->error_500();
                    }
                    
                }

            }else{
                return $_respuesta->error_401('El token que envio es invalido o caducado');
            }
        }


    }

    private function insertarPaciente(){
        $query = "INSERT INTO " . $this->table . " (DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo)
        values
        ('" . $this->dni . "','" . $this->nombre . "','" . $this->direccion ."','" . $this->codigoPostal . "','"  . $this->telefono . "','" . $this->genero . "','" . $this->fechaNacimiento . "','" . $this->correo . "')";

        $insertar = parent::nonQueryId($query);

        if($insertar){
            return $insertar;
        }else{
            return 0;
        }
    }

    public function put($json){

        $_respuesta = new Respuesta;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuesta->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();

            if($arrayToken){

                if(!isset($datos['pacienteid'])){
                return $_respuesta->error_400();
                }else{

                    $this->pacienteid = $datos['pacienteid'];

                    if(isset($datos['nombre'])){$this->nombre = $datos['nombre'];}
                    if(isset($datos['dni'])){$this->dni = $datos['dni'];}
                    if(isset($datos['correo'])){$this->correo = $datos['correo'];}
                    if(isset($datos['telefono'])){$this->telefono = $datos['telefono'];}
                    if(isset($datos['direccion'])){$this->direccion = $datos['direccion'];}
                    if(isset($datos['codigoPostal'])){$this->codigoPostal = $datos['codigoPostal'];}
                    if(isset($datos['genero'])){$this->genero = $datos['genero'];}
                    if(isset($datos['fechaNacimiento'])){$this->fechaNacimiento = $datos['fechaNacimiento'];}

                    $insertar = $this->actulizarPaciente();

                    if($insertar){
                        $respuesta = $_respuesta->response;
                        $respuesta['result'] = array(
                            "pacienteId" => $this->pacienteid
                        );
                        return $respuesta;

                    }else{
                        return $_respuesta->error_500();
                    }
            
        }

            }else{
                return $_respuesta->error_401('El token que envio es invalido o caducado');
            }
        }


    }

    private function actulizarPaciente(){
        $query = "UPDATE " . $this->table . " SET Nombre ='" . $this->nombre . "',Direccion = '" . $this->direccion . "', DNI = '" . $this->dni . "', CodigoPostal = '" .
        $this->codigoPostal . "', Telefono = '" . $this->telefono . "', Genero = '" . $this->genero . "', FechaNacimiento = '" . $this->fechaNacimiento . "', Correo = '" . $this->correo .
         "' WHERE PacienteId = '" . $this->pacienteid . "'"; 

        $actualizar = parent::nonQuery($query);
        if($actualizar >= 1){
            return $actualizar;
        }else{
            return $actualizar;
        }
    }


    public function delete($json){

        $_respuesta = new Respuesta;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuesta->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();

            if($arrayToken){

                if(!isset($datos['pacienteid'])){
                    return $_respuesta->error_400();
                }else{

                    $this->pacienteid = $datos['pacienteid'];

                    $eliminar = $this->eliminarPaciente();

                    if($eliminar){
                        $respuesta = $_respuesta->response;
                        $respuesta['result'] = array(
                            "pacienteId" => $this->pacienteid
                        );
                        return $respuesta;

                    }else{
                        return $_respuesta->error_500();
                    }
                    
                }

            }else{
                return $_respuesta->error_401('El token que envio es invalido o caducado');
            }
        }



    }

    private function eliminarPaciente(){

        $query = "DELETE FROM ".$this->table. " WHERE PacienteId = '{$this->pacienteid}' ";
        $eliminar = parent::nonQuery($query);

        if($eliminar >= 1){
            return $eliminar;
        }else{
            return $eliminar;
        }
    }

    private function buscarToken(){

        $query = "SELECT TokenId,UsuarioId,Estado FROM usuarios_token WHERE Token = '{$this->token}' AND Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);

        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    private function actualizarToken($tokenid){

        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenid'";
        $resp = parent::nonQuery($query);

        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

}