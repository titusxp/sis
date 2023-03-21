<?php

class collection_detail extends MY_Model{
    const DB_TABLE = 'collection_details';
    const DB_TABLE_PK = 'collection_id';
    
    public $collection_id;
    
    public $type_id;
    
    public $amount_due;
    
    public $academic_year;
    
    public $class_id;
    
    public $student_id;
    
    public $staff_id;
    
    public $staff_name;
    
    public $is_expense;
    
    public $guardian_name;
    
    public $guardian_number;
    
    public $type_name;
    
    public $student_number;
    
    public $student_name;
    
    public $date_of_birth;
    
    public $gender;
    
    public $class_name;
    
    public $amount_paid;
    
    public $deductions;
    
    public $amount_owed;
    
    
    /*
     * get's the student object of this enrollment
     */
    public function get_student()
    {
        $student = new student();
        $student->load($this->student_id);
        return $student;
    }
    
    /*
     * get's the class object of this enrollment
     */
    public function get_class(){
        $class = new Classs();
        $class->load($this->class_id);
        return $class;
    }
    
    /*
     * get's the class object of this enrollment
     */
    public function get_deductions(){
        $deduction = new deduction();
        
        $query = $this->db->get_where($deduction::DB_TABLE, array('collection_id'=>$this->{$this::DB_TABLE_PK}));
        
        $all_deductions = array();
        foreach($query->result() as $row)
        {
            $model = new deduction();
            $model->populate($row);
            $all_deductions[$row->{$deduction::DB_TABLE_PK}] = $model;
        }
        
        return $all_deductions;
    }
    
    /*
     * get's an array representing all fees paid as part of this collection
     */
    public function get_fees()
    {
        $tr = new transaction();
        
        $query = $this->db->get_where($tr::DB_TABLE, array('collection_id'=>$this->{$this::DB_TABLE_PK}));
        
        $all_fees = array();
        
        foreach($query->result() as $row)
        {
            $model = new transaction();
            $model->populate($row);
            $all_fees[$row->{$tr::DB_TABLE_PK}] = $model;
        }
        
        return $all_fees;
    }
    
    public function get_enrollment_collections($class_id = null, $year = null)
    {
        $type_id = get_system_setting(sys_School_Fees_Collection_Id);
        
        $search_array = array();
        
        $search_array['type_id'] = $type_id;
        
        if($class_id != null)
        {
            $search_array['class_id'] = $class_id;
        }
        
        if($year != null)
        {
            $search_array['academic_year'] = $year;
        }
                
        $ret_value = $this->get_where($search_array);
        
        return $ret_value;
    }
    
    public function get_collections($class_id, $year, $type_id)
    {
        $fees_type_id = get_system_setting(sys_School_Fees_Collection_Id);
        $sql = "CALL get_collections_per_class ($fees_type_id, $type_id, $class_id, '$year')";
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    public function get_student_enrollments($student_id)
    {
        $type_id = get_system_setting(sys_School_Fees_Collection_Id);
        
        $search_array = array('student_id'=>$student_id, 'type_id'=>$type_id);
        
        $ret_value = $this->get_where($search_array);
        
        return $ret_value;
    }
    
    public function exists()
    {
        $result = $this->get_where(
                array(
                    'class_id' => $this->class_id, 
                    'academic_year'=>$this->academic_year, 
                    'student_id'=>$this->student_id,
                    'type_id'=>$this->type_id)
                );
                
        return count($result) > 0 ? true : false;
    }
    
    public function get_staff_salaries($staff_id, $academic_year)
    {
        $type_id = get_system_setting(sys_Salaries_Collection_Id);
        $sql = "SELECT collection_id, salary_month_index, amount_paid, month FROM"
                . "  collection_details WHERE type_id = $type_id AND staff_id = "
                . "$staff_id AND academic_year = '$academic_year' order by salary_month_index";
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    public function collection_exists($staff_id, $academic_year, $month_index)
    {
        $array = array(
                'staff_id'=>$staff_id, 
                'academic_year'=>$academic_year, 
                'salary_month_index'=>$month_index
           );
        
        $result = $this->get_where( $array);
//        show_array($result);
//        echo count($result);
        return count($result) > 0 ? true : false;
    }
}
