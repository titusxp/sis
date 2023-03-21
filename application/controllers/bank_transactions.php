<?php

class bank_transactions extends MY_Controller{
    
    
    public function json_get_all()
    {
    	$tr = new detailed_bank_transaction();

    	$transactions = $tr->get();

    	echo json_encode($transactions);
    }
    
    
    public function json_save_transaction()
    {
    	$data = json_decode(file_get_contents("php://input"));
        
        $tr = new bank_transaction();
        $tr->transaction_id = 0;
        $tr->transaction_date = get_mysql_datetime_format($data->transaction_date);
        $tr->date_recorded = get_current_date_mysql_format();
        $tr->amount = $data->Amount;
        $tr->transaction_type = $data->transaction_type;
        $tr->recorded_by_user_id = current_user()->user_id;
        $tr->notes = $data->notes;
        
        $tr->save();
        
        $detailed_tr = new detailed_bank_transaction();
        $detailed_tr->load($tr->transaction_id);
        
        echo json_encode($detailed_tr);
    }
    
    public function delete($id){
        $tr = new bank_transaction();
        $tr->delete_by_id($id);
    }
    
    public function get_bank_account_balance(){
        $bk = new bank_transaction();
        echo $bk->get_account_balance();
    }
}