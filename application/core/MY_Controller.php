<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}


class MY_Controller extends CI_Controller
{
    protected $permission = 0;
    
    function __construct() 
    {
        parent::__construct();
        
        $this->output->set_content_type('text/html; charset=utf-8');
        
        if ($this->session->userdata('user_id') == false)
        {
            redirect('authentication');
        }
        
        if($this->permission && !current_user_has_permission($this->permission))
        {
            redirect('authentication');
        }
    }

    public function LoadViewHeader() {
        $this->load->view('header');
    }

    public function LoadViewFooter($angular_js_view = null) 
    {
        $array = array(
            'angular_js_view' => $angular_js_view
        );
        
        $this->load->view('footer', $array);
    }

}
