<?php

 class transaction extends MY_Model
 {
     
    const DB_TABLE = 'transactions';
    const DB_TABLE_PK = 'transaction_id';
    
    public $transaction_id;
    public $collection_id;
    public $amount;
    public $collection_type_id;
    public $is_input;
    public $date_recorded;
    public $recorded_by_user_id;

    function get_by_collection_id($id)
    {
    	$array = array('collection_id'=>$id);
    	$query = $this->db->get_where($this::DB_TABLE, $array);

    	return $query->result();
    }
    
    function save_transactions($transactions, $collection_id)
    {
        $sql = 'DELETE FROM transactions WHERE collection_id = ' . $collection_id;
        
        $saveableTransactions = array();
        
        if (count($transactions) > 0)
        {
            $sql .= ' AND transaction_id NOT IN (';
            
            foreach($transactions as $tr)
            {
                $transaction = new transaction;
                $transaction->transaction_id = $tr->transaction_id;
                $transaction->collection_id = $collection_id;
                $transaction->amount = $tr->amount;
                $transaction->collection_type_id = $tr->collection_type_id;
                $transaction->is_input = $tr->is_input;
                $transaction->date_recorded = $tr->transaction_id == 0 ? get_current_date_mysql_format() : get_mysql_datetime_format($tr->date_recorded);
                $transaction->recorded_by_user_id = $tr->recorded_by_user_id;
                
                $saveableTransactions[] = $transaction;

                $sql .= $transaction->transaction_id . ',';
            }
            $sql = rtrim($sql, ',');
            $sql .= ')';
        }
        $this->db->query($sql);
        
        foreach($saveableTransactions as $tr)
        {
            $tr->save();
        }
    }
 }  

