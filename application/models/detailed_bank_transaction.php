<?php
class detailed_bank_transaction extends My_Model
{

    const DB_TABLE = 'detailed_bank_transactions';
    const DB_TABLE_PK = 'transaction_id';

    public $transaction_id;
    public $transaction_date;
    public $date_recorded;
    public $transaction_type;
    public $amount;
    public $recorded_by_user_id;
    public $notes;
    public $transaction_type_full;
    public $balance;
    public $recorded_by;
    public $transaction_date_formatted;
    public $date_recorded_formatted;
}