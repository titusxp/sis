<?php
class Model_school_info extends MY_Model{
    const DB_TABLE = 'school_info';
    const DB_TABLE_PK = 'school_id';
    
    public $school_id;
    
    public $school_name;
    
    public $email;
    
    public $phone_number;
    
    public $address;
    
    public $logo;
    
    public $time_zone;
    
    public function get_logo_path(){
        return base_url(school_images_path . $this->logo);
    }
    
    public function load_school_info()
    {
        $query = $this->db->get($this::DB_TABLE, 1);
        
        $this->populate($query->row());
    }
}