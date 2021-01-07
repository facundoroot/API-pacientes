<?php 

// devuelvo array de error con 2 campos, el estatus "ok" o "error"  y ademas adentro del ok podria haber error
// asi que ese va a ser otro campo por ejemplo daatos invalidos
class Respuesta{

    // creo un array
    public $response = [

        'status' => 'ok',
        'result' => array()

    ];

    // cliente envia un request por un metodo no permitido
    public function error_405(){
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            "error_id" => '405',
            "error_message" => 'metodo no permitido'

        );

        return $this->response;
    }

    // esta funcion puede tener un parametro opcional, si no lo mandan anda igual con el parametro prearmado
    public function error_200($valor = 'datos incorrectos'){
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            "error_id" => '200',
            "error_message" => $valor

        );

        return $this->response;
    }

    public function error_400(){
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            "error_id" => '400',
            "error_message" => 'datos incompletos o con formato incorrecto'

        );

        return $this->response;
    }

    public function error_500($valor = 'Error interno del servidor'){
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            "error_id" => '500',
            "error_message" => $valor

        );

        return $this->response;
    }

    public function error_401($valor = 'No autorizado'){
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            "error_id" => '401',
            "error_message" => $valor

        );

        return $this->response;
    }


}