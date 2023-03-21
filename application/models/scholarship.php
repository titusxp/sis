<?php
class Scholarship extends MY_Model{
    const DB_TABLE = 'scholarships';
    const DB_TABLE_PK = 'scholarship_id';
    
    public $scholarship_id;
    
    public $enrollment_id;
    
    public $amount;
    
    public $date_recorded;
    
    public $recorded_by_user_id;
    
    public $description;
     
    function get_enrollment()
    {
        $enrollment = new enrollment();
        $enrollment->load($this->enrollment_id);
        return $enrollment;
    }
    
    public function get_recording_user()
    {
        $user = new User();
        $user->load($this->recorded_by_user_id);
        return $user;
    }
    
    function get_scholarships($academic_year)
    {
//        $start_date = get_year($academic_year, 1) . '-1-1';
//        $end_date = get_year($academic_year, 2) . '-12-31';
        
       // $sql = "Select * from scholarships where date_recorded >= ? and date_recorded <= ? order by scholarship_id desc";
        $sql = "select sc.* from scholarships sc join enrollments en on sc.enrollment_id = en.enrollment_id where en.academic_year = ?";
        
        $query = $this->db->query($sql, array($academic_year));
        
        $scholarships = array();
        
        foreach($query->result() as $row)
        {
            $scholarship = new Scholarship();
            $scholarship->populate($row);
            
            $scholarships[$scholarship->scholarship_id] = $scholarship;
        }
        return $scholarships;
    }
    
    function load_from_enrollment_id($enrollment_id)
    {
        $query = $this->db->get_where($this::DB_TABLE,array('enrollment_id'=>$enrollment_id));
        $row = $query->row();
        $this->populate($row);
    }
}