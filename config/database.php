<?php

class Database
// Se define la clase Database, que implementa el patrón Singleton para gestionar la conexión a la base de datos.
{
    private static $instance = null;
    // Se declara una propiedad estática $instance que almacenará la única instancia de la clase Database.

    private $connection;
    // Se declara una propiedad privada $connection que almacenará la conexión a la base de datos.

    private function __construct()
    // Se define el constructor privado de la clase, lo que impide la creación de instancias externas.
    {
        // Asegúrate de cambiar estos valores por los correctos
        $this->connection = new PDO('mysql:host=localhost;dbname=alfateam', 'root', '');
        // Se crea una nueva conexión a la base de datos utilizando PDO.
        // Aquí se especifica el host, el nombre de la base de datos, el usuario y la contraseña.

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Se configura PDO para que lance excepciones en caso de errores.
        // Esto permite manejar errores de forma más sencilla y efectiva.
    }

    public static function getConnection()
    // Se define un método estático getConnection para obtener la instancia de la conexión a la base de datos.
    {
        if (self::$instance == null) {
            // Si no hay una instancia creada, se crea una nueva.
            self::$instance = new Database();
        }
        return self::$instance->connection; // Devuelve la conexión a la base de datos.
    }
}
