<?php

class User_Permission extends MY_Model{
    const DB_TABLE = 'user_permissions';
    const DB_TABLE_PK = 'user_permission_id';
    
    public $user_permission_id;
    
    public $user_id;
    
    public $permission_id;

}
