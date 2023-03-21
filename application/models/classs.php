<?php
class Classs extends My_Model
{

    const DB_TABLE = 'classes';
    const DB_TABLE_PK = 'class_id';

    public $class_id;
    public $class_name;
    public $class_index;
    public $class_fees;
    public $class_section;

    public function class_index_exists($class_section, $class_index)
    {
        $query = $this->db->get_where($this::DB_TABLE, array('class_index'=>$class_index, 'class_section'=>$class_section));
        return $query->num_rows() > 0;
    }
    
    public function is_single_class_index($class_id, $class_section, $class_index)
    {
        $sql = 'Select * from ' . $this::DB_TABLE . ' where '. $this::DB_TABLE_PK . ' != ? and class_section = ? and class_index = ?';
        $query = $this->db->query($sql, array($class_id, $class_section, $class_index));
        return $query->num_rows() > 0;
    }
    
    public function exists($class_name)
    {
        $query = $this->db->get_where($this::DB_TABLE, array('class_name'=>$class_name));
        return $query->num_rows() > 0;
    }
    
    public function is_single_class_name($class_id, $class_name)
    {
        $sql = 'Select * from ' . $this::DB_TABLE . ' where '. $this::DB_TABLE_PK . ' != ? and class_name = ?';
        $query = $this->db->query($sql, array($class_id, $class_name));
        return $query->num_rows() > 0;
    }
    
    public function get_all_classes() {
        $sql = "SELECT * FROM classes ORDER BY class_section , class_index";
        $query = $this->db->query($sql);      
        return $query->result();
    }
    
    public function get_all_classes_ordered_by_section() {
        $sql = "SELECT * FROM classes ORDER BY class_section , class_index";
        $query = $this->db->query($sql);
        $level_array = array();
        foreach ($query->result() as $row)
        {
            $class = new Classs();
            $class->populate($row);
            $level_array[$class->class_id] = $class;
        }        
        return $level_array;
    }
    
    public function get_full_class_name(){
        return $this->class_name . ' : ' . get_language($this->class_section);
    }

    public function get_all_classes_fully($class_section) {
        $sql = "Select * from classes Where class_section = '" . $class_section . "' order by class_index";
        $query = $this->db->query($sql);
        $classes = array();
        $this->load->model('classs');
        foreach ($query->result('classs') as $class)
        {
            $classes[$class->class_id] = $class;
        }
        return $classes;
    }
    
    public function get_students_by_academic_year($academicYear){
        $sql = "SELECT st.* FROM student_classes sl JOIN students st ON sl.student_id = st.student_id WHERE sl.academic_year = ". "'". $academicYear . "'";
        $query = $this->db->query($sql);
        $class_students = array();
        $this->load->model('student');
        foreach($query->result('student') as $student){
            $class_students[$student->student_id] = $student;
        }
        return $class_students;
    }
    
    public function get_students_by_academic_year_and_class($academicYear, $class_id){
        $sql = 'SELECT st.* FROM enrollments en JOIN students st ON en.student_id = st.student_id WHERE en.academic_year = '. "'". $academicYear . "' AND en.class_id = " . $class_id;
        $query = $this->db->query($sql);
        $class_students = array();
        $this->load->model('student');
        foreach($query->result() as $row)
        {
            $this->load->model('Student');
            
            $student = new Student();
            
            $student->populate($row);
            
            $class_students[$student->student_id] = $student;
        }
        return $class_students;
    }
    
    public function get_class_teacher($class_id)
    {
        $sql = 'SELECT st.* FROM class_teachers ct JOIN staff st ON ct.staff_id = st.staff_id WHERE ct.class_id = ' . $class_id;
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        return null;
    }
    
    public function get_this_class_teacher()
    {
        $sql = 'SELECT st.* FROM class_teachers ct JOIN staff st ON ct.staff_id = st.staff_id WHERE ct.class_id = ' . $this->class_id;
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        return null;
    }
    
    public function add_teacher($teacher_id, $class_id){
        $sql1 = 'Delete from class_teachers where class_id = ' . $class_id;
        $sql2 = 'Insert into class_teachers (staff_id, class_id) values (' . $teacher_id . ', ' . $class_id . ')';
        
        $this->db->query($sql1);
        $this->db->query($sql2);
    }
    
    public function get_next_class($class_id = null){
        if($class_id == null)
        {
            $class_id = $this->class_id;
        }
        if($class_id > 0)
        {
             $sql = "CALL get_next_class($class_id)";
            $query = $this->db->query($sql);
            if($query->num_rows() > 0)
            {
                return $query->row();
            }
        }
        return null;       
    }
    
    public function get_first_class_id()
    {
        $query = $this->db->get($this::DB_TABLE, 1, 0);
        if($query->num_rows() > 0)
        {
            return $query->row()->class_id;
        }
        return 0;
    }

}