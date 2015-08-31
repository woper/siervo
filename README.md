# Siervo

PHP nano framework.

## Algunas Características:

* Configuración de rutas a la expressjs.
* Entorno de request y response y next para las callbacks configuradas.
* Acceso a las propiedades de la request (uri args, method, request headers, body) mediante el objeto Request.
* Acceso a las propiedades de la response (response headers, status code, body) mediante el objeto Response.
* Configuración de distintos entornos de desarrollo (production, development, etc.) y seteo dinámico de los mismos.
* Configuración de comportamiento para rutas inexistentes (Not Found).
* Acceso a las superglobals $_GET, $_POST y $_FILES mediante métodos de un objeto Request ($req->get('asdf') & $req->post('asdf') y $req->files('asdf')).
* Acceso al flujo de entrada de php (php://input) mediante la propiedad body de un objeto Request ($req->body, de esta manera puede ser tratado según el usuario lo necesite).
* Configuración middleware a nivel global con el método de Siervo uso(callback) y a nivel de ruta, pasando un array de callbacks ([calback1, callback2]).