<?php
class Enrollment_course extends My_Model{
    
    const DB_TABLE = 'enrollment_courses';
    const DB_TABLE_PK = 'enrollment_course_id';
    
    public $enrollment_course_id;
    
    public $student_id;
    
    public $Grade;
    
    public $course_id;
    
    public $teaching_staff_id;
    
    public $score;
    
    public $enrollment_id;
    
     public function Course(){
//         $sql = 'Select * from courses Where course_id = ' . $this->course_id;
//         $query = $this->db->query($sql);
         
         $query = $this->db->get_where('courses', array('course_id'=>$this->course_id));
         if($query->num_rows() > 0)
         {
             return $query->row();
         }
         return null;
     }
     
      public function student(){
//         $sql = 'Select * from students Where student_id = ' . $this->student_id;
//         $query = $this->db->query($sql);
//         
         $query = $this->db->get_where('students', array('student_id'=>$this->student_id));
         if($query->num_rows() > 0)
         {
             return $query->row();
         }
         return null;
     }
     
     public function staff()
     {
         if($this->teaching_staff_id > 0)
         {             
             $query = $this->db->get_where('staff', array('staff_id'=>$this->teaching_staff_id));
             if($query->num_rows() > 0)
             {
                 return $query->row();
             }
         }
         return null;
     }
     
     public function get_all_student_courses($student_id, $class_id, $academic_year){
         $sql = 'SELECT  ec.* FROM enrollment_courses ec JOIN enrollments e ON ec.enrollment_id = e.enrollment_id Where ec.student_id = ' . $student_id . ' AND e.class_id = ' . $class_id . ' AND e.academic_year = \'' . $academic_year . '\'';
         $query = $this->db->query($sql);
         $student_courses = array();
         foreach($query->result() as $row){
             $enrollment_course = new enrollment_course();
             $enrollment_course->populate($row);
             $student_courses[$enrollment_course->enrollment_course_id] = $enrollment_course;
         }
         return $student_courses;
     }
     
     public function delete_courses_from_enrollment($enrollment_id){
         $sql = 'Delete From enrollment_courses where enrollment_id = ' . $enrollment_id;
         $query = $this->db->query($sql);
     }
}