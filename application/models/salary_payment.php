<?php

class Salary_Payment  extends My_Model
{
    public $staff_id;
    
    public $staff_name;
    
    public $academic_year;
        
    public $january;
    
    public $february;
    
    public $march;
    
    public $april;
    
    public $may;
    
    public $june;
    
    public $july;
    
    public $august;
    
    public $september;
    
    public $october;
    
    public $november;
    
    public $december;
    
    public $total;
    
    public function load($academic_year, $staff_id, $staff_name = null)
    {
        $this->academic_year = $academic_year;
        $this->staff_id = $staff_id;
        
        if(isset($staff_name))
        {
            $this->staff_name = $staff_name;            
        }
        
        for($i=1; $i<=12; $i++)
        {
            $sql = "select amount_due from collections where academic_year = "
                    . "'$academic_year' and staff_id = $staff_id and salary_month_index = $i";
            $query = $this->db->query($sql);
            
            $value = $query->row();
                        
            $amount = isset($value->amount_due) ? $value->amount_due : " - ";
                                    
            $this->set_value($amount, $i);
        }
        
        $this->calculate_total();
    }
    
    
    public function calculate_total()
    {
        $this->total = $this->january + $this->february 
                + $this->march + $this->april + $this->may 
                + $this->june + $this->july + $this->august 
                + $this->september + $this->october 
                + $this->november + $this->december;
    }
    
    public function set_value($value, $index)
    {
        switch ($index)
        {
            case 1:
                $this->january = $value;
                break;
            case 2:
                $this->february = $value;
                break;
            case 3:
                $this->march = $value;
                break;
            case 4:
                $this->april = $value;
                break;
            case 5:
                $this->may = $value;
                break;
            case 6:
                $this->june = $value;
                break;
            case 7:
                $this->july = $value;
                break;
            case 8:
                $this->august = $value;
                break;
            case 9:
                $this->september = $value;
                break;
            case 10:
                $this->october = $value;
                break;
            case 11:
                $this->november = $value;
                break;
            case 12:
                $this->december = $value;
                break;
        }
    }  
}
