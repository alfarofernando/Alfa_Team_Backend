<?php
class LessonController
{
    private $lesson;

    public function __construct($db)
    {
        $this->lesson = new Lesson($db);
    }

    public function getLessonsByCourse($courseId)
    {
        try {
            $lessons = $this->lesson->getLessonsByCourse($courseId);
            echo json_encode(['success' => true, 'data' => $lessons]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function addLesson()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data || json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON input");
            }

            $requiredFields = ['course_id', 'title', 'type', 'content', 'is_enabled', 'order_number'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            $result = $this->lesson->addLesson(
                $data['course_id'],
                $data['title'],
                $data['type'],
                $data['content'],
                $data['is_enabled'],
                $data['order_number']
            );
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateLesson()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data || json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON input");
            }

            $requiredFields = ['id', 'course_id', 'title', 'type', 'content', 'is_enabled', 'order_number'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            $result = $this->lesson->updateLesson(
                $data['id'],
                $data['course_id'],
                $data['title'],
                $data['type'],
                $data['content'],
                $data['is_enabled'],
                $data['order_number']
            );
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteLesson($lessonId)
    {
        try {
            $result = $this->lesson->deleteLesson($lessonId);
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
