<?php

class Permission extends MY_Model{
    const DB_TABLE = 'permissions';
    const DB_TABLE_PK = 'permission_id';
    
    public $permission_id;
    
    public $permission_name; 
    
    public $permission_level;
    
    public function get_permissions_array(){
        $permission_array = array();
        foreach($this->get() as $permission){
            $permission_array[$permission->permission_level] = $permission->permission_name;
        }
        return $permission_array;
    }
    
    
    public function generate_permission_integer($permissions_array)
    {
        $permission_integer = 0;
        
        foreach($permissions_array as $p)
        {
            $permission_integer += $p->permission_level;
        }
        
        return $permission_integer;
    }
    
    public function get_permissions_from_integer($permission_level)
    {
        $permissions = array();
        $all_permissions = $this->get();
                       
        foreach($all_permissions as $p)
        {  
            $cpu = ((int)$permission_level & (int)$p->permission_level) ;
            if($cpu)
            {
                $permissions[$p->permission_level] = $p;
            }
        }
        
        return $permissions;
    }

}
