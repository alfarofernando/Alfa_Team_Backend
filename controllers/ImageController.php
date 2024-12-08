<?php

require_once __DIR__ . '/../models/Image.php';

class ImageController
{
    public $image;

    public function __construct($db)
    {
        $this->image = new Image($db);
    }

    // Crear una nueva imagen asociada a un curso
    public function createImage()
    {
        if (isset($_FILES['image']) && isset($_POST['course_id'])) {
            $courseId = $_POST['course_id'];
            $image = $_FILES['image'];

            $targetDir = __DIR__ . '/../uploads/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $filename = uniqid() . '_' . basename($image['name']);
            $targetFile = $targetDir . $filename;

            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                $imagePath = "/uploads/$filename";
                $imageId = $this->image->saveImage($courseId, $imagePath); // Guarda la imagen en la DB

                if ($imageId) {
                    // Actualizar la referencia en la tabla courses (relacionar con image_id)
                    if ($this->image->updateCourseImage($courseId, $imageId)) {
                        echo json_encode(['message' => 'Image uploaded and course updated successfully']);
                    } else {
                        echo json_encode(['error' => 'Failed to update course with new image']);
                    }
                } else {
                    echo json_encode(['error' => 'Failed to save image to database']);
                }
            } else {
                echo json_encode(['error' => 'Failed to upload image']);
            }
        } else {
            echo json_encode(['error' => 'Invalid input']);
        }
    }


    // Actualizar la imagen de un curso existente
    public function updateImage($courseId)
    {
        if (isset($_FILES['image'])) {
            $image = $_FILES['image'];

            $targetDir = __DIR__ . '/../uploads/';
            $filename = uniqid() . '_' . basename($image['name']);
            $targetFile = $targetDir . $filename;

            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                $imagePath = "/uploads/$filename";
                $currentImageId = $this->getCurrentImageId($courseId); // Obtener la imagen actual asociada al curso

                if ($currentImageId) {
                    // Actualizar la imagen existente en la tabla images
                    if ($this->image->updateImage($currentImageId, $imagePath)) {
                        // También actualizamos la referencia en courses
                        if ($this->image->updateCourseImage($courseId, $currentImageId)) {
                            echo json_encode(['message' => 'Image updated successfully']);
                        } else {
                            echo json_encode(['error' => 'Failed to update course with new image']);
                        }
                    } else {
                        echo json_encode(['error' => 'Failed to update image in database']);
                    }
                } else {
                    echo json_encode(['error' => 'Course does not have an associated image']);
                }
            } else {
                echo json_encode(['error' => 'Failed to upload image']);
            }
        } else {
            echo json_encode(['error' => 'Invalid input']);
        }
    }


    // Obtener el ID de la imagen actual asociada a un curso
    public function getCurrentImageId($courseId)
    {
        $query = "SELECT image_id FROM courses WHERE id = ?";
        $stmt = $this->image->db->prepare($query);
        $stmt->execute([$courseId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['image_id'] : null;
    }
}
