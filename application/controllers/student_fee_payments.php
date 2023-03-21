<?php

class student_fee_payments extends MY_Controller
{
    
    protected $permission = 4;
    
    public function index()
    {        
        $academic_year = get_current_academic_year();
        redirect(site_url('student_fee_payments/show_payments/' . get_encrypted_format($academic_year)));
    }
    
    public function show_payments($encrypted_academic_year)
    {
        $data = array();
        
        //if an academic year is posted, reload
        $posted_year = $this->input->post('academic_year');  
        
        
        if(!empty($posted_year))
        {
            redirect(site_url('student_fee_payments/show_payments/' . get_encrypted_format($posted_year)));
        }
        
        $academic_year = get_decrypted_format($encrypted_academic_year);
        
                
        $data['selected_academic_year'] = $academic_year;
        
        
        //get all academic years        
        $academic_year_instance = new Academic_year();        
        $data['all_academic_years'] = $academic_year_instance->load_all_academic_years();
        
        //get all payments for this academic_year
        $fee_payment_instance = new student_fee_payment();
        $data['all_payments'] = $fee_payment_instance->get_fee_payments_by_academic_year($academic_year);
        
        
        $this->LoadViewHeader();
        $this->load->view('fee_payments/student_fee_payments_view', $data);
        $this->LoadViewFooter();
    }
    
    public function add_payment()
    {  
        $data = array();
        
                    
        //get all classes
        $all_classes = array('' => '');
        $data['selected_class_id'] = '';
        
        $class_instance = new Classs();
        foreach($class_instance->get_all_classes_ordered_by_section() as $class)
        {
            $all_classes[$class->class_id] = $class->get_full_class_name();
        }
        $data['all_classes'] = $all_classes;
            
        //get all academic years
        
        $academic_year_instance = new Academic_year();        
        $data['all_academic_years'] = $academic_year_instance->load_all_academic_years();
        
        $data['selected_academic_year'] = get_current_academic_year();
        
        //get posted values
        $post = $this->input->post();
        if(isset($post['search']))
        {
            $class_id = $this->input->post('class');
            $academic_year = $this->input->post('academic_year');
            
            if(empty($class_id) || empty($academic_year))
            {
                $data['error'] = '<div class="error_label">Class and Academic year must be selected</div>';
            }
            else
            {
                //update the selected academic year
                $data['selected_academic_year'] = $academic_year;
                
                //get selected class object and pass it         
                $class_instance = new Classs();
                $class_instance->load($class_id);
                $data['selected_class_id'] = $class_instance->class_id;
                $data['selected_class'] = $class_instance;
                              
                $all_students = array('' => '');
                foreach($class_instance->get_students_by_academic_year_and_class($academic_year, $class_id) as $student)
                {
                    $all_students[$student->student_id] = $student->student_name . ' (' . $student->student_number . ')';
                }
                $data['all_students'] = $all_students;
            }
            
            $this->LoadViewHeader();
            $this->load->view('fee_payments/new_fee_payment', $data);
            $this->LoadViewFooter();
        }
        else             
        {
            $this->form_validation->set_rules(array(
                array(
                    'field' => 'student',
                    'label' => get_resource(res_student),
                    'rules' => 'required'
                ),
                array(
                    'field' => 'payer',
                    'label' => get_resource(res_paid_by),
                    'rules' => 'required'
                ),
                array(
                    'field' => 'amount_paid',
                    'label' => get_resource(res_amount_paid),
                    'rules' => 'required|is_numeric'
                )
            ));
            
            $this->form_validation->set_error_delimiters('<div class = "error_label">', '</div>');
            
            if(!$this->form_validation->run())
            {
                $this->LoadViewHeader();
                $this->load->view('fee_payments/new_fee_payment', $data);
                $this->LoadViewFooter();
            }
            else
            {
                $post = $this->input->post();
                $new_payment = new student_fee_payment();
                $new_payment->amount_paid = $this->input->post('amount_paid');
                $new_payment->payment_date = $this->input->post('payment_date');
                $new_payment->payer = $this->input->post('payer');
                                
                
                //get enrollment id
                $this->load->model('Enrollment');
                $enrollment = new Enrollment();
                $enrollment->load_enrollment($post['student'], $post['selected_class_id'], $post['selected_academic_year']);
                
                $new_payment->enrollment_id = $enrollment->enrollment_id;
                
                $new_payment->recorded_by_user_id = $this->session->userdata('user_id');
                //$new_payment->is_scholarship = $this->input->post('is_scholarship');
                
                $new_payment->payment_date = get_current_date_mysql_format();
                
                $new_payment->save();
                
                redirect(site_url('student_fee_payments/show_payments/' . 
                        get_encrypted_format($new_payment->get_academic_year())));
            }
        }
    }
    
    public function add_payment_from_enrollment($enrollment_id)
    {
        $data = array();
        
        //get enrollment object
        $enrollment = new enrollment();
        $enrollment->load($enrollment_id);
        $data['enrollment'] = $enrollment;
        
        $this->form_validation->set_rules(array(
//                array(
//                    'field' => 'payer',
//                    'label' => get_resource(res_paid_by),
//                    'rules' => 'required'
//                ),
                array(
                    'field' => 'amount_paid',
                    'label' => get_resource(res_amount_paid),
                    'rules' => 'required|is_numeric'
                )
            ));
            
            $this->form_validation->set_error_delimiters('<div class = "error_label">', '</div>');
            
            if(!$this->form_validation->run())
            {
                $this->LoadViewHeader();
                $this->load->view('fee_payments/new_direct_fee_payment', $data);
                $this->LoadViewFooter();
            }
            else
            {
                $post = $this->input->post();
                $new_payment = new student_fee_payment();
                $new_payment->amount_paid = $this->input->post('amount_paid');
                $new_payment->payment_date = $this->input->post('payment_date');
                $new_payment->payer = $this->input->post('payer');
                                
                               
                $new_payment->enrollment_id = $enrollment_id;
                
                $new_payment->recorded_by_user_id = $this->session->userdata('user_id');
                //$new_payment->is_scholarship = $this->input->post('is_scholarship');
                
                $new_payment->payment_date = get_current_date_mysql_format();
                
                $new_payment->save();
                
                redirect(site_url('student_courses/show_student_course_list_from_enrollment/' . $enrollment_id));
            }
    }
    
    public function add_payment_from_students_view($enrollment_id)
    {  
        $data = array();
        
        //get enrollment object
        $enrollment = new enrollment();
        $enrollment->load($enrollment_id);
        $data['enrollment'] = $enrollment;
        
        $this->form_validation->set_rules(array(
//                array(
//                    'field' => 'payer',
//                    'label' => get_resource(res_paid_by),
//                    'rules' => 'required'
//                ),
                array(
                    'field' => 'amount_paid',
                    'label' => get_resource(res_amount_paid),
                    'rules' => 'required|is_numeric'
                )
            ));
            
            $this->form_validation->set_error_delimiters('<div class = "error_label">', '</div>');
            
            if(!$this->form_validation->run())
            {
                $this->LoadViewHeader();
                $this->load->view('fee_payments/new_direct_fee_payment', $data);
                $this->LoadViewFooter();
            }
            else
            {
                $post = $this->input->post();
                $new_payment = new student_fee_payment();
                $new_payment->amount_paid = $this->input->post('amount_paid');
                $new_payment->payment_date = $this->input->post('payment_date');
                $new_payment->payer = $this->input->post('payer');
                                
                               
                $new_payment->enrollment_id = $enrollment_id;
                
                $new_payment->recorded_by_user_id = $this->session->userdata('user_id');
                //$new_payment->is_scholarship = $this->input->post('is_scholarship');
                
                $new_payment->payment_date = get_current_date_mysql_format();
                
                $new_payment->save();
                
                redirect(site_url('students/show_student/' . $student->student_number));
            }
        
    }
    
    public function edit_payment($payment_id)
    {  
        $data = array();
        
        $payment = new student_fee_payment();
        $payment->load($payment_id);
                    
        $data['payment'] = $payment;
        
        
        $this->form_validation->set_rules(array(
//            array(
//                'field' => 'payer',
//                'label' => 'Payer',
//                'rules' => 'required'
//            ),
            array(
                'field' => 'amount_paid',
                'label' => 'Amount Paid',
                'rules' => 'required|is_numeric'
            )
        ));

        $this->form_validation->set_error_delimiters('<div class = "error_label">', '</div>');

        if(!$this->form_validation->run())
        {
            $this->LoadViewHeader();
            $this->load->view('fee_payments/view_edit_fee_payment', $data);
            $this->LoadViewFooter();
        }
        else
        {
            
            
            $post = $this->input->post();
            
            $payment->amount_paid = $post['amount_paid'];
            $payment->payer = $post['payer'];
            //$payment->is_scholarship = $post['is_scholarship'];
            $payment->payment_date = get_current_date_mysql_format();
            $payment->recorded_by_user_id = $this->session->userdata('user_id');
            
            
            $payment->save();

            redirect(site_url('student_fee_payments/show_payments/' . 
                    get_encrypted_format($payment->get_academic_year())));
            
        }
    }
    
    
    public function delete_payment($payment_id)
    {
        $yes = $this->input->post('yes');
        $no = $this->input->post('no');
        
        if(strtoupper($no) == 'NO')
        {
            redirect(site_url('student_fee_payments/edit_payment/' . $payment_id));
            return;
        }
        
        if(strtoupper($yes) == 'YES')
        {
            //get the payment object and delete it
            $this->load->model('student_fee_payment');
            $payment =  new student_fee_payment();
            $payment->load($payment_id);
            
            $redirect_link = site_url('student_fee_payments/show_payments/' . get_encrypted_format($payment->get_academic_year()));
            
            $payment->delete();
            
            redirect($redirect_link);
            return;
        }       
        
        
        $message = get_resource(res_are_you_sure);
        $this->load->view('yes_no_message_box', array('message' => $message));
    }   
    
    
}

