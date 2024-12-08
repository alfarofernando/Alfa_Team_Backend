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
        $query = "SELECT * FROM courses";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseById($id)
    {
        $query = "SELECT * FROM courses WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($course) {
            $lessonsQuery = "SELECT * FROM lessons WHERE course_id = :course_id ORDER BY order_number ASC";
            $lessonsStmt = $this->db->prepare($lessonsQuery);
            $lessonsStmt->bindParam(':course_id', $id, PDO::PARAM_INT);
            $lessonsStmt->execute();
            $course['lessons'] = $lessonsStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $course;
    }

    public function getCoursesByUserId($id)
    {
        $query = "SELECT DISTINCT c.* FROM user_courses uc JOIN courses c ON uc.course_id = c.id WHERE uc.user_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCourse($title, $description, $price, $level, $category, $is_enabled, $lessons)
    {
        try {
            $this->db->beginTransaction();

            // Aquí guardamos el curso
            $query = "INSERT INTO courses (title, description, price, level, category, is_enabled)
VALUES (:title, :description, :price, :level, :category, :is_enabled)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':level', $level);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':is_enabled', $is_enabled, PDO::PARAM_BOOL);
            $stmt->execute();

            $courseId = $this->db->lastInsertId();

            // Guardar las lecciones del curso
            $lessonQuery = "INSERT INTO lessons (course_id, title, type, content, is_enabled, order_number)
VALUES (:course_id, :title, :type, :content, :is_enabled, :order_number)";
            $lessonStmt = $this->db->prepare($lessonQuery);

            foreach ($lessons as $lesson) {
                $lessonStmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
                $lessonStmt->bindParam(':title', $lesson['title']);
                $lessonStmt->bindParam(':type', $lesson['type']);
                $lessonStmt->bindParam(':content', $lesson['content']);
                $lessonStmt->bindParam(':is_enabled', $lesson['is_enabled'], PDO::PARAM_BOOL);
                $lessonStmt->bindParam(':order_number', $lesson['order_number'], PDO::PARAM_INT);
                $lessonStmt->execute();
            }

            $this->db->commit();
            return ['success' => true, 'message' => 'Course and lessons added successfully', 'course_id' => $courseId];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Error adding course and lessons: ' . $e->getMessage()];
        }
    }

    public function updateCourse($id, $title, $description, $price, $level, $category, $is_enabled, $lessons)
    {
        try {
            $this->db->beginTransaction();

            // Actualizar el curso
            $query = "UPDATE courses
SET title = :title, description = :description, price = :price,
level = :level, category = :category, is_enabled = :is_enabled
WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':level', $level);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':is_enabled', $is_enabled);
            $stmt->execute();

            // Eliminar lecciones antiguas
            $deleteLessonsQuery = "DELETE FROM lessons WHERE course_id = :course_id";
            $deleteStmt = $this->db->prepare($deleteLessonsQuery);
            $deleteStmt->bindParam(':course_id', $id);
            $deleteStmt->execute();

            // Agregar nuevas lecciones
            if (is_array($lessons)) {
                $lessonQuery = "INSERT INTO lessons (course_id, title, type, content, is_enabled, order_number)
VALUES (:course_id, :title, :type, :content, :is_enabled, :order_number)";
                $lessonStmt = $this->db->prepare($lessonQuery);

                foreach ($lessons as $lesson) {
                    $lessonStmt->bindParam(':course_id', $id);
                    $lessonStmt->bindParam(':title', $lesson['title']);
                    $lessonStmt->bindParam(':type', $lesson['type']);
                    $lessonStmt->bindParam(':content', $lesson['content']);
                    $lessonStmt->bindParam(':is_enabled', $lesson['is_enabled']);
                    $lessonStmt->bindParam(':order_number', $lesson['order_number']);
                    $lessonStmt->execute();
                }
            }

            $this->db->commit();
            return ['success' => true, 'message' => 'Course updated successfully'];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Error updating course: ' . $e->getMessage()];
        }
    }


    public function disableCourse($id)
    {
        $query = "UPDATE courses SET is_enabled = NOT is_enabled WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteCourse($id)
    {
        try {
            // Iniciar transacción
            $this->db->beginTransaction();

            // Eliminar las relaciones en user_courses
            $deleteUserCoursesQuery = "DELETE FROM user_courses WHERE course_id = :course_id";
            $deleteUserCoursesStmt = $this->db->prepare($deleteUserCoursesQuery);
            $deleteUserCoursesStmt->bindParam(':course_id', $id, PDO::PARAM_INT);
            $deleteUserCoursesStmt->execute();

            // Eliminar las lecciones asociadas al curso
            $deleteLessonsQuery = "DELETE FROM lessons WHERE course_id = :course_id";
            $deleteLessonsStmt = $this->db->prepare($deleteLessonsQuery);
            $deleteLessonsStmt->bindParam(':course_id', $id, PDO::PARAM_INT);
            $deleteLessonsStmt->execute();

            // Eliminar el curso
            $deleteCourseQuery = "DELETE FROM courses WHERE id = :id";
            $deleteCourseStmt = $this->db->prepare($deleteCourseQuery);
            $deleteCourseStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $deleteCourseStmt->execute();

            // Confirmar transacción
            $this->db->commit();

            return ['success' => true, 'message' => 'Course and related data deleted successfully'];
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Error deleting course: ' . $e->getMessage()];
        }
    }
}
