<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Global_functions{
    
        public function get_students_by_academic_year($academicYear)
        {
            $sql = "SELECT st.* FROM student_levels sl JOIN students st ON sl.student_id = st.student_id WHERE sl.academic_year = ". "'". $academicYear . "'";
            $query = $this->db->query($sql);
            $class_students = array();
            $this->load->model('student');
            foreach($query->result('student') as $student){
                $class_students[$student->student_id] = $student;
            }
            return $class_students;
        }
}
