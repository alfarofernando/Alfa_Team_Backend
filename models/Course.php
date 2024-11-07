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
        $query = "SELECT id,title, description , category , price, level FROM courses";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        // Fetch todos los resultados como un array de arrays asociativos
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $courses; // Retornar el array de cursos
    }
}
