<?php
class Lesson
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getLessonsByCourse($courseId)
    {
        $query = "SELECT * FROM lessons WHERE course_id = :course_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addLesson($course_id, $title, $type, $content, $is_enabled, $order_number)
    {
        try {
            $query = "INSERT INTO lessons (course_id, title, type, content, is_enabled, order_number) 
                      VALUES (:course_id, :title, :type, :content, :is_enabled, :order_number)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':is_enabled', $is_enabled, PDO::PARAM_BOOL);
            $stmt->bindParam(':order_number', $order_number, PDO::PARAM_INT);
            $stmt->execute();

            return ['success' => true, 'message' => 'Lesson added successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error adding lesson: ' . $e->getMessage()];
        }
    }

    public function updateLesson($id, $course_id, $title, $type, $content, $is_enabled, $order_number)
    {
        try {
            $query = "UPDATE lessons 
                      SET course_id = :course_id, title = :title, type = :type, content = :content, 
                          is_enabled = :is_enabled, order_number = :order_number
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':is_enabled', $is_enabled, PDO::PARAM_BOOL);
            $stmt->bindParam(':order_number', $order_number, PDO::PARAM_INT);
            $stmt->execute();

            return ['success' => true, 'message' => 'Lesson updated successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error updating lesson: ' . $e->getMessage()];
        }
    }

    public function deleteLesson($lessonId)
    {
        try {
            $query = "DELETE FROM lessons WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $lessonId, PDO::PARAM_INT);
            $stmt->execute();
            return ['success' => true, 'message' => 'Lesson deleted successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error deleting lesson: ' . $e->getMessage()];
        }
    }
}
