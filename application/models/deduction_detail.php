<?php

class deduction_detail extends MY_Model
{
    const DB_TABLE = 'deduction_details';
    const DB_TABLE_PK = 'deduction_id';
    
    public $deduction_id;
    public $amount;
    public $description;
    public $collection_id;
    public $date_recorded;
    public $date_recorded_iso;
    public $recorded_by_user_id;
    public $recorded_by;
    
    public function get_by_collection_id($id)
    {
        $array = array('collection_id'=>$id);
    	$query = $this->db->get_where($this::DB_TABLE, $array);

    	return $query->result();
    }    
}