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
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function addLesson()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $course_id = $data['course_id'] ?? null;
        $title = $data['title'] ?? null;
        $type = $data['type'] ?? null;
        $content = $data['content'] ?? null;

        if (!$course_id || !$title || !$type || !$content) {
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $result = $this->lesson->addLesson($course_id, $title, $type, $content);
        echo json_encode($result);
    }

    public function updateLesson()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'] ?? null;
        $course_id = $data['course_id'] ?? null;
        $title = $data['title'] ?? null;
        $type = $data['type'] ?? null;
        $content = $data['content'] ?? null;

        if (!$id || !$course_id || !$title || !$type || !$content) {
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $result = $this->lesson->updateLesson($id, $course_id, $title, $type, $content);
        echo json_encode($result);
    }

    public function deleteLesson($lessonId)
    {
        try {
            $this->lesson->deleteLesson($lessonId);
            echo json_encode(['success' => true, 'message' => 'Lección eliminada con éxito']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
