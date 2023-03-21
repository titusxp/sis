<?php

class staff_member extends My_Model{

    const DB_TABLE = 'staff';
    const DB_TABLE_PK = 'staff_id';
    
    public $staff_id;
    
    public $staff_name;
    
    public $phone_number;
    
    public $email;
    
    public $staff_qualification;
    
    //public $title;
    
    public $gender;
    public $address;
    public $salary;
    public $staff_role;

    public function get_all_staff() {
        $sql = "Select * from staff order by staff_name";
        $query = $this->db->query($sql);
//        $allStaff = array();
//        foreach ($query->result() as $row)
//        {
//            $staff = new Staff_member();
//            $staff->populate($row);
//            $allStaff[$staff->staff_id] = $staff;
//        }
//        return $allStaff;
        return $query->result();
    }
    
    public function get_salary_payments()
    {
        $sql = 'select * from salary_payments where staff_id = ? order by payment_date desc';
        $query = $this->db->query($sql, array($this->staff_id));
        $salary_payments = array();
        foreach($query->result() as $row)
        {
            $payment = new Salary_Payment();
            $payment->populate($row);
            $salary_payments[$payment->payment_id] = $payment;
        }
        
        return $salary_payments;
    }
    
    public function search_by_keyword($keyword)
    {
        $sql = "CALL search_staff('". $keyword . "')";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function get_payroll($academic_year)
    {
        $sql = "CALL get_staff_salaries('$academic_year')";
       // echo $sql;
        $query = $this->db->query($sql);
        return $query->result();
    }
}
