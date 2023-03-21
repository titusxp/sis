<?php

 class transaction_detail extends MY_Model
 {
     
    const DB_TABLE = 'transaction_details';
    const DB_TABLE_PK = 'transaction_id';
    
    public $transaction_id;
    public $collection_id;
    public $amount;
    public $collection_type_id;
    public $is_input;
    public $date_recorded;
    public $recorded_by_user_id;
    public $recorded_by;
    public $date_recorded_iso;

    function get_by_collection_id($id)
    {
    	$array = array('collection_id'=>$id);
    	$query = $this->db->get_where($this::DB_TABLE, $array);

    	return $query->result();
    }
 }  

