<?php



class global_json_repo extends MY_Controller
{    
    protected $permission = 64;
    
    
    //gets  all classes from the database     
    public function get_all_classes($addEmpty = null)
    {
               //get all classes
        $all_classes = array(); 
        
        $class_instance = new Classs();
        
        
       if($addEmpty != null)
       {
            $class_instance->class_id = 0;
            $class_instance->class_name = "all classes";
            $all_classes[] = $class_instance;
       }
        
        foreach($class_instance->get_all_classes_ordered_by_section() as $class)
        {
            $all_classes[] = $class;
        }
        
        echo json_encode($all_classes);
    }
    
    
    public function get_all_academic_years($addEmpty = null)
    {
       $years = new Academic_year();
       
       $all_academic_years = array();
       
       if($addEmpty != null)
       {
           $all_academic_years[0]['year_value'] = 0;
           $all_academic_years[0]['year_name'] = 'All Academic Years';
       }
       
       foreach($years->load_all_academic_years() as $y)
       {
           $all_academic_years[$y]['year_value'] = $y;
           $all_academic_years[$y]['year_name'] = $y;
       }
       
       echo json_encode($all_academic_years);
    }
    
    public function get_all_months()
    {
        $months = array();
        
        for($i = 1; $i <= 12; $i++)
        {
            
            $months[$i]['index'] = $i;
            switch ($i)
            {
                case 1:
                    $months[$i]['month'] = 'January';
                    break;
                case 2:
                    $months[$i]['month'] = 'February';
                    break;
                case 3:
                    $months[$i]['month'] = 'March';
                    break;
                case 4:
                    $months[$i]['month'] = 'April';
                    break;
                case 5:
                    $months[$i]['month'] = 'May';
                    break;
                case 6:
                    $months[$i]['month'] = 'June';
                    break;
                case 7:
                    $months[$i]['month'] = 'July';
                    break;
                case 8:
                    $months[$i]['month'] = 'August';
                    break;
                case 9:
                    $months[$i]['month'] = 'September';
                    break;
                case 10:
                    $months[$i]['month'] = 'October';
                    break;
                case 11:
                    $months[$i]['month'] = 'November';
                    break;
                case 12:
                    $months[$i]['month'] = 'December';
                    break;
            }
        }
        echo json_encode($months);
    }
}