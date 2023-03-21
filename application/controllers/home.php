<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Home extends MY_Controller
{

    public function index(){
        $this->load_dashboard();
    }
    
    public function load_dashboard()
    {     
        $this->LoadViewHeader();
        $this->load->view('home/home');
        $this->LoadViewFooter('home/home_js');
    }
    
    public function get_all_finance_summaries()
    {
        $finance_summary = new finance_summary();
        $summaries = $finance_summary->get_all_finance_summaries();
        
        echo json_encode($summaries);
    }
}

