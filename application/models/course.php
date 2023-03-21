<?php
class Course extends My_Model{
    
    const DB_TABLE = 'courses';
    const DB_TABLE_PK = 'course_id';
    
    public $course_id;
    
    public $course_name;
    
    public $course_code;
    
    public $course_description;
    public $class_id;

    public function get_courses_by_class($class_id) {
        $sql = "SELECT * FROM courses WHERE class_id = " . $class_id;
        $query = $this->db->query($sql);
        return $query->result();
    }
    
//    public function course_exists($course_code, $course_name, $class_id)
//    {
//        $sql = 'Select * from ' . $this::DB_TABLE . ' WHERE class_id = ? and (course_code = ? or course_name = ?)';
//        $query = $this->db->query($sql, array($class_id, $course_code, $course_name));
//        return $query->num_rows()> 0? TRUE: FALSE;
//    }
    
    public function course_exists($course_code, $course_name, $class_id, $course_id = 0)
    {
        $sql = 'Select * from ' . $this::DB_TABLE . ' WHERE course_id != ? and class_id = ? and (course_code = ? or course_name = ?)';
        $query = $this->db->query($sql, array($course_id, $class_id, $course_code, $course_name));
        return $query->num_rows()> 0? TRUE: FALSE;
    }

}