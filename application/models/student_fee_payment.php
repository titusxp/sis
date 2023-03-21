<?php
class Student_fee_payment extends My_Model{
    
    const DB_TABLE = 'student_fee_payments';
    const DB_TABLE_PK = 'payment_id';
    
    public $payment_id;
    
    public $amount_paid;
    
    public $payment_date;
    
    public $payer;
    
    public $enrollment_id;
    
    public $recorded_by_user_id;
    
    //public $is_scholarship;
    
    public function get_enrollment()
    {
        $enrollment = new enrollment();
        $enrollment->load($this->enrollment_id);
        return $enrollment;
    }
    
    public function get_academic_year()
    {
        return $this->get_enrollment()->academic_year;
    }
    
    public function get_student()
    {        
        $student = new Student();
        $student->load($this->get_enrollment()->student_id);
        return $student;
    }
    
    public function get_recording_user()
    {
        $user = new User();
        $user->load($this->recorded_by_user_id);
        return $user;
    }
    
    public function get_fee_payments_by_academic_year($academic_year)
    {
        $sql = 'SELECT sf.* FROM student_fee_payments sf JOIN enrollments en ON 
                sf.`enrollment_id` = en.`enrollment_id`
                WHERE en.academic_year = ? ORDER BY payment_date DESC';
        
        $query = $this->db->query($sql, array($academic_year));
        
        $payments = array();
        
        foreach ($query->result() as $row)
        {
            $payment = new Student_fee_payment();
            $payment->populate($row);
            
            $payments[$payment->payment_id] = $payment;
        }
        return $payments;
    }
    
    public function get_class()
    {
        $class = new Classs();
        $class->load($this->get_enrollment()->class_id);
        return $class;
    }
    
    public function total_fees_paid($enrollment_id)
    {
        $sql = 'select COALESCE(sum(amount_paid), 0) as sum from student_fee_payments where enrollment_id = ?';
        $query = $this->db->query($sql, array($enrollment_id));
        $fees_paid =  $query->row()->sum;
        return $fees_paid;
    }
}