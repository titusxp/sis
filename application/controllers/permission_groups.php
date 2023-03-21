<?php

class permission_groups extends MY_Controller
{
    protected $permission = 4096;
    
    public function index()
    {
        redirect(site_url('permission_groups/show_all_groups'));
    }
    
    public function show_all_groups()
    {
        //get all permission groups
        
        $permission_group_instance = new Permission_group();
        $all_permission_groups = $permission_group_instance->get();
        $data = array('all_permission_groups'=>$all_permission_groups);
        
        //display them
        $this->LoadViewHeader();
        $this->load->view('permission_groups/permission_groups_view', $data);
        $this->LoadViewFooter();
    }
    
    public function add_edit_group($group_id = 0)
    {
        //get group
        $group = new permission_group();
        $group->load($group_id);
        $data['permission_group'] = $group;
        
        $this->form_validation->set_rules(array(
            array(
                'field'=>'group_name',
                'label'=>  get_resource(res_group_name),
                'rules'=>'required'
                )        
            ));
        
        if(!$this->form_validation->run())
        {
            $this->LoadViewHeader();
            $this->load->view('permission_groups/add_edit_group_view', $data);
            $this->LoadViewFooter();
        }
        else             
        {
            $this_group = new permission_group();
            $this_group->group_id = $this->input->post('group_id');
            $this_group->group_name = $this->input->post('group_name');
            $this_group->group_description = $this->input->post('group_description');
            
            //calculate permissions integer
            $post = $this->input->post();
            $permissions_integer = 0;
            if(isset($post['permissions']))
            {
                foreach ($post['permissions'] as $p)
                {
                    $permissions_integer += $p;
                }
            }
            $this_group->group_permission_value = $permissions_integer;
            
            if(empty($this_group->group_id)){
                $this_group->group_id = null;
            }
                        
            $this_group->save();
            
//            show_array($this_group);
            
            redirect(site_url('permission_groups/show_all_groups'));
        }
        
    }
}