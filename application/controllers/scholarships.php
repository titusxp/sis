<?php



class scholarships extends MY_Controller
{    
    protected $permission = 16;
    
    public function index() 
    {
        $academic_year = get_current_academic_year();
        redirect(site_url('scholarships/show_scholarships/' . get_encrypted_format($academic_year)));
    }
    
    public function show_scholarships($encrypted_academic_year)
    {
        $post = $this->input->post();
        
        $academic_year = isset($post['academic_year']) 
                                        ? $post['academic_year']
                                        : get_decrypted_format($encrypted_academic_year);
        
        $scholarship_instance = new Scholarship();        
        $all_scholarships = $scholarship_instance->get_scholarships($academic_year);
        $data = array('all_scholarships'=> $all_scholarships);
        
        //get academic years
        //get all academic years        
        $academic_year_instance = new Academic_year();        
        $data['all_academic_years'] = $academic_year_instance->load_all_academic_years();
        
        $data['selected_academic_year'] = $academic_year;
        
        $this->LoadViewHeader();
        $this->load->view('scholarships/all_scholarships_view', $data);
        $this->LoadViewFooter();
    }
    
    public function add_scholarship($enrollment_id)
    {
        $enrollment = new enrollment();
        $enrollment->load($enrollment_id);
        
        $scholarship = new Scholarship();
        $scholarship->enrollment_id = $enrollment->enrollment_id;
        
        $data = array(
            'scholarship'=>  $scholarship,
            'enrollment' => $enrollment
                );
              
        $this->form_validation->set_rules(array(
            array(
                'field' => 'amount',
                'label' => get_resource(res_amount),
                'rules' => 'required|is_numeric'
            ),
        ));
        
        
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');
        
        if(!$this->form_validation->run())  
        {
            $this->LoadViewHeader();
            $this->load->view('scholarships/add_edit_scholarship', $data);
            $this->LoadViewFooter();
        }        
        else
        {
            $scholarship->amount = $this->input->post('amount');
            $scholarship->date_recorded = get_current_date_mysql_format();
            $scholarship->recorded_by_user_id = current_user()->user_id;
            $scholarship->description = $this->input->post('description');
            $scholarship->save();
            
            redirect(site_url('student_courses/show_student_course_list/'
                    . $enrollment->student_id . '/'
                    . $enrollment->class_id . '/'
                    . get_encrypted_format($enrollment->academic_year)));
        }        
    }
    
    
    public function view_edit_scholarship($enrollment_id)
    {   
        
        $scholarship = new Scholarship();
        $scholarship->load_from_enrollment_id($enrollment_id);
        
        $enrollment = $scholarship->get_enrollment();
        
        $data = array(
            'scholarship'=>  $scholarship,
            'enrollment' => $enrollment
                );
        
        
        $this->form_validation->set_rules(array(
            array(
                'field' => 'amount',
                'label' => get_resource(res_amount),
                'rules' => 'required|is_numeric'
            ),
        ));
        
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');
        
        if(!$this->form_validation->run())  
        {
            $this->LoadViewHeader();
            $this->load->view('scholarships/add_edit_scholarship', $data);
            $this->LoadViewFooter();
        }        
        else
        {
            $scholarship->amount = $this->input->post('amount');
            $scholarship->date_recorded = get_current_date_mysql_format();
            $scholarship->recorded_by_user_id = current_user()->user_id;            
            $scholarship->description = $this->input->post('description');
            $scholarship->save();
            
            redirect(site_url('student_courses/show_student_course_list/'
                    . $enrollment->student_id . '/'
                    . $enrollment->class_id . '/'
                    . get_encrypted_format($enrollment->academic_year)));
        }        
    }
    
    public function delete_scholarship($scholarship_id)
    {
        $post = $this->input->post();
        
        if(isset($post['yes']))
        {
            $scholarship = new Scholarship();
            $scholarship->load($scholarship_id);
            $scholarship->delete();
            redirect(site_url('scholarships/show_scholarships'));
        }
        
        if(isset($post['no']))
        {
            redirect(site_url('scholarships/show_scholarships'));
        }
        
        $this->LoadViewHeader();
        $this->load->view('yes_no_message_box');
        $this->LoadViewFooter();
    }
}
      