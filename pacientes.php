<?php 
// aca es donde va a estar el CRUD de la API pacientes
require_once 'clases/respuestas.class.php';
require_once 'clases/pacientes.class.php';

$_respuesta = new Respuesta();
$_paciente = new Paciente();


if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // metodo GET (READ)

    if(isset($_GET['page'])){
        // si recibe page muestra la pagina con varios pacientes
        $pagina = $_GET['page'];
        $listaPacientes = $_paciente->listaPacientes($pagina);

        header("Content-type:application/json");
        print_r(json_encode($listaPacientes)); 
        http_response_code(200);

    }elseif(isset($_GET['id'])){
        // si recibe el id muestra la info de ese paciente
        $pacienteId = $_GET['id'];
        $datosPaciente = $_paciente->obtenerPaciente($pacienteId);

        header("Content-type:application/json");
        echo json_encode($datosPaciente);
        http_response_code(200);
    }
    

    
}elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
    // metodo POST (CREATE)

    // recibo datos
    $postBody = file_get_contents("php://input");

    // envio datos al controlador
    $datosArray = $_paciente->post($postBody);

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


    
}elseif($_SERVER['REQUEST_METHOD'] == 'PUT'){
    // metodo PUT (UPDATE)

    // recibo datos
    $postBody = file_get_contents("php://input");

    // envio datos al controlador
    $datosArray = $_paciente->put($postBody);

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


}elseif($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    // metodo DELETE (DELETE)

    // voy a recibir y los datos por header, y si no los envian por ahi los recibo por body como siempre
    $headers = getallheaders();
    if(isset($headers['token']) && isset($headers['pacienteid'])){
        // recibo los datos por headers
        $send = [
            "token" => $headers['token'],
            "pacienteid" => $headers['pacienteid']

        ];
        // ahora los convierto a json
        $postBody = json_encode($send);

    }else{
        // // recibo datos en el body
         $postBody = file_get_contents("php://input");
    }

    // envio datos al controlador
    $datosArray = $_paciente->delete($postBody);

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