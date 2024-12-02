<?php
class CourseController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllCourses()
    {
        try {
            $course = new Course($this->db);
            $courses = $course->getAllCourses();

            if ($courses) {
                echo json_encode($courses);
            } else {
                echo json_encode(["error" => "No courses found"]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => "Error retrieving courses: " . $e->getMessage()]);
        }
    }
    public function getCourseById($id)
    {
        try {
            $course = (new Course($this->db))->getCourseById($id);
            if ($course) {
                echo json_encode($course);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Course not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
        }
    }



    public function addCourse()
    {
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["error" => "Invalid JSON format"]);
            return;
        }

        // Validar los datos del curso
        $title = $inputData['title'] ?? null;
        $description = $inputData['description'] ?? null;
        $price = $inputData['price'] ?? null;
        $level = $inputData['level'] ?? null;
        $category = $inputData['category'] ?? null;
        $image = $inputData['image'] ?? null;

        if (!$title || !$description || !$price || !$level) {
            echo json_encode(["error" => "Missing required fields for course"]);
            return;
        }

        // Validar las lecciones
        $lessons = $inputData['lessons'] ?? [];
        foreach ($lessons as $lesson) {
            if (!isset($lesson['title'], $lesson['type'], $lesson['content'])) {
                echo json_encode(["error" => "Each lesson must have a title, type, and content"]);
                return;
            }
        }

        // Llama al modelo para guardar el curso y las lecciones
        $course = new Course($this->db);
        $result = $course->addCourse($title, $description, $price, $level, $category, $image, $lessons);

        if ($result['success']) {
            echo json_encode(["success" => true, "message" => $result['message'], "course_id" => $result['course_id']]);
        } else {
            echo json_encode(["error" => $result['message']]);
        }
    }


    public function updateCourse($id, $data)
    {
        error_log("Updating course with ID: $id");

        $title = $data['title'];
        $description = $data['description'];
        $price = $data['price'];
        $category = $data['category'];
        $image = $data['image'];
        $level = $data['level'];
        $lessons = json_encode($data['lessons']);

        $course = new Course($this->db);
        $result = $course->updateCourse($id, $title, $description, $price, $level, $category, $image, $data['lessons']);

        echo json_encode(['message' => $result['message']]);
    }


    public function deleteCourse($id)
    {
        $query = "DELETE FROM courses WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
