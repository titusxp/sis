<?php

class deduction extends MY_Model
{
    const DB_TABLE = 'deductions';
    const DB_TABLE_PK = 'deduction_id';
    
    public $deduction_id;
    public $amount;
    public $description;
    public $collection_id;
    public $date_recorded;
    public $recorded_by_user_id;
    
    function save_deductions($deductions, $collection_id)
    {
        $sql = 'DELETE FROM deductions WHERE collection_id = ' . $collection_id;
        
        if(count($deductions))
        {
            $sql .= ' AND deduction_id NOT IN (';
            foreach($deductions as $ded)
            {
                $deduction = new deduction;
                $deduction->deduction_id = $ded->deduction_id;
                $deduction->amount = $ded->amount;
                $deduction->description = $ded->description;
                $deduction->collection_id = $ded->collection_id;
                $deduction->date_recorded = $ded->deduction_id == 0 ? get_current_date_mysql_format() : get_mysql_datetime_format($ded->date_recorded);
                $deduction->recorded_by_user_id = $ded->recorded_by_user_id;
                
                $deduction->save();

                $sql .= $deduction->deduction_id . ',';
            }
            $sql = rtrim($sql, ',');
            $sql .= ')';
        }
        $this->db->query($sql);   
    }

 }