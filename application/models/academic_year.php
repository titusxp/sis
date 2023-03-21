<?php

class Academic_year extends CI_Model{
    
    function __construct(){
        parent::__construct();
        $this->load_current_academic_year();
        $this->load_next_academic_year();
        $this->load_all_academic_years();
    }
    
    public $current_academic_year;
    public $next_academic_year;
    
    public $all_academic_years;
    
    public function load_current_academic_year(){        
        return get_current_academic_year();
    }
    
    public function load_next_academic_year(){
        $current_year = strftime("%Y", time());
        $current_month = strftime("%m", time());
        if($current_month > 7) //July
        {
            $year1 = $current_year + 1;
            $year2 = $current_year + 2;
        }
        else{
            $year1 = $current_year;
            $year2 = $current_year + 1;
        }
        return $year1 . '/' . $year2;
    }
    
    public function get_all_academic_years()
    {
        
        mysqli_next_result($this->db->conn_id);
        $AllAcademicYears = array();
        $sql = "CALL get_all_academic_years()";
        $query = $this->db->query($sql);        
        mysqli_next_result($this->db->conn_id);
        foreach($query->result() as $row){
            $AllAcademicYears[$row->academic_year] = $row->academic_year;
        }
        
        return $AllAcademicYears;
    }
    
    public function load_all_academic_years()
    {
        $AllAcademicYears = $this->get_all_academic_years();
                
        $CurrentAcademicYear = $this->load_current_academic_year();
        
        if(!isset($AllAcademicYears[$CurrentAcademicYear])){
            $AllAcademicYears[$CurrentAcademicYear] = $CurrentAcademicYear;
        }
        
        $NextAcademicYear = $this->load_next_academic_year();
        
        if(!isset($AllAcademicYears[$NextAcademicYear])){
            $AllAcademicYears[$NextAcademicYear] = $NextAcademicYear;
        }
        
        $this->all_academic_years = $AllAcademicYears;
        
        rsort($AllAcademicYears);
                
        return $AllAcademicYears;
    }
    

}
