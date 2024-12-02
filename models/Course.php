<?php
class Course
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllCourses()
    {
        $query = "SELECT id, title, description, category, price, level, creado_en FROM courses";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseById($id)
    {
        // Obtén los detalles del curso
        $query = "SELECT id, title, description, category, price, level, image, creado_en 
              FROM courses 
              WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($course) {
            // Obtén las lecciones asociadas
            $lessonsQuery = "SELECT id, course_id, title, type, content 
                         FROM lessons 
                         WHERE course_id = :course_id";
            $lessonsStmt = $this->db->prepare($lessonsQuery);
            $lessonsStmt->bindParam(':course_id', $id, PDO::PARAM_INT);
            $lessonsStmt->execute();
            $lessons = $lessonsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Añade las lecciones al curso
            $course['lessons'] = $lessons;
        }

        return $course;
    }


    public function addCourse($title, $description, $price, $level, $category, $image, $lessons)
    {
        try {
            // Inicia una transacción para asegurar que todo se guarde correctamente
            $this->db->beginTransaction();

            // Inserta el curso en la tabla `courses`
            $query = "INSERT INTO courses (title, description, price, level, category, image) 
                      VALUES (:title, :description, :price, :level, :category, :image)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':level', $level);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':image', $image);
            $stmt->execute();

            // Obtén el ID del curso recién creado
            $courseId = $this->db->lastInsertId();

            // Inserta las lecciones asociadas
            $lessonQuery = "INSERT INTO lessons (course_id, title, type, content) 
                            VALUES (:course_id, :title, :type, :content)";
            $lessonStmt = $this->db->prepare($lessonQuery);

            foreach ($lessons as $lesson) {
                $lessonStmt->bindParam(':course_id', $courseId);
                $lessonStmt->bindParam(':title', $lesson['title']);
                $lessonStmt->bindParam(':type', $lesson['type']);
                $lessonStmt->bindParam(':content', $lesson['content']);
                $lessonStmt->execute();
            }

            // Confirma la transacción
            $this->db->commit();

            return ['success' => true, 'message' => 'Course and lessons added successfully', 'course_id' => $courseId];
        } catch (Exception $e) {
            // Revertir la transacción si algo falla
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Error adding course and lessons: ' . $e->getMessage()];
        }
    }



    public function updateCourse($id, $title, $description, $price, $level, $category = null, $image = null, $lessons = [])
    {
        try {
            $this->db->beginTransaction();
            error_log("Starting transaction for updating course ID: $id");

            // Actualiza el curso en la tabla `courses`
            $query = "UPDATE courses 
                  SET title = :title, description = :description, price = :price, 
                      level = :level, category = :category, image = :image
                  WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':level', $level);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':image', $image);
            $stmt->execute();

            error_log("Course updated with title: $title");

            // Borra las lecciones antiguas
            $deleteLessonsQuery = "DELETE FROM lessons WHERE course_id = :course_id";
            $deleteStmt = $this->db->prepare($deleteLessonsQuery);
            $deleteStmt->bindParam(':course_id', $id);
            $deleteStmt->execute();
            error_log("Deleted old lessons for course ID: $id");

            // Inserta las nuevas lecciones
            if (!empty($lessons)) {
                $lessonQuery = "INSERT INTO lessons (course_id, title, type, content) 
                            VALUES (:course_id, :title, :type, :content)";
                $lessonStmt = $this->db->prepare($lessonQuery);

                foreach ($lessons as $lesson) {
                    $lessonStmt->bindParam(':course_id', $id);
                    $lessonStmt->bindParam(':title', $lesson['title']);
                    $lessonStmt->bindParam(':type', $lesson['type']);
                    $lessonStmt->bindParam(':content', $lesson['content']);
                    $lessonStmt->execute();
                    error_log("Inserted lesson: " . $lesson['title']);
                }
            }

            // Confirma la transacción
            $this->db->commit();
            error_log("Transaction committed successfully");

            return ['success' => true, 'message' => 'Course updated successfully'];
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error updating course: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error updating course: ' . $e->getMessage()];
        }
    }


    public function deleteCourse($id)
    {
        $query = "DELETE FROM courses WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
