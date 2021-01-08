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
    private $imagen = '';


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

                    if(isset($datos['imagen'])){
                        $resp = $this->procesarImagen($datos['imagen']);
                        $this->imagen = $resp;
                    }

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

    private function procesarImagen($img){
        $direccion = dirname(__DIR__) . "\public\imagenes\\";
        // transformo en un array a la variable imagen, dividiendo en antes y despues de ;base64; a los 2 partes del array
        // la parte [0] del array nos da info de el formato de imagen y la [1] el resto del codigo
        $partes = explode(";base64,",$img);
        // ahora divido la imagen que nos llega pero esta vez en la parte de / y agarramos la segunda parte del array o sea [1] y pedimos que nos devuelva el mime type
        // en lugar de agarrar todo el choclo dentro de [1] solo agarrar el .png o .jpg
        $extension = explode('/',mime_content_type($img))[1];
        // ahora hago un decode a la parte de la info de la imagen o sea el $PARTES[1]
        // en vez de $partes[1] uso sizeof que me devuelve el ultimo elemento del array o sea [1], pero con $partes 1 devuelve un warning
        $imagen_base64 = base64_decode($partes[1]);

        // creo el path del archivo a crear
        $file = $direccion . uniqid() . "." . $extension;

        // guardo el archivo
        file_put_contents($file,$imagen_base64);

        // ahora hago una nueva direccion que voy a usar para guardar la direccion del archivo en la db donde reemplazo \ por / asi queda bien la direccion en la db
        // si no hago esto no aparece ningun tipo de barra en el path cuando la guardo en la tabla pacientes
        // solo es una barra en str pero uso 2 por que sino muestra error
        $nuevaDireccion = str_replace('\\','/',$file);

        return $nuevaDireccion;
    }

    private function insertarPaciente(){
        $query = "INSERT INTO " . $this->table . " (DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo,Imagen)
        values
        ('" . $this->dni . "','" . $this->nombre . "','" . $this->direccion ."','" . $this->codigoPostal . "','"  . $this->telefono . "','" . $this->genero . "','" . $this->fechaNacimiento . "','" . $this->correo . "','" . $this->imagen . "')";

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