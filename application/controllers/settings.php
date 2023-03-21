<?php

class settings extends MY_Controller{
    
    protected $permission = 1;
    
    public function index()
    {
        $this->LoadViewHeader();
        $this->load->view('settings/settings_view');       
        $this->LoadViewFooter('settings/settings_view_js');
    }
}