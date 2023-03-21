<?php

class enrollment extends MY_Model{
    const DB_TABLE = 'enrollments';
    const DB_TABLE_PK = 'enrollment_id';
    
    public $enrollment_id;
    
    public $student_id;
    
    public $class_id;
    
    public $academic_year;
    
    public $fees_due;
    
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
    
    
    //get a particular enrollment given the student_id, class_id and academic year
    public function get_enrollment($student_id, $class_id, $academic_year)
    {                
        $query = $this->db->get_where($this::DB_TABLE, array('student_id'=>$student_id, 'class_id'=>$class_id, 'academic_year'=>$academic_year));
        if($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            return null;
        }
    }
    
    
    //get all enrollments for a particular academic year
    public function get_enrollment_by_year($academic_year)
    {                
        $query = $this->db->get_where($this::DB_TABLE, array('academic_year'=>$academic_year));
        
        $ret_val = array();
        $class = get_class($this);
        foreach($query->result() as $row){
            $model = new $class;
            $model->populate($row);
            $ret_val[$row->{$this::DB_TABLE_PK}] = $model;
        }
        return $ret_val;
    }
    
    
    //get all enrollments for a particular class and academic year
    public function get_enrollment_by_class_and_year($class_id, $academic_year)
    {                
        $query = $this->db->get_where($this::DB_TABLE, array('class_id'=>$class_id, 'academic_year'=>$academic_year));
        
        $ret_val = array();
        $class = get_class($this);
        foreach($query->result() as $row){
            $model = new $class;
            $model->populate($row);
            $ret_val[$row->{$this::DB_TABLE_PK}] = $model;
        }
        return $ret_val;
    }
    
    /*
     * populates this particular enrollment object with an enrollment gotten by the provided
     * student_id, class_id and academic_year
     */
    public function load_enrollment($student_id, $class_id, $academic_year){
        $enrollment = $this->get_enrollment($student_id, $class_id, $academic_year);
        if(isset($enrollment)){
            $this->populate($enrollment);
        }
    }
    
    /*
     * get's all enrollments by a student
     */
    public function get_student_enrollments($student_id, $order_by = 'desc')
    {         
        $this->db->order_by('enrollment_id', $order_by);
        $query = $this->db->get_where($this::DB_TABLE, array('student_id'=>$student_id));
        
        $ret_val = array();
        $class = get_class($this);
        foreach($query->result() as $row){
            $model = new $class;
            $model->populate($row);
            $ret_val[$row->{$this::DB_TABLE_PK}] = $model;
        }
        return $ret_val;
    }
    
    /* 
     * returns a string describing the fee payment status of this enrollment
     * unpaid, incomplete or complete
     */
    public function fee_payment_status()
    {
        $fees_due = $this->fees_due();
        
        if($fees_due >= $this->fees_due)
        {
            return get_resource(res_unpaid);
        }
        if($fees_due < $this->fees_due && $fees_due > 0)
        {
            return get_resource(res_incomplete);
        }
        
        if($fees_due ==  0)
        {
            return get_resource(res_complete);
        }
        return 'N/A';
    }
    
    
    /*
     * returns a status string which would be read by css code in areas where
     * this enrollment is displayed
     */
    public function status_for_css()
    {
        $fees_due = $this->fees_due();
        
        if($fees_due >= $this->fees_due)
        {
            return 'unpaid';
        }
        if($fees_due < $this->fees_due && $fees_due > 0)
        {
            return 'incomplete';
        }
        
        if($fees_due ==  0)
        {
            return 'complete';
        }
        return 'N/A';
    }
    
    /*
     * returns true if fees for this enrollment have completely been paid for
     */
    public function fees_completed()
    {
        $fees_due = $this->fees_due();
        return $fees_due == 0;
    }
    
    
    public function fees_unpaid()
    {
        $fees_due = $this->fees_due();
        
        return $fees_due >= $this->fees_due;
    }
    
    
    public function fees_incomplete()
    {
        $fees_due = $this->fees_due();
        
        return ($fees_due < $this->fees_due && $fees_due > 0);
    }
    
    /*
     * Returns the fees outstanding for the enrollment
     */
    public function fees_due()
    {
//        $class_fees = $this->get_class()->class_fees;
//        
//        //factor in any scholarships awarded
//        $payable = $class_fees - $this->scholarship_amount();
//                
//        $fees_paid = $this->fees_paid();
//        
//        $fees_due =  $payable - $fees_paid;
//        
//        return $fees_due > 0 ? $fees_due : 0;
        
        $sql = 'select fees_outstanding from enrollment_details where enrollment_id = ' . $this->enrollment_id;
        $query = $this->db->query($sql);
        return $query->row()->fees_outstanding;
    }
    
    public function fees_paid()
    {
//        $fee_payment_instance = new Student_fee_payment();
//        $fees_paid = $fee_payment_instance->total_fees_paid($this->enrollment_id);
//        return $fees_paid;
        
        $sql = 'select amount_paid from enrollment_details where enrollment_id = ' . $this->enrollment_id;
        $query = $this->db->query($sql);
        return $query->row()->amount_paid;
    }
    
    public function is_scholarship()
    {
//        $sql = "select * from scholarships where enrollment_id = " . $this->enrollment_id;
//        $query = $this->db->query($sql);
//        return $query->num_rows() > 0 ? true : false;
        
        $scholarship_amount = $this->scholarship_amount();
        return $scholarship_amount > 0;
    }
    
    public function scholarship_amount()
    {
        $sql = 'select scholarship_amount from enrollment_details where enrollment_id = ' . $this->enrollment_id;
        $query = $this->db->query($sql);
        return $query->row()->scholarship_amount;
        
//        $sql = "select coalesce(sum(amount), 0) as amount from scholarships where enrollment_id = " 
//                . $this->enrollment_id;
//        
//        $query = $this->db->query($sql);
//        
//        $row = $query->row();
//        
//        return $row->amount;
    }
    
    /*
     * Overrides the parent delete method
     */
    public function delete()
    {
        $id = $this->enrollment_id;
        
        //delete payments
        $sql = "delete from student_fee_payments where enrollment_id = " . $id;
        $this->db->query($sql);
        
        //delete scholarships
        $sql = "delete from scholarships where enrollment_id = " . $id;
        $this->db->query($sql);
        
        //delete enrollment_courses
        $sql = "delete from enrollment_courses where enrollment_id = " . $id;
        $this->db->query($sql);
        
        //delete enrollment proper
        $sql = "delete from enrollments where enrollment_id = " . $id;
        $this->db->query($sql);
    }
}
