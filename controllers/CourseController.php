<?php

require_once __DIR__ . '/../models/Course.php';
class CourseController
{
    private $course;

    public function __construct($db)
    {
        $this->course = new Course($db);
    }

    public function getAllCourses()
    {
        try {
            $courses = $this->course->getAllCourses();
            header('Content-Type: application/json');
            echo json_encode($courses);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error retrieving courses: " . $e->getMessage()]);
        }
    }

    public function getCourseById($id)
    {
        try {
            $course = $this->course->getCourseById($id);

            header('Content-Type: application/json');
            if ($course) {
                echo json_encode($course);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Course not found"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal server error: " . $e->getMessage()]);
        }
    }

    public function getCoursesByUserId($id)
    {
        try {
            $courses = $this->course->getCoursesByUserId($id);
            header('Content-Type: application/json');
            if ($courses) {
                echo json_encode($courses);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "No courses found for user"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error retrieving user courses: " . $e->getMessage()]);
        }
    }

    public function addCourse()
    {
        // Suponiendo que los datos vienen en formato JSON desde el cuerpo de la petición
        $data = json_decode(file_get_contents("php://input"), true);

        // Validación básica
        if (empty($data['title']) || empty($data['description']) || empty($data['price'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }

        $lessons = isset($data['lessons']) ? $data['lessons'] : [];

        $result = $this->course->addCourse(
            $data['title'],
            $data['description'],
            $data['price'],
            $data['level'],
            $data['category'],
            $data['is_enabled'],
            $lessons
        );

        echo json_encode($result);
    }

    public function updateCourse($id)
    {
        // Decodificar el cuerpo de la solicitud
        $data = json_decode(file_get_contents("php://input"), true);

        // Validación básica
        if (empty($data['title']) || empty($data['description']) || empty($data['price'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }

        // Si 'lessons' no existe, lo dejamos como array vacío
        $lessons = isset($data['lessons']) ? $data['lessons'] : [];

        // Pasar directamente el array de lecciones al modelo
        $result = $this->course->updateCourse(
            $id,
            $data['title'],
            $data['description'],
            $data['price'],
            $data['level'],
            $data['category'],
            $data['is_enabled'],
            $lessons // Aquí pasamos el array directamente, no JSON
        );

        // Respuesta al cliente
        echo json_encode($result);
    }


    public function disableCourse($id)
    {
        $result = $this->course->disableCourse($id);
        echo json_encode($result);
    }

    public function deleteCourse($id)
    {
        $result = $this->course->deleteCourse($id);
        echo json_encode($result);
    }
}
