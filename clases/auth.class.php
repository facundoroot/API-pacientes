<?php 

// conecto a la db
require_once 'conexion/conexion.php';
// traigo respuestas
require_once 'respuestas.class.php';

// heredo los metodos publicos de conexion
class Auth extends Conexion{

    public function login($json){

        $_respuesta = new Respuesta;
        // me llegan los json y los paso a array asociativo
        $datos = json_decode($json,true);

        if(!isset($datos['usuario']) || !isset($datos['password'])){
            // error con los campos
            return $_respuesta->error_400();
        }else{
            // todo esta bien

            $usuario = $datos['usuario'];
            $password = $datos['password'];
            // encripto la contrasenia
            $password = parent::encriptar($password);

            $datos = $this->obtenerDatosUsuario($usuario);
            if($datos){
                // si existe el usuario
                // verifico si las contrasenias coinciden
                if($password == $datos[0]['Password']){
                    if($datos[0]['Estado'] == 'Activo'){
                        // creo token
                        $verificar = $this->insertarToken($datos[0]['UsuarioId']);
                        if($verificar){

                            // se guardo correctamente
                            $result = $_respuesta->response;
                            $result["result"] = array(
                                "token" => $verificar
                            );
                            return $result;

                        }else{
                            // fallo el guardado del token
                            return $_respuesta->error_500("Fallo interno del servidor");
                        }

                    }else{
                        // el usuario esta inactivo
                        return $_respuesta->error_200("el usuario se encuentra inactivo");
                    }

                }else{
                    // la contrasenia es incorrecta
                    return $_respuesta->error_200("el password es invalido");
                }
            }else{
                // si no existe el usuario
                return $_respuesta->error_200("el usuario ".$usuario." no existe");
            }
        }
    }

    private function obtenerDatosUsuario($correo){

        $query = "SELECT UsuarioId,Password,Estado FROM usuarios WHERE Usuario = '$correo'";
        // obtengo los datos y los paso a utf-8 
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["UsuarioId"])){
            return $datos;
        }else{
            return 0;
        }

    }

    private function insertarToken($usuarioId){
        $val = true;
        // bin2hex nos devuelve un strin 1-9a-f
        // openssl genera 16bites pseudoaleatorios, solo acepta variables por eso pongo en una variable true
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha) VALUES('$usuarioId','$token','$estado','$date')";
        $verifica = parent::nonQuery($query);
        if($verifica){
            return $token;
        }else{
            return 0;
        }
    }
}