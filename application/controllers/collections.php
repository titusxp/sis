<?php

class collections extends MY_Controller{
    
    protected $permission = 1;
    
    public function index()
    {
        $this->school_fees();
    }
    
    public function school_fees()
    {
       $this->LoadViewHeader();
       $this->load->view('enrollments/enrollments_view');       
       $this->LoadViewFooter('enrollments/enrollments_view_js');
    }
    
    
    public function load_collections()
    {
       $this->LoadViewHeader();
       $this->load->view('collections/collections_view');       
       $this->LoadViewFooter('collections/collections_view_js');
    }
    
    
    
    public function view_enrollment($id)
    {
        $col = new collection_detail();
        $col->load($id);
        
       $years = new Academic_year();
       $all_academic_years = $years->load_all_academic_years();
       
       //get all classes
        $all_classes = array(); 
        $class_instance = new Classs();
        foreach($class_instance->get_all_classes_ordered_by_section() as $class){
            $all_classes[$class->class_id] = $class->get_full_class_name();
        }
        
       
       $data = array(
           "all_academic_years"=> $all_academic_years,
           "all_classes"=>$all_classes,
           "collection"=>$col
       );
                
        $this->LoadViewHeader();
        $this->load->view("enrollments/view_enrollment", $data);
        $this->LoadViewFooter();
    }
    
    public function json_get_enrolment_collections($class_id = 0, $academic_year = 0)
    {
        
        if ($class_id == 0)
        {
            $class_id = null;
        }
        if ($academic_year == 0)
        {
            $academic_year = null;
        }
        else
        {
            $academic_year = get_decrypted_format($academic_year);
        }

        $col_detail = new collection_detail();
        $collections = $col_detail->get_enrollment_collections($class_id, $academic_year);
        
        echo json_encode($collections);
    }
    
    
    
    public function json_get_collections($class_id, $encrypted_academic_year, $type_id)
    {
        $academic_year = get_decrypted_format($encrypted_academic_year);
        $col_detail = new collection_detail();
        $collections = $col_detail->get_collections($class_id, $academic_year, $type_id);
        
        echo json_encode($collections);
    }
    
    public function json_get_collection_detail_by_id($collection_id)
    {
        $col_detail = new collection_detail();
        $col_detail->load($collection_id);
        
        $this->json_return_collection($col_detail);
    }
    
    
    
    /*
     * Star function - don't forget
     */
    public function json_return_collection($collection)
    {
        $array = (array) $collection;
        
        $tr = new transaction_detail();
        $array['fees'] = $tr->get_by_collection_id($collection->collection_id);
         
        $ded = new deduction_detail();
        $array['AllDeductions'] = $ded->get_by_collection_id($collection->collection_id);
        
        $cl = new Classs();
        $array['nextClass'] = $cl->get_next_class($collection->class_id);
        
        $year = new Academic_year();        
        $array['nextAcademicYear'] = $year->load_next_academic_year();
        
        echo json_encode($array);
    }

    
    public function json_create_new_collection($student_id, $class_id, $encrypted_academic_year, $type_id = 0)
    {
        $col = new collection_detail();
        $col->collection_id = 0;
        $col->student_id = $student_id;
        $col->class_id = $class_id;
        $col->academic_year = get_decrypted_format($encrypted_academic_year);
        $col->type_id = $type_id > 0? $type_id : get_enrollment_collection_type_id();
        
        if($col->exists())
        {
            echo "";
            return;
        }
        
        $class = new classs();
        $class->load($class_id);
        
        $stu = new Student();
        $stu->load($student_id);
        
        if($type_id > 0)
        {
            $ct = new collection_type();
            $ct->load($type_id);
            $col->amount_due = $ct->get_cost($class->class_id);
        }
        else
        {
            $col->amount_due = $class->class_fees; 
        }
               
        $col->class_name = $class->class_name;
        $col->student_name = $stu->student_name;
        $col->student_number = $stu->student_number;
        $col->type_id = $type_id > 0? $type_id : get_enrollment_collection_type_id();
        
        
        $this->json_return_collection($col);
    }
    
    public function json_get_student_enrollments($student_id)
    {
        $col_detail = new collection_detail();
        $collections = $col_detail->get_student_enrollments($student_id);   
        echo json_encode($collections);
    }
    
    public function save_collection_json()
    {
        
        $data = json_decode(file_get_contents("php://input"));
        
        $col = new collection();
        $col->collection_id = $data->collection_id;
        $col->type_id = $data->type_id;
        $col->amount_due = $data->amount_due;
        $col->academic_year = $data->academic_year;
        $col->class_id = $data->class_id;
        $col->student_id = $data->student_id;
        $col->notes = "";
        $col->save();
        
        
        
        $tr = new transaction();        
        if (isset($data->fees))
        {
            $tr->save_transactions($data->fees, $col->collection_id);
        }
        else
        {
            $tr->save_transactions(array(), $col->collection_id);
        }

        $ded = new deduction();
        if (isset($data->AllDeductions))
        {
            $ded->save_deductions($data->AllDeductions, $col->collection_id);
        }
        else
        {
            $ded->save_deductions(array(), $col->collection_id);
        }
        
        $col_detail = new collection_detail();
        $col_detail->load($col->collection_id);
        
        echo json_encode($col_detail);
    }
    
    public function collection_exists($staff_id, $encrypted_academic_year, $month_index)
    {
        $academic_year = get_decrypted_format($encrypted_academic_year);
        $collection = new collection_detail();
        $cannotAddCollection = $collection->collection_exists($staff_id, $academic_year, $month_index);
        
        echo json_encode($cannotAddCollection);
    }
    
    public function delete_collection($id)
    {        
        $col = new collection();
        $col->safely_delete_collection($id);
    }
    
    public function get_other_transactions($encrypted_academic_year)
    {
        $year = get_decrypted_format($encrypted_academic_year);
        $trans = new other_expense_detail();
        $ret_val = $trans->get_where(array('academic_year' => $year));
        
        echo json_encode($ret_val);
    }
    
    public function save_new_transaction()
    {
        $data = json_decode(file_get_contents("php://input"));
        
        $type_id = $data->type_id;
        if($type_id == 0){
            $newtype = new collection_type();
            $newtype->cost = $data->amount;
            $newtype->type_name = $data->newOtherTransactionType;
            $newtype->is_different_cost_per_class = 0;
            $newtype->is_expense = 1;
            $newtype->is_deleted = 0;
            $newtype->is_system_type = 0;
            $newtype->is_not_student_related = 1;
            $newtype->save();
            $type_id = $newtype->type_id;
        }
        
        $col = new collection();
        $col->collection_id = 0;
        $col->type_id = $type_id;
        $col->amount_due = $data->amount;
        $col->academic_year = get_academic_year($data->date_recorded);
        $col->notes = isset($data->notes) ? $data->notes: "";
        
        $col->save();
        
        $transaction = new transaction;
        $transaction->transaction_id = 0;
        $transaction->collection_id = $col->collection_id;
        $transaction->amount = $col->amount_due;
        $transaction->collection_type_id = $col->type_id;
        $transaction->is_input = 0;
        $transaction->date_recorded = get_mysql_datetime_format($data->date_recorded);
        $transaction->recorded_by_user_id = $data->recorded_by_user_id;
        
        $transaction->save();
    }
    
    
}