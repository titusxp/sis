<?php

class collection extends MY_Model{
    const DB_TABLE = 'collections';
    const DB_TABLE_PK = 'collection_id';
    
    public $collection_id;
    
    public $type_id;
    
    public $amount_due;
    
    public $academic_year;
    
    public $class_id;
    
    public $student_id;
    
    public $staff_id;
    
    public $salary_month_index;
    
    public $notes;
    
     
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
    
    public function safely_delete_collection($id = 0)
    {
        if($id == 0)
        {
            $id = $this->{$this::DB_TABLE_PK};
        }
        $sql = 'CALL delete_collection(' . $id . ')';
        $this->db->query($sql);
    }
    
    public function exists()
    {
        $query = $this->db->get_where($this::DB_TABLE, 
                array(
                    'class_id' => $this->class_id, 
                    'academic_year'=>$this->academic_year, 
                    'student_id'=>$this->student_id));
        
        return $query->num_rows() > 0 ? true : false;
    }
}
