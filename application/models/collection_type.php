<?php

class collection_type extends MY_Model{
    const DB_TABLE = 'collection_types';
    const DB_TABLE_PK = 'type_id';
    
    public $type_id;
    
    public $type_name;
    
    public $cost;
    
    public $is_different_cost_per_class;
    
    public $is_deleted;
    
    public $is_expense;
    
    public $is_system_type;
    
    public $is_not_student_related;


    public function get_cost($class_id)
    {
        if($this->is_different_cost_per_class)
        {
            $searchArray = array('class_id'=>$class_id, 'type_id'=>$this->type_id);
            $type_cost = new collection_type_cost();
            $costs = $type_cost->get_where($searchArray);
            if(count($costs) > 0)
            {
                return $costs[0]->cost;
            }
            
        }
        return $this->cost;
    }
    
    public function get_collection_type_costs()
    {
        if($this->type_id > 0)
        {
            $sql = "SELECT * FROM collection_type_cost_details WHERE type_id = " . $this->{$this::DB_TABLE_PK};
        
            $query = $this->db->query($sql);
            return $query->result();
        }
        else
        {
            return array();
        }
    }
    
    public function get_by_type_cost($collection_type_cost_id)
    {
        $sql = "SELECT ct.* FROM collection_types ct LEFT JOIN collection_type_costs ctc ON ct.type_id = ctc.type_id
                WHERE ctc.type_cost_id = $collection_type_cost_id";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function save_type_costs($type_costs)
    {
        $sql = "DELETE FROM collection_type_costs WHERE type_id = 5 and type_cost_id not in (-3,"; //the-3 is important. can't remember why :)
        
        foreach($type_costs as $type_cost)
        {
            $sql .= $type_cost->type_cost_id . ",";
        }
        $sql = rtrim($sql, ',');
        $sql .= ')';
        $this->db->query($sql);
        
        $type_costs_to_save = array();
        foreach($type_costs as $type_cost)
        {
            $tc = new collection_type_cost();
            $tc->populate($type_cost);
            $tc->save();
            $type_costs_to_save[] = $tc;
        }
        return $type_costs_to_save;
    }
}
