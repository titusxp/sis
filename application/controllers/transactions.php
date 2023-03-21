<?php

class transactions extends MY_Controller{
    
    
    public function json_get_by_collection_id($id)
    {
    	$tr = new transaction();

    	$transactions = $tr->get_by_collection_id($id);

    	echo json_encode($transactions);
    }
    
    
    public function json_get_detailed_by_collection_id($id)
    {
    	$tr = new transaction_detail();

    	$tr_details = $tr->get_by_collection_id($id);

    	echo json_encode($tr_details);
    }
}