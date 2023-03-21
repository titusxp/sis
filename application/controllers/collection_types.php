<?php

class collection_types extends MY_Controller{
    
    protected $permission = 1;
    
    public function index()
    {        
        
    }
    
    public function delete_collection_type($id)
    {
        $ct = new collection_type();
        $ct->load($id);
        if($ct->type_id > 0)
        {
            $ct->is_deleted = 1;
            $ct->save();
        }
    }
    
    public function json_get_all($isExpense = null)
    {
        $col_type = new collection_type();
        
        $searchArray = array();
        $searchArray['is_deleted'] = 0;
        
        if($isExpense != null)
        {
            $searchArray['is_expense'] = $isExpense;
        }
        
        $col_types = $col_type->get_where($searchArray);
        
        echo json_encode($col_types);
    }
    
    public function json_get_non_system_types($is_expense, $not_student_related = 0)
    {
        $col_type = new collection_type();
        
        $searchArray = array();
        $searchArray['is_deleted'] = 0;
        $searchArray['is_system_type'] = 0;
        $searchArray['is_expense'] = $is_expense;
        $searchArray['is_not_student_related'] = $not_student_related;
        
        //show_array($searchArray);
        
        $col_types = $col_type->get_where($searchArray);
        
        echo json_encode($col_types);
    }
    
    public function json_get_non_student_types()
    {
        $col_type = new collection_type();
        
        $searchArray = array();
        $searchArray['is_deleted'] = 0;
        $searchArray['is_system_type'] = 0;
        $searchArray['is_not_student_related'] = 1;
        
        $col_types = $col_type->get_where($searchArray);
        
        $type_names = array();
        foreach($col_types as $row){
            $type_names[] = $row->type_name;
        }
        
        array_multisort($type_names, SORT_ASC, $col_types);
        
        //show_array($col_types);
        echo json_encode($col_types);
    }
    
    public function json_get_collection_type($id)
    {
        $col_type = new collection_type();
        $col_type->load($id);
        
        $array = (array)$col_type;
        
        if($id > 0)
        {
            $array['collection_type_costs'] = $col_type->get_collection_type_costs();
        }
        else
        {
            $array['collection_type_costs'] = array();
        }
        
        echo json_encode($array);
    }
   
//    public function json_delete_collection_type_cost($id)
//    {
//        $col_type = new collection_type();
//        $col_type->get_by_type_cost($id);
//        
//        $is_expense = $col_type->is_expense;
//                
//        $col_type_cost = new collection_type_cost();
//        $col_type_cost->load($id);
//        
//        $col_type_cost->delete();
//        
//        $this->json_get_collection_type($is_expense);
//    }
//    
//    public function json_create_new_collection_type($id, $class_id, $cost)
//    {
//        $col_type_cost = new collection_type_cost();
//        $col_type_cost->type_id = $id;
//        $col_type_cost->class_id = $class_id;
//        $col_type_cost->cost = $cost;
//        $col_type_cost->save();
//        
//        $col_type = new collection_type();
//        $col_type->get_by_type_cost($id);
//        
//        $is_expense = $col_type->is_expense;
//        $this->json_get_collection_type($is_expense);
//    }
    
    public function json_save_collection_type()
    {
        $data = json_decode(file_get_contents("php://input"));
                
        $ct = new collection_type();
        $ct->type_id = $data->type_id;
        $ct->type_name = $data->type_name;
        $ct->cost = empty($data->cost)? 0: $data->cost;
        $ct->is_different_cost_per_class = $data->is_different_cost_per_class;
        $ct->is_expense = $data->is_expense;
        $ct->is_deleted = $data->is_deleted;
        $ct->is_not_student_related = $data->is_not_student_related;
        $ct->is_system_type = 0;
        
        if(!$ct->type_id > 0)
        {
            $ct->type_id = 0;
        }
        if(!$ct->is_expense > 0)
        {
            $ct->is_expense = 0;
        }
        if(!$ct->is_deleted > 0)
        {
            $ct->is_deleted = 0;
        }
        
        $ct->save();
        $ctc = $ct->save_type_costs($data->collection_type_costs);
       
        show_array($ct);
        show_array($ctc);
        
    }
}