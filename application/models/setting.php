<?php

class setting extends MY_Model
{
    const DB_TABLE = 'settings';
    const DB_TABLE_PK = 'setting_name';
    
    public $setting_name;
    public $setting_value;
    
    public function get_setting_value($settingName)
    {
        $sql = "SELECT setting_value FROM settings where setting_name = '" . $settingName . "'";
        $query = $this->db->query($sql);
        
        $row = $query->row();
        return $row->setting_value;
    }
}