<?php

class deductions extends MY_Controller{
    
    
    public function json_get_by_collection_id($id)
    {
    	$ded = new deduction();

    	$deds = $ded->get_by_collection_id($id);

    	echo json_encode($deds);
    }
    
    
    public function json_get_detailed_by_collection_id($id)
    {
    	$ded = new deduction_detail();

    	$ded_details = $ded->get_by_collection_id($id);

    	echo json_encode($ded_details);
    }
}