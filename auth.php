<?php 

require_once 'clases/auth.class.php';
require_once 'clases/respuestas.class.php';

$_auth = new Auth();
$_respuesta = new Respuesta();

// hago la autentificacion, recibo los datos del registro en json
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // recibir datos
    $postBody = file_get_contents("php://input");

    // envio datos al controlador, si sale todo bien trae un token,sino devuelve un error
    $datosArray = $_auth->login($postBody);

    // devuelvo una respuesta http
    header("Content-type:application/json");
    // si tiene un error tambien lo aclaro con http response code
    if(isset($datosArray['result']['error_id'])){
        $responseCode = $datosArray['result']['error_id'];
        http_response_code($responseCode);
    }else{
        // si esta bien envia un http con codigo 200
        http_response_code(200);
    }

    // devuelvo un json que contiene el token o el error
    echo json_encode($datosArray);

}else{
    header("Content-type:application/json");
    $datosArray = $_respuesta->error_405();
    echo json_encode($datosArray);
}