<?php

class CourseController
{
    private $course;

    public function __construct($db)
    {
        $this->course = new Course($db);
    }

    public function getCourses()
    {
        // Obtener todos los cursos desde el modelo
        $courses = $this->course->getAllCourses();
        // Establecer el encabezado de tipo JSON
        header('Content-Type: application/json');
        //Devolver los cursos en formato JSON
        echo json_encode($courses);
    }
}
