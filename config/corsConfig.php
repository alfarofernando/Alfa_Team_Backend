<?php
// Permitir el acceso desde un origen específico
header("Access-Control-Allow-Origin: http://localhost:5173");

// Permitir métodos específicos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Permitir encabezados personalizados
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Permitir que las credenciales (cookies, cabeceras de autenticación) se envíen con la solicitud
header("Access-Control-Allow-Credentials: true");

// Manejo de pre-flight requests (opcional, pero útil para solicitudes complejas)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Responder con un código 200 a las solicitudes de tipo OPTIONS
    http_response_code(200);
    exit;
}
