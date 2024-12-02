<?php
class CourseController
{
    private $course;

    public function __construct($db)
    {
        $this->course = new Course($db);
    }

    // Obtener todos los cursos
    public function getCourses()
    {
        $courses = $this->course->getAllCourses();
        header('Content-Type: application/json');
        echo json_encode($courses);
    }

    
}
