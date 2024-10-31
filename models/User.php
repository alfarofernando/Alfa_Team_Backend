<?php

class User
// Se define la clase User, que encapsula la lógica relacionada con los usuarios.
// Esta clase interactuará con la base de datos para gestionar la autenticación de usuarios.
{
    private $db;
    // Se declara una propiedad privada $db para almacenar la conexión a la base de datos.

    public function __construct($db)
    // Este es el constructor de la clase User, que se llama al crear una nueva instancia de la clase.
    {
        $this->db = $db;
        // Se inicializa la propiedad $db con el valor pasado como argumento, 
        // permitiendo que otros métodos de la clase usen esta conexión a la base de datos.
    }

    public function validateUser($username, $password)
    // Este método se utiliza para validar las credenciales de un usuario. 
    // Toma el nombre de usuario y la contraseña como parámetros.
    {
        // Consulta SQL para obtener el usuario por nombre
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        // Se define una consulta SQL que selecciona todos los campos de la tabla `users`
        // donde el nombre de usuario coincide con el proporcionado. 
        // Se utiliza un parámetro nombrado (:username) para prevenir inyecciones SQL.

        $stmt = $this->db->prepare($query);
        // Se prepara la consulta utilizando la conexión a la base de datos almacenada en $db.

        $stmt->bindParam(':username', $username);
        // Se vincula el parámetro :username con el valor de la variable $username 
        // proporcionada a la función, lo que permite que la consulta se ejecute de manera segura.

        $stmt->execute();
        // Se ejecuta la consulta preparada.

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Se recupera el resultado de la consulta, si existe, como un array asociativo.

        if ($user) {
            // Si se encuentra un usuario con ese nombre de usuario, se procede a validar la contraseña.

            // Comparar la contraseña directamente (sin hashear)
            if ($password === $user['password']) {
                // Se compara la contraseña proporcionada con la almacenada en la base de datos.
                // Nota: Esto es inseguro, ya que se recomienda usar contraseñas hasheadas.

                // Si las credenciales son correctas, devolver la información del usuario
                return [
                    'username' => $user['username'],
                    // Se devuelve el nombre de usuario del usuario autenticado.

                    'isAdmin' => (bool)$user['isAdmin'], // Asegúrate de que sea un booleano
                    // Se devuelve un valor booleano que indica si el usuario es administrador.
                ];
            }
        }

        return false; // Retornar false si el usuario no existe o la contraseña es incorrecta
        // Si no se encuentra un usuario o las credenciales son incorrectas,
        // se devuelve false, indicando que la autenticación ha fallado.
    }
}
