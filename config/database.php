<?php

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        // Asegúrate de cambiar estos valores por los correctos
        $this->connection = new PDO('mysql:host=localhost;dbname=alfateam', 'root', '');
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configura PDO para lanzar excepciones
    }

    public static function getConnection()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->connection; // Devuelve la conexión
    }
}
