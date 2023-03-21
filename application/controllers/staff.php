<?php

class Staff extends MY_Controller
{
    
    protected $permission = 256;

    public function index() 
    {
        $this->LoadViewHeader();
        $this->load->view('staff/staff_view');
        $this->LoadViewFooter('staff/staff_view_js');
    }
    
    public function json_get_all_staff()
    {
        $staffInstance = new staff_member();
        $allStaff = $staffInstance->get_all_staff();
        echo json_encode($allStaff);
    }
    
    public function json_get_staff($staff_id)
    {
        $staff = new staff_member();
        $staff->load($staff_id);
        echo json_encode($staff);
    }
    
    public function json_get_staff_by_keyword($encrypted_keyword = "")
    {
        $key = get_keyword_encryption_key();
        
        $keyword = str_replace($key, ' ', $encrypted_keyword);
        
        $staff = new staff_member();
        
        echo json_encode($staff->search_by_keyword($keyword));
    }
    
    public function json_save_staff()
    {
        $data = json_decode(file_get_contents("php://input"));
        
        $staff = new staff_member();
        $staff->staff_id = $data->staff_id;
        $staff->salary = $data->salary;
        $staff->staff_name = $data->staff_name;
        $staff->email = $data->email;
        $staff->gender = $data->gender;
        $staff->staff_role = $data->staff_role;
        
        $staff->save();
        
        echo json_encode($staff);
    }

    public function json_get_payroll($encrypted_academic_year)
    {
        $academic_year = get_decrypted_format($encrypted_academic_year);
        $staff = new staff_member();
        $payroll = $staff->get_payroll($academic_year);
        echo json_encode($payroll);
    }
    
    public function get_staff_salaries($staff_id, $encrypted_academic_year)
    {
        $academic_year = get_decrypted_format($encrypted_academic_year);
        $cl = new collection_detail();
        $payroll = $cl->get_staff_salaries($staff_id, $academic_year);
                
        echo json_encode($payroll);
    }
    
    public function json_new_salary_payment($encrypted_academic_year, $staff_id, $amount_paid, $month_index)
    {
        $academic_year = get_decrypted_format($encrypted_academic_year);
        
        $new_collection = new collection();
        $new_collection->academic_year = $academic_year;
        $new_collection->staff_id = $staff_id;
        $new_collection->amount_due = $amount_paid;
        $new_collection->salary_month_index = $month_index;
        $new_collection->type_id = get_system_setting(sys_Salaries_Collection_Id);
        $new_collection->notes = "";
        $new_collection->save();
        
        $new_transaction = new transaction();
        $new_transaction->collection_id = $new_collection->collection_id;
        $new_transaction->amount = $amount_paid;
        $new_transaction->collection_type_id = $new_collection->type_id;
        $new_transaction->is_input = 0;
        $new_transaction->date_recorded = get_current_date_mysql_format();
        $new_transaction->recorded_by_user_id = current_user()->user_id;
        $new_transaction->save();
        
        echo json_encode($new_collection);
    }
}
