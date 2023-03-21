<?php

class finance_summary extends MY_Model{
    const DB_TABLE = 'finance_summary';
    const DB_TABLE_PK = 'type_id';
    
    public $type_id;
    
    public $type_name;
    
    public $academic_year;
    
    public $amount;
    
    public $is_expense;
    
    public function amount_polarity()
    {
        return $this->is_expense < 1 ? $this->amount : $this->amount * -1;
    }
    
    public function get_finance_summary($academic_year)
    {
        mysqli_next_result($this->db->conn_id);
        $sql = "CALL get_finance_summary('$academic_year');";
        //echo $sql;
        $query = $this->db->query($sql);        
        $summaries = array();        
        foreach($query->result() as $row)
        {
            $summaries[$row->type_id] = $row;
        }
        

        
        return $summaries;
    }
    
    public function get_all_finance_summaries()
    {
        $academic_year = new Academic_year();
        $years = $academic_year->get_all_academic_years();
        $finance_summaries = array();
        
        foreach($years as $year)
        {
            $total_expense = 0;
            $total_income = 0;
            
            $summary = $this->get_finance_summary($year);
            foreach($summary as $item)
            {
                $total_expense += $item->is_expense ? $item->amount : 0;
                $total_income += $item->is_expense ? 0 : $item->amount;
            }
            
            $summary['total_expense'] = $total_expense;
            $summary['total_income'] = $total_income;
            $summary['academic_year'] = $year;
            
            $finance_summaries[$year] = $summary;
        }
        return $finance_summaries;
    }
}
