<?php

// Habilitar CORS
// Se establecen las cabeceras (headers) HTTP necesarias para permitir CORS (Cross-Origin Resource Sharing).
header("Access-Control-Allow-Origin: http://localhost:5173");
// Permite que las solicitudes de origen cruzado se realicen desde http://localhost:5173.
// Esto significa que las aplicaciones que se ejecutan en este origen podrán acceder a los recursos de la API.

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Especifica qué métodos HTTP están permitidos para las solicitudes de origen cruzado.
// En este caso, se permiten las solicitudes GET, POST y OPTIONS.

header("Access-Control-Allow-Headers: Content-Type");
// Permite que se envíen ciertos encabezados HTTP en las solicitudes de origen cruzado.
// Aquí se permite el encabezado 'Content-Type', que se usa comúnmente para indicar el tipo de contenido de la solicitud.
