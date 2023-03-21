<?php
class bank_transaction extends My_Model
{

    const DB_TABLE = 'bank_transactions';
    const DB_TABLE_PK = 'transaction_id';

    public $transaction_id;
    public $transaction_date;
    public $date_recorded;
    public $transaction_type;
    public $amount;
    public $recorded_by_user_id;
    public $notes;
    
    public function get_account_balance(){
        mysqli_next_result($this->db->conn_id);
        $sql = "SELECT get_bank_account_balance(0) AS balance";
        $query = $this->db->query($sql);
        return $query->row()->balance;
    }
    
    
}