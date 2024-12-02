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
        $query = "SELECT id, title, type, content FROM lessons WHERE course_id = :course_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addLesson($course_id, $title, $type, $content)
    {
        try {
            $query = "INSERT INTO lessons (course_id, title, type, content) 
                      VALUES (:course_id, :title, :type, :content)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':content', $content);
            $stmt->execute();

            return ['success' => true, 'message' => 'Lesson added successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error adding lesson: ' . $e->getMessage()];
        }
    }

    public function updateLesson($id, $course_id, $title, $type, $content)
    {
        try {
            $query = "UPDATE lessons 
                      SET course_id = :course_id, title = :title, type = :type, content = :content 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':content', $content);
            $stmt->execute();

            return ['success' => true, 'message' => 'Lesson updated successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error updating lesson: ' . $e->getMessage()];
        }
    }

    public function deleteLesson($lessonId)
    {
        $query = "DELETE FROM lessons WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $lessonId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
