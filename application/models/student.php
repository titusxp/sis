<?php

class Student extends My_Model{
    
    const DB_TABLE = 'students';
    const DB_TABLE_PK = 'student_id';
    
    
    public $student_id;
    
    public $student_name;
    
    public $date_of_birth;
    
    public $gender;
    
    public $picture;
    
    public $language;
    
    public $student_number;
    
    public $date_created;
    
    public $guardian_name;
    
    public $guardian_phone_number;

    //receives a student number and returns the student object
    public function get_student_from_number($student_number) {
        $query = $this->db->get_where($this::DB_TABLE, array('student_number' => $student_number,));
        $this->populate($query->row());
    }
    
    public function get_students_by_keyword($keyword){
        $sql = "Select * From students where Student_name Like '%" . $keyword . "%' OR student_number Like '%" . $keyword . "%' ORDER BY student_id DESC";
        $query = $this->db->query($sql);
        
        $students = array();
        
        foreach($query->result('student') as $student){
            $students[$student->student_id] = $student;
        }
        return $students;
    }

    //gives us the full path to the current student's picture
    public function get_full_picture_path() {
        return student_images_path() . $this->picture;
    }

    //overrides parent method. here we want to
    //set the student's number before saving
    public function save() {
        //if it's an update operation, proceed as normal
        if (isset($this->{$this::DB_TABLE_PK}))
        {
            if(empty($this->student_number))
            {
                $this->student_number = $this->get_new_student_number();
            }
            $this->update();
        }
        else
        {
            $this->student_number = $this->get_new_student_number();
            $this->insert();
        }
    }

    private function get_appropriate_year() {
        $current_year = strftime("%Y", time());
        $current_month = strftime("%m", time());
        if($current_month > 7) //July
        {
            return $current_year + 1;
        }
        return $current_year;
    }

    private function get_new_student_number() {
        $year = $this->get_appropriate_year();
        $sql = "SELECT Count(*) AS student_count FROM students Where student_number like '%C____" . $year . "'";
        $query = $this->db->query($sql);
        if ($query->num_rows() < 1)
        {
            return $this->build_student_number(1, $year);
        }
        else
        {
            $result = $query->row();
            return $this->build_student_number($result->student_count + 1, $year);
        }
    }

    private function build_student_number($number, $year) {
        $number_of_zeros_to_add = 4 - strlen($number);

        for ($i = 0; $i < $number_of_zeros_to_add; $i++)
        {
            $number = '0' . $number;
        }
        return "C" . $number . $year;
    }
    
    public function get_all_students()
    {
        $sql = 'SELECT * FROM students ORDER BY student_id DESC';
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function search_by_keyword($keyword)
    {
        $sql = "CALL search_student('". $keyword . "')";
        $query = $this->db->query($sql);
        return $query->result();
    }

}

