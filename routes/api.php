<?php

// Cargar la configuración de la base de datos
require_once '../config/database.php';
// Esta línea incluye el archivo de configuración de la base de datos, 
// permitiendo que el script acceda a la clase o funciones necesarias 
// para conectarse a la base de datos.


// Habilitar CORS
require_once '../config/corsConfig.php';
// Esta línea incluye la configuración de CORS (Cross-Origin Resource Sharing),
// lo que permite que el API sea accesible desde diferentes dominios,
// fundamental para la comunicación entre aplicaciones web y APIs.


// Manejo de las solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}
// Este bloque verifica si la solicitud es de tipo OPTIONS, que se utiliza
// en las solicitudes CORS para verificar permisos antes de enviar la solicitud real.
// Si es así, el script se detiene aquí sin realizar ninguna otra acción.


// Cargar los modelos y controladores
require_once '../models/User.php';
require_once '../controllers/UserController.php';
// Estas líneas incluyen los modelos y controladores necesarios para manejar
// la lógica de negocio y la interacción con la base de datos, en este caso, 
// para los usuarios.


// Carga el manejo de rutas de usuarios
require_once 'usersHandle.php';
// Esta línea incluye el archivo que contiene las funciones o métodos para manejar
// las rutas relacionadas con los usuarios, centralizando la lógica de enrutamiento.


// Crear una instancia de la conexión a la base de datos
$db = Database::getConnection();
// Aquí se crea una instancia de la conexión a la base de datos utilizando 
// la clase Database. La variable $db contendrá la conexión para ser usada 
// en las consultas a la base de datos.


header('Content-Type: application/json');
// Esta línea establece el encabezado de respuesta para que el cliente 
// sepa que la respuesta será en formato JSON, lo que es común en APIs.


// Manejo de la ruta raíz
if ($_SERVER['REQUEST_URI'] === '/') {
    echo json_encode(['message' => 'API is running']);
    exit;
}
// Este bloque verifica si la URI solicitada es la raíz ('/'). 
// Si es así, devuelve un mensaje JSON simple indicando que la API está funcionando 
// y luego termina la ejecución del script.


UsersHandle($db); // Llamar a la función que maneja las rutas de usuarios
// Finalmente, esta línea llama a la función UsersHandle, pasando la conexión
// a la base de datos. Esta función se encargará de procesar las solicitudes
// relacionadas con las rutas de usuarios y devolver las respuestas apropiadas.
