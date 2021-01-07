    #Api Pacientes


        ##Auth - login

           ###POST  /auth

           {
               "usuario" :"",  -> REQUERIDO

               "password": "" -> REQUERIDO
            }



        ##Pacientes

           ###GET  /pacientes?page=$numeroPagina

           ###GET  /pacientes?id=$idPaciente


           ###POST  /pacientes

           {
               "nombre" : "",  -> REQUERIDO

               "dni" : "",     -> REQUERIDO

               "correo":"",    -> REQUERIDO

               "codigoPostal" :"",

               "genero" : "",

               "telefono" : "",

               "fechaNacimiento" : "",

               "token" : ""    -> REQUERIDO
           }


           ###PUT  /pacientes

           {
               "nombre" : "",

               "dni" : "",

               "correo":"",

               "codigoPostal" :"",

               "genero" : "",

               "telefono" : "",

               "fechaNacimiento" : "",

               "token" : "" ,      -> REQUERIDO

               "pacienteId" : ""   -> REQUERIDO
           }


           ###DELETE  /pacientes

           {
               "token" : "",       -> REQUERIDO

               "pacienteId" : ""   -> REQUERIDO
           }
