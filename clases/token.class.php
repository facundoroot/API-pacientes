<?php 

require_once 'conexion/conexion.php';

class Token extends Conexion{

    public function actualizarToken($fecha){

        // voy a cambiar el estado de los token a inactivo que tengan una fecha menor al ultimo token que cree y al mismo tiempo en estado activo
        $query = "UPDATE usuarios_token SET Estado = 'Inactivo' WHERE Fecha < '$fecha' AND Estado = 'Activo' ";
        $verificar = parent::nonQuery($query);

        if($verificar > 0){
            return 1;
        }else{
            return 0;
        }
    }
}