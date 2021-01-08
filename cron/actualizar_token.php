<?php 
require_once '../clases/token.class.php';

$_token = new Token;
$fecha = date("Y-m-d H:i");

echo $_token->actualizarToken($fecha);