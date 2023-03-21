<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authentication extends CI_Controller {
    
    protected $is_free = true;
    
    protected $access_permission = 0;
    

    public function index(){  
           /*
         * Check if user already has a session, if yes
         * redirect to the home page
         */
      
        if($this->session->userdata('user_id'))
        {
            redirect(site_url('home'));
        }  
        else
        {
            redirect(site_url('authentication/login'));
        }
    }
    
    /*
     * This function is our dedicated login handler.
     */
    public function login(){        
        $this->form_validation->set_rules(array(
            array(
                'field' => 'username',
                'label' => get_resource(res_user_name),
                'rules' => 'required'            ),
            array(
                'field' => 'password',
                'label' => get_resource(res_password),
                'rules' => 'required'
            )
        ));
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');
        
        if(!$this->form_validation->run())
        {  
            $this->load->view('header');
            $this->load->view("login");
            $this->load->view('footer');
        } 
        else{             
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            
            $user= new User();
            $user->get_user($username, $password);
            
            if($user->user_id < 1)
            {   
                $data = array('error'=>'<div class="error_label">'. get_resource(res_username_or_password_incorrect) .' </div>');
                
                $this->load->view('header');
                $this->load->view("login", $data);
                $this->load->view('footer');
            }
            else{
                $this->session->set_userdata('user_id', $user->user_id);
                $this->session->set_userdata('user_language', $user->language);
                
                $user->set_language_cookie(); 
            
                
                redirect('home');
            }
        }
    }
    
    /*
     * Our logout handler
     */
    public function logout(){
        $this->session->sess_destroy();
        redirect(site_url('authentication/login'));
    }
    
    public function change_current_user_language($language)
    {
        $user = current_user();
        $user->language = $language;
        
        $user->save();
        
        redirect(site_url('home'));
    }
}