<?php

class Permission_group extends MY_Model{
    const DB_TABLE = 'permission_groups';
    const DB_TABLE_PK = 'group_id';
    
    public $group_id;
    
    public $group_name;
    
    public $group_permission_value; 
    
    public $group_description;
    
    public function get_permissions()
    {
        $permission = new Permission();
        return $permission->get_permissions_from_integer($this->group_permission_value);
    }
}