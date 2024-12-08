<?php

class Image
{
    public  $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Guardar nueva imagen asociada a un curso
    public function saveImage($courseId, $imagePath)
    {
        $query = "INSERT INTO images (course_id, image_path) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        if ($stmt->execute([$courseId, $imagePath])) {
            return $this->db->lastInsertId(); // Retornar el ID de la imagen creada
        }
        return false;
    }

    // Actualizar la ruta de imagen en la tabla de imágenes
    public function updateImage($id, $imagePath)
    {
        $query = "UPDATE images SET image_path = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$imagePath, $id]);
    }

    // Actualizar la referencia de `image_id` en la tabla de cursos
    public function updateCourseImage($courseId, $imageId)
    {
        $query = "UPDATE courses SET image_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$imageId, $courseId]);
    }
}
