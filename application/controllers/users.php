<?php

class Users extends MY_Controller
{
    
    protected $permission = 4096;
    
    public function index(){
        redirect(site_url('users/load_all_users'));
    }
    
    public function load_all_users(){
        $userInstance = new User();
        $allUsers = $userInstance->get();
        $data = array('allUsers' => $allUsers);

        $this->LoadViewHeader();
        $this->load->view('users/users_view', $data);
        $this->LoadViewFooter();
    }
    
    public function add_user(){
        $permission_group_instance = new permission_group();
 
        $all_permission_groups = array();
        foreach($permission_group_instance->get() as $group)
        {
            $all_permission_groups[$group->group_permission_value] = $group->group_name;
        }
        
        $data = array('permission_groups'=>$all_permission_groups);
    
        
        $this->form_validation->set_rules(array(
            array(
                'field' => 'username',
                'label' => get_resource(res_user_name),
                'rules' => 'required'
            ),
            array(
                'field' => 'password',
                'label' => get_resource(res_password),
                'rules' => 'required'
            ),
            array(
                'field' => 'full_name',
                'label' => get_resource(res_full_name),
                'rules' => 'required'
            ),
            array(
                'field' => 'phone_number',
                'label' => get_resource(res_phone_number),
                'rules' => 'is_numeric'
            ),
            array(
                'field' => 'permission_group',
                'label' => get_resource(res_permission),
                'rules' => 'required|is_numeric'
            ),
        ));
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');

        if (!$this->form_validation->run())
        {
            $this->LoadViewHeader();
            $this->load->view('users/new_user_view', $data);
            $this->loadViewFooter();
        }
        else
        {

            $user = new User();

            $user->username = $this->input->post('username');
            $user->password = $this->input->post('password');
            $user->full_name = $this->input->post('full_name');
            $user->email = $this->input->post('email');           
            $user->email = $this->input->post('email');
            $user->phone_number = $this->input->post('phone_number');
            $user->permission_level = $this->input->post('permission_group');
            $user->language = $this->input->post('language');
            
            $post = $this->input->post();
            
            $user->is_admin = isset($post['is_admin']);
            
            $user->save();

            redirect(site_url('users/load_all_users'));
        }
    }
    
    public function view_edit_user($user_id){
        $user = new User();
        $user->load($user_id);
        
        $permission_group_instance = new permission_group();
        $all_permission_groups = array();
        foreach($permission_group_instance->get() as $group)
        {
            $all_permission_groups[$group->group_permission_value] = $group->group_name;
        }
                
        $data = array('user'=>$user, 'all_permission_groups'=>$all_permission_groups);
        
        $this->form_validation->set_rules(array(
            array(
                'field' => 'username',
                'label' => get_resource(res_user_name),
                'rules' => 'required'
            ),
            array(
                'field' => 'password',
                'label' => get_resource(res_password),
                'rules' => 'required'
            ),
            array(
                'field' => 'full_name',
                'label' => get_resource(res_full_name),
                'rules' => 'required'
            ),
            array(
                'field' => 'phone_number',
                'label' => get_resource(res_phone_number),
                'rules' => 'is_numeric'
            ),
            array(
                'field' => 'permission_group',
                'label' => get_resource(res_permission),
                'rules' => 'required'
            ),
        ));
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');

        if (!$this->form_validation->run())
        {
            $this->LoadViewHeader();
            $this->load->view('users/single_user_view', $data);
            $this->loadViewFooter();
        }
        else
        {

            $user = new User();

            $user->user_id = $this->input->post('user_id');
            $user->username = $this->input->post('username');
            $user->password = $this->input->post('password');
            $user->full_name = $this->input->post('full_name');
            $user->email = $this->input->post('email');           
            $user->email = $this->input->post('email');
            $user->phone_number = $this->input->post('phone_number');
            $user->permission_level = $this->input->post('permission_group');
            $user->language = $this->input->post('language');

            $post = $this->input->post();
            
            $user->is_admin = isset($post['is_admin']);
            
            if(current_user()->user_id == $user->user_id)
            {
                $user->set_language_cookie();
            }
            
            $user->save();

            redirect(site_url('users/load_all_users'));
    }
    
    }
    
    public function delete_user($user_id){
        $post = $this->input->post();
        
        $user = new User();
        $user->load($user_id);

        if (isset($post['no']))
        {
            redirect(site_url('users/load_all_users'));
            return;
        }
        if (isset($post['yes']))
        {
            $user->delete();
            redirect(site_url('users/load_all_users'));
            return;
        }
        
        $data = array('message' => get_resource(res_are_you_sure));
        
        $this->LoadViewHeader();
        $this->load->view('yes_no_message_box', $data);
        $this->LoadViewFooter();
    }
    
    function json_get_current_user()
    {
        $user = array(
            'user_id'=> current_user()->user_id,
            'full_name'=> current_user()->full_name,
            );
        echo json_encode($user);
    }
}
