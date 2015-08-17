# Siervo

PHP nano framework.

## Algunas Características:

* Configuración de rutas a la expressjs.
* Entorno de request y response para las callbacks configuradas.
* Acceso a las propiedades de la request (uri args, method, request headers) mediante el objeto Request.
* Acceso a las propiedades de la response (response headers, status code) mediante el objeto Response.
* Configuración de distintos entornos de desarrollo (production, development, etc.) y seteo dinámico de los mismos.
* Configuración de comportamiento para rutas inexistentes (Not Found).
* Acceso a las superglobals $_GET, $_POST y $_FILES mediante propiedades de un objeto Request ($req->get[] & $req->post[] y $req->files[]).
* Acceso al flujo de entrada de php (php://input) mediante la propiedad $input[] de un objeto Request ($req->input[]).