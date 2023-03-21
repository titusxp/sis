<?php

class salary_payments extends MY_Controller
{
    protected $permission = 1024;
    
    public function index()
    {
        redirect(site_url('salary_payments/show_payments/' . get_encrypted_format(get_current_academic_year())));
    }
    
    public function show_payments($encrypted_academic_year)
    {
        $data = array();
        
        //get academic year either from posted value or passed value
        $posted_year = $this->input->post('academic_year');
        $academic_year = !empty ($posted_year) ? $posted_year : get_decrypted_format($encrypted_academic_year);
        $data['selected_academic_year'] = $academic_year;
        
        //ok now we get all academic years
        $academic_year_instance = new Academic_year();
        $data['all_academic_years'] = $academic_year_instance->load_all_academic_years();

        
        //now get all payments by that academmic year
        $this->load->model('salary_payment');
        $payment = new salary_payment();
        $data['all_payments'] = $payment->get_payments_by_academic_year($academic_year);
        
        
        $this->LoadViewHeader();
        $this->load->view('salary_payments/salary_payments_view', $data);
        $this->LoadViewFooter();
    }
    
    public function add_edit_payment($payment_id = null)
    {
        $data = array();
        
        //get the payment object
        $payment = new Salary_Payment(); 
        if ($payment_id != null) {
            $payment->load($payment_id);
        }
        
        $data['payment'] = $payment;
        
        //ok now we get all academic years
        $academic_year_instance = new Academic_year();
        $data['all_academic_years'] = $academic_year_instance->load_all_academic_years();
        
        
        
        //oh, get all staff as well
        $staff_instance = new Staff_member();
        $all_staff = array();
        foreach($staff_instance->get_all_staff() as $staff)
        {
            $all_staff[$staff->staff_id] = $staff->staff_name . ' (' . $staff->salary . ' CFA)';
        }
        $data['all_staff'] = $all_staff;
        
        
        $this->form_validation->set_rules(array(
            array(
                    'field' => 'amount',
                    'label' => get_resource(res_amount_paid),
                    'rules' => 'is_numeric|required',
                ),
                array(
                    'field' => 'academic_year',
                    'label' => get_resource(res_academic_year),
                    'rules' => 'required',
                ),
                array(
                    'field' => 'staff',
                    'label' => get_resource(res_staff),
                    'rules' => 'required',
                ),
//                array(
//                    'field' => 'payment_date',
//                    'label' => get_resource(res_payment_date),
//                    'rules' => 'callback_is_valid_date',
//                )
        )
            );
        $this->form_validation->set_error_delimiters("<div class='error_label'>", "</div>");
        $this->form_validation->set_message('is_valid_date', get_resource(res_invalid_fields_validation));
        
        if(!$this->form_validation->run())
        {
            $this->LoadViewHeader();
            $this->load->view('salary_payments/add_edit_salary_payment_view', $data);
            $this->LoadViewFooter();
        }
        else
        {
            $payment = new Salary_Payment();
            
            //get the payment id only if it's an edit case
            $payment_id = $this->input->post('payment_id');
            if($payment_id > 0){
                $payment->payment_id = $payment_id;
            }
            
            $payment->staff_id = $this->input->post('staff');
            $payment->amount = $this->input->post('amount');
            //$payment->payment_date = get_mysql_date_format($this->input->post('payment_date'));
            $payment->purpose = $this->input->post('purpose');
            $payment->academic_year = $this->input->post('academic_year');
            
            $payment->payment_date = get_current_date_mysql_format();
            
            $payment->save();
            
            redirect(
                    site_url('salary_payments/show_payments/' . 
                            get_encrypted_format($payment->academic_year)));
        }        
    }
    
    function is_valid_date($date){
        $date_info = date_parse($date);
        if($date_info['warning_count'] == 0 && $date_info['error_count'] == 0)
        {
            return true;
        }
        return false;
    }
    
    public function delete_payment($payment_id)
    {
        $post = $this->input->post();
        
        if($post['no'])
        {
            redirect(site_url('salary_payments/add_edit_payment/' . $payment_id));
            return;
        }
        
        if($post['yes'])
        {
            //get the payment object and delete it
            $this->load->model('Salary_Payment');
            $payment =  new Salary_Payment();
            $payment->load($payment_id);
            
            $redirect_link = site_url('salary_payments/show_payments/' . get_encrypted_format($payment->academic_year));
            
            $payment->delete();
            
            redirect($redirect_link);
            return;
        }       
        
        
        $message = get_resource(res_are_you_sure);
        $this->load->view('yes_no_message_box', array('message' => $message));
    }
}
?>
