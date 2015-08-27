# Siervo

PHP nano framework.

## Algunas Características:

* Configuración de rutas a la expressjs.
* Entorno de request y response y next para las callbacks configuradas.
* Acceso a las propiedades de la request (uri args, method, request headers) mediante el objeto Request.
* Acceso a las propiedades de la response (response headers, status code) mediante el objeto Response.
* Configuración de distintos entornos de desarrollo (production, development, etc.) y seteo dinámico de los mismos.
* Configuración de comportamiento para rutas inexistentes (Not Found).
* Acceso a las superglobals $_GET, $_POST y $_FILES mediante métodos de un objeto Request ($req->get('asdf') & $req->post('asdf') y $req->files('asdf')).
* Acceso al flujo de entrada de php (php://input) mediante el método $input('asdf') de un objeto Request ($req->input('asdf'), convierte el flujo de entrada en un array, el método devuelve el valor de la llave que recibe como argumento).
* Configuración middleware a nivel global con el método de Siervo uso(callback) y a nivel de ruta, pasando un array de callbacks ([calback1, callback2]).