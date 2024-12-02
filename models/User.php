<?php

class User
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function validateUser($email, $password)
  {
    $query = "SELECT id, email, password, isAdmin FROM users WHERE email = :email LIMIT 1";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) {
      return [
        'id' => $user['id'],
        'email' => $user['email'],
        'isAdmin' => (bool)$user['isAdmin']
      ];
    }

    return false;
  }

  public function createUser($email, $password, $username, $isAdmin, $image)
  {
    $query = "INSERT INTO users (email, password, username, isAdmin, image)
                  VALUES (:email, :password, :username, :isAdmin, :image)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':isAdmin', $isAdmin);
    $stmt->bindParam(':image', $image);
    return $stmt->execute();
  }

  public function getUsersWithCourses()
  {
    $query = "SELECT 
        u.id AS user_id,
        u.email AS user_email,
        GROUP_CONCAT(DISTINCT c.title ORDER BY c.title) AS courses_titles,
        GROUP_CONCAT(DISTINCT c.id ORDER BY c.id) AS course_ids
    FROM 
        users u
    LEFT JOIN 
        user_courses uc ON uc.user_id = u.id
    LEFT JOIN 
        courses c ON uc.course_id = c.id
    GROUP BY 
        u.id
    ORDER BY 
        u.email;";

    $stmt = $this->db->prepare($query);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log("Query result: " . json_encode($result));
    return $result;
  }

  // Asignar un curso a un usuario
  public function assignCourse($userId, $courseId)
  {
    // Verificar si el curso ya está asignado
    $stmt = $this->db->prepare("SELECT * FROM user_courses WHERE user_id = :userId AND course_id = :courseId");
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':courseId', $courseId);
    $stmt->execute();

    // Si ya existe una asignación, no se asigna nuevamente
    if ($stmt->rowCount() > 0) {
      return false; // Curso ya asignado
    }

    // Si no está asignado, asignar el curso
    $stmt = $this->db->prepare("INSERT INTO user_courses (user_id, course_id) VALUES (:userId, :courseId)");
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':courseId', $courseId);
    $stmt->execute();

    return true; // Asignación exitosa
  }
  public function revokeCourse($userId, $courseId)
  {
    try {
      // Log de depuración: datos recibidos
      error_log("Received data: userId = " . $userId . ", courseId = " . $courseId);

      // Verificar si el curso está asignado al usuario
      $stmt = $this->db->prepare("SELECT * FROM user_courses WHERE user_id = :userId AND course_id = :courseId");
      $stmt->bindParam(':userId', $userId);
      $stmt->bindParam(':courseId', $courseId);
      $stmt->execute();

      // Si el curso no está asignado, no se puede desasignar
      if ($stmt->rowCount() === 0) {
        return false; // El curso no está asignado
      }

      // Eliminar la asignación
      $stmt = $this->db->prepare("DELETE FROM user_courses WHERE user_id = :userId AND course_id = :courseId");
      $stmt->bindParam(':userId', $userId);
      $stmt->bindParam(':courseId', $courseId);
      $stmt->execute();

      return true; // Desasignación exitosa
    } catch (Exception $e) {
      // Log de depuración: error en el flujo
      error_log("Error in revokeCourse: " . $e->getMessage());
      return false; // Error al desasignar
    }
  }


  public function getUserData($userId)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
  }

  public function updateUserData($userId, $userData)
  {
    $sql = "UPDATE users SET ";
    $params = [];
    foreach ($userData as $key => $value) {
      $sql .= "$key = ?, ";
      $params[] = $value;
    }
    $sql = rtrim($sql, ', ') . " WHERE id = ?";
    $params[] = $userId;

    $stmt = $this->db->prepare($sql);
    return $stmt->execute($params);
  }
}
