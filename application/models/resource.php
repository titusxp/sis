<?php

class Resource extends CI_Model
{
    const DB_TABLE = 'resources';
    const DB_TABLE_PK = 'resource_id';
    
    public $resource_id;
    
    public $resource_name;
    
    public $resource_value;
    
    public $language;
    
    public function get_resource($resource_name, $language)
    {
        $sql = 'SELECT resource_value FROM resources where resource_name = ? AND language = ? ';
        $query = $this->db->query($sql, array($resource_name, $language));
        if($query->num_rows() > 0)
        {
            return $query->row()->resource_value;
        }
        return '<RNT error>';
    }
    
}
    
    