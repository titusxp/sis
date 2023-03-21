<?php

class other_expense_detail extends MY_Model{
    const DB_TABLE = 'OtherExpenseDetails';
    const DB_TABLE_PK = 'transaction_id';
    
    public $transaction_id;
    
    public $collection_id;
    
    public $date_recorded;
    
    public $date_recorded_iso;
    
    public $type_name;
    
    public $amount;
    
    public $recorded_by_user_id;
    
    public $recorded_by;
    
    public $academic_year;
    
    public $notes;
    
    public $is_input;
}
