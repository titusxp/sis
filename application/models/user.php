<?php

class User extends MY_Model{
    const DB_TABLE = 'users';
    const DB_TABLE_PK = 'user_id';
    
    public $user_id;
    
    public $username;
    
    public $password;
    
    public $email;
    
    public $phone_number;
    
    public $full_name;
    
    public $permission_level;
    
    public $is_admin;
    
    public $language;
    
    public function get_user($username, $password){
        $query =  $this->db->get_where('users', array('username' => $username, 'password'=>$password));
        if($query->num_rows() > 0)
        { 
            $this->populate($query->row());           
        }        
    }
    
    public function get_permissions()
    {
        $permission = new Permission();
        return $permission->get_permissions_from_integer($this->permission_level);
    }
    
    public function get_logged_in_user()
    {
        $user = new User();
        $user->load($this->logged_in_user_id());
        return $user;
    }
    
    public function logged_in_user_id()
    {
        return $this->session->userdata('user_id');
    }
    
    public function get_cookie_language()
    {
        return $this->input->cookie('user_language', false);
    }
    
    public function set_language_cookie()
    {
        $this->load->helper('cookie');     
        $cookie = array(
            'name'   => 'user_language',
            'value'  => $this->language,
            'expire' =>  86500,
            'secure' => false
        );
        $this->input->set_cookie($cookie);
    }
    


}
