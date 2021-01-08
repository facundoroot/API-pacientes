# Api restful Pacientes

Para comenzar a utilizar la api se crea un usuario en la base de datos o se usa alguno precreado (password = 123456)
Luego de crear el usuario se hace el login donde se crea el token, el cual se utiliza para poder hacer peticiones de todos los metodos del CRUD
Esta API Restful recibe y devuelve datos de pacientes en formato json, para el DELETE tambien se puede mandar directamente a travez de headers
En clases/conexion/config esta la configuracion de la db, en assets/database/database.sql se encuentran las tablas para la db de la API
La imagen del paciente debe ser en formato codigo base 64.
En el archivo clases/token.class junto con actualizar token estan creados para usar CRON y inactivar tokens dependiendo de la preferencia del usuario

## Auth - login

### POST /auth

{
"usuario" :"", -> REQUERIDO

"password": "" -> REQUERIDO
}

## Pacientes

### GET /pacientes?page=$numeroPagina

### GET /pacientes?id=$idPaciente

### POST /pacientes

{
"nombre" : "", -> REQUERIDO

"dni" : "", -> REQUERIDO

"correo":"", -> REQUERIDO

"codigoPostal" :"",

"genero" : "",

"telefono" : "",

"fechaNacimiento" : "",

"imagen" : "",

"token" : "" -> REQUERIDO
}

### PUT /pacientes

recomendacion:rellenar todos los datos para modificar una fila ya que los datos no rellenos se vuelven null.

{
"nombre" : "",

"dni" : "",

"correo":"",

"codigoPostal" :"",

"genero" : "",

"telefono" : "",

"fechaNacimiento" : "",

"token" : "" , -> REQUERIDO

"pacienteid" : "" -> REQUERIDO
}

### DELETE /pacientes

{
"token" : "", -> REQUERIDO

"pacienteid" : "" -> REQUERIDO
}
