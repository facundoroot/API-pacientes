<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API - Pacientes</title>
    <link rel="stylesheet" href="assets/css/estilo.css" type="text/css">
</head>
<body>
<div  class="container">
    <h1>Api Pacientes</h1>
    <p>Para generar el token hay que hacer el login, el token se utiliza luego para las acciones del CRUD.
    <br> Esta API Restful recibe y devuelve datos de pacientes en formato json.
    <br> En clases/conexion/config esta la configuracion de la db, en assets/database/database.sql se encuentran las tablas para la db de la API</p>
    <p>En el archivo clases/token.class junto con actualizar token estan creados para usar CRON y inactivar tokens dependiendo de la preferencia del usuario
    <br>Las imagenes deben ser en formato codigo base 64</p>
    <div class="divbody">
        <h3>Auth - login</h3>
        <code>
           POST  /auth
           <br>
           {
               <br>
               "usuario" :"",  -> REQUERIDO
               <br>
               "password": "" -> REQUERIDO
               <br>
            }
        
        </code>
    </div>      
    <div class="divbody">   
        <h3>Pacientes</h3>
        <code>
           GET  /pacientes?page=$numeroPagina
           <br>
           GET  /pacientes?id=$idPaciente
        </code>
        <code>
           POST  /pacientes
           <br> 
           {
            <br> 
               "nombre" : "",               -> REQUERIDO
               <br> 
               "dni" : "",                  -> REQUERIDO
               <br> 
               "correo":"",                 -> REQUERIDO
               <br> 
               "codigoPostal" :"",             
               <br>  
               "genero" : "",        
               <br>        
               "telefono" : "",       
               <br>       
               "fechaNacimiento" : "",      
               <br>
                "imagen" : "",      
               <br>          
               "token" : ""                 -> REQUERIDO        
               <br>       
           }
        </code>
        <code>
           PUT  /pacientes
           <br> 
           {
            <br> 
               "nombre" : "",               
               <br> 
               "dni" : "",                  
               <br> 
               "correo":"",                 
               <br> 
               "codigoPostal" :"",             
               <br>  
               "genero" : "",        
               <br>        
               "telefono" : "",       
               <br>       
               "fechaNacimiento" : "",      
               <br>         
               "token" : "" ,                -> REQUERIDO        
               <br>       
               "pacienteid" : ""   -> REQUERIDO
               <br>
           }
        </code>
        <code>
           DELETE  /pacientes
           <br> 
           {   
               <br>    
               "token" : "",                -> REQUERIDO        
               <br>       
               "pacienteid" : ""   -> REQUERIDO
               <br>
           }
        </code>
    </div>
</div>
    
</body>
</html>
