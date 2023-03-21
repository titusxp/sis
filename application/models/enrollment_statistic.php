<?php

class enrollment_statistic extends MY_Model
{
    public $academic_year;
    public $enrollment_count;
    public $completed_fees_count;
    public $unpaid_fees_count;
    public $incomplete_fees_count;
    
    public $total_amount_due;
    public $total_amount_paid;
    public $total_scholarship;
    public $total_outstanding;
    
    
    function __construct($academic_year = false)
    {
        parent::__construct();
        
        if($academic_year)
        {
            $this->academic_year = $academic_year;       
            $this->enrollment_count = $this->get_enrollment_count();        
            $this->unpaid_fees_count = $this->get_unpaid_enrollments_count();
            $this->incomplete_fees_count = $this->get_incomplete_enrollments_count();
            $this->completed_fees_count = $this->get_completed_enrollments_count();
            
            $this->total_amount_due = $this->get_total_amount_due();
            $this->total_amount_paid = $this->get_total_amount_paid();
            $this->total_scholarship = $this->get_total_scholarship();
            $this->total_outstanding = $this->get_total_outstanding();
        }
               
    }   
    
    function get_total_amount_due()
    {
        $sql = "select sum(fees_due) as total_fees_due from enrollment_details where academic_year = '" . $this->academic_year . "'";
        $query = $this->db->query($sql);
        return $query->row()->total_fees_due;
    }
    
    function get_total_amount_paid()
    {
        $sql = "select sum(amount_paid) as total_amount_paid from enrollment_details where academic_year = '" . $this->academic_year . "'";
        $query = $this->db->query($sql);
        return $query->row()->total_amount_paid;
    }
    
    function get_total_scholarship()
    {
        $sql = "select sum(scholarship_amount) as total_scholarship_amount from enrollment_details where academic_year = '" . $this->academic_year . "'";
        $query = $this->db->query($sql);
        return $query->row()->total_scholarship_amount;
    }
    
    function get_total_outstanding()
    {
        $sql = "select sum(fees_outstanding) as total_fees_outstanding from enrollment_details where academic_year = '" . $this->academic_year . "'";
        $query = $this->db->query($sql);
        return $query->row()->total_fees_outstanding;
    }
    
    
    
    /*
     * returns the number of enrollments in this enrollment stat's academic year
     */
    function get_enrollment_count()
    {
        $sql = "select count(*) as enrollment_count from enrollment_details where academic_year = '" . $this->academic_year . "'";
        $query = $this->db->query($sql);
        return $query->row()->enrollment_count;
    }
    
    /*
     * get's all enrollment statistics for all academic years
     */
    function get_all_enrollment_stats()
    {
        $academic_year_instance = new Academic_year();
        $all_years = $academic_year_instance->get_all_academic_years();
        
        $all_enrollment_stats = array();
        
        foreach($all_years as $year)
        {
            $enrollment_stat = new enrollment_statistic($year);
            $all_enrollment_stats[$enrollment_stat->academic_year] = $enrollment_stat;
        }
        return $all_enrollment_stats;
    }
    
    /*
     * get's the number of comleted enrollments in this enrollment stat's academic year
     */
    function get_completed_enrollments_count()
    {
        $sql = "select count(*) as enrollment_count from enrollment_details where fees_outstanding <= 0 and academic_year = '" . $this->academic_year . "'";
        $query = $this->db->query($sql);
        return $query->row()->enrollment_count;
        
//        $count = 0;
//        foreach($this->enrollments as $enrollment)
//        {
//            if($enrollment->fees_paid())
//            {
//                $count ++;
//            }
//        }
//        return $count;
    }
    
    function get_incomplete_enrollments_count()
    {
        $sql = "select count(*) as enrollment_count from enrollment_details where fees_outstanding > 0 and fees_outstanding < fees_due and academic_year = '" . $this->academic_year . "'";
        $query = $this->db->query($sql);
        return $query->row()->enrollment_count;
        
//        $count = 0;
//        foreach($this->enrollments as $enrollment)
//        {
//            if($enrollment->fees_incomplete())
//            {
//                $count ++;
//            }
//        }
//        return $count;
    }
    
    function get_unpaid_enrollments_count()
    {
        $sql = "select count(*) as enrollment_count from enrollment_details where  fees_outstanding >= fees_due and academic_year = '" . $this->academic_year . "'";
        $query = $this->db->query($sql);
        return $query->row()->enrollment_count;
        
//        $count = 0;
//        foreach($this->enrollments as $enrollment)
//        {
//            if($enrollment->fees_unpaid())
//            {
//                $count ++;
//            }
//        }
//        return $count;
    }
}

