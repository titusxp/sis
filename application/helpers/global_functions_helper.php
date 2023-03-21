<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('get_school_info'))
{
    function get_school_info()
    {
        $school_info = new model_school_info();
        $school_info->load_school_info();
        return $school_info;
    }
}

if(!function_exists('get_gender'))
{
    function get_gender($gender)
    {
        if(strtoupper($gender) == 'MALE')
        {
            return get_resource (res_male);
        }
        if(strtoupper($gender) == 'FEMALE')
        {
            return get_resource (res_female);
        }
        return '';
    }
}

/*
 * formats a date for display
 */
if(!function_exists('format_date'))
{
    function format_date($date)
    {
        $format = '';
        switch (get_current_user_language())
        {
            case "en-GB":
                $format = 'd/m/Y - h:i A';
                break;
            case "fr-FR";
                $format = 'd/m/Y - H:m';
                break;                
        }
        $phpdate = strtotime( $date );
        $formated_date = date( $format, $phpdate );
        return $formated_date;
    }
}


/*
 * formats a date of birth for display
 */
if(!function_exists('format_date_of_birth'))
{
    function format_date_of_birth($date)
    {
        if($date == null)
            return get_resource(res_Unavailable);
        $phpdate = strtotime( $date );
        $formated_date = date( 'd/m/Y', $phpdate );
        return $formated_date;
    }
}


if(!function_exists('get_all_permissions'))
{
    function get_all_permissions()
    {
        $permission = new permission();
        return $permission->get();
    }
}


if(!function_exists('get_all_months'))
{
    function get_all_months()
    {
        return array(
            1 =>  get_resource(res_january),
            2 =>get_resource(res_february),
            3 =>get_resource(res_march),
            4 =>get_resource(res_april),
            5 =>get_resource(res_may),
            6 =>get_resource(res_june),
            7 =>get_resource(res_july),
            8 =>get_resource(res_august),
            9 =>get_resource(res_september),
            10 =>get_resource(res_october),
            11 =>get_resource(res_november),
            12 =>get_resource(res_december),
        );
    }
}



if(!function_exists('global_is_single_class_index'))
{
    function global_is_single_class_index($class_id, $class_section, $class_index)
    {
        $class = new Classs();
        return !$class->is_single_class_index($class_id, $class_section, $class_index);
    }
}

if(!function_exists('global_is_unique_class_index'))
{
    function global_is_unique_class_index($class_section, $class_index)
    {
        $class = new Classs();
        return !$class->class_index_exists($class_section, $class_index);
    }
}









if(!function_exists('is_unique_class_name'))
{
    function is_unique_class_name($class_name)
    {
        $class = new Classs();
        return !$class->exists($class_name);
    }
}


if(!function_exists('is_single_class_name'))
{
    function is_single_class_name($class_id, $class_name)
    {
        $class = new Classs();
        return !$class->is_single_class_name($class_id, $class_name);
    }
}
 

if(!function_exists('course_exists'))
{
    function course_exists($course_code, $course_name, $class_id)
    {
        $course = new Course();
        return $course->course_exists($course_code, $course_name, $class_id);
    }
}

if(!function_exists('echo_wait_form'))
{
    function echo_wait_form($message = null)
    {
        if($message == null)
        {
            $message = "Loading";
        }
        
        echo '<span ng-show="showWaitForm"><img src="'.
                base_url("stuff/images/loading.gif") .
                '" / style="width:20px">  '. $message . '{{waitFormMessage}}</span>';
    }
}

if(!function_exists('get_all_classes')){
    function get_all_classes()
    {
        $class = new Classs();
        return $class->GetAllClassesOrderedBySection();
    }
}

if(!function_exists('get_first_class_id'))
{
    function get_first_class_id()
    {
        $class = new Classs();
        return $class->get_first_class_id();
    }
}


if(!function_exists('current_user'))
{
    function current_user()
    {        
        $user = new User();
        return $user->get_logged_in_user();
    }
}


if(!function_exists('current_user_permissions'))
{
    function current_user_permissions()
    {        
        return current_user()->get_permissions();
    }
}



if(!function_exists('current_user_has_permission'))
{
    function current_user_has_permission($permission_level)
    {
        if(current_user()->is_admin)
        {
            return true;
        }
        return array_key_exists($permission_level, current_user_permissions());
    }
}



if(!function_exists('cast'))
{
    /**
 * Class casting
 *
 * @param object $sourceObject
 * @param string|object $destination
 * @return object
 */
function cast($sourceObject, $destination)
{
    if (is_string($destination)) {
        $destination = new $destination();
    }
    $sourceReflection = new ReflectionObject($sourceObject);
    $destinationReflection = new ReflectionObject($destination);
    $sourceProperties = $sourceReflection->getProperties();
    foreach ($sourceProperties as $sourceProperty) {
        $sourceProperty->setAccessible(true);
        $name = $sourceProperty->getName();
        $value = $sourceProperty->getValue($sourceObject);
        if ($destinationReflection->hasProperty($name)) {
            $propDest = $destinationReflection->getProperty($name);
            $propDest->setAccessible(true);
            $propDest->setValue($destination,$value);
        } else {
            $destination->$name = $value;
        }
    }
    return $destination;
}
}

if(!function_exists('show_array'))
{
    function show_array($array)
    {
        echo '<pre>';
        echo print_r($array);
        echo '</pre>';
    }
}

    
    if(!function_exists('show_variable'))
    {
        function show_variable($variable)
        {
            var_dump($variable);
        }
    }
    
    
    
if (!function_exists('get_encrypted_format'))
{
    function get_encrypted_format($academic_year) {
        return str_replace('/', 'and', $academic_year);
    }
}

/*
 * Get the url to the page where we add a new student
 */
if (!function_exists('get_add_edit_student_link'))
{

    function get_add_edit_student_link() {
        return site_url('students/add_edit_student');
    }

}

if(!function_exists('get_academic_year'))
{
    function get_academic_year($date){
        $date_info = date_parse($date);
        $current_year = $date_info['year'];
        $current_month = $date_info['month'];
        return generate_academic_year($current_year, $current_month);
    }
}

if(!function_exists('generate_academic_year'))
{
    function generate_academic_year($current_year, $current_month){
        if($current_month > 7) //July
        {
            $year1 = $current_year;
            $year2 = $current_year + 1;
        }
        else{
            $year1 = $current_year - 1;
            $year2 = $current_year;
        }
        return $year1 . '/' . $year2;
    }
}
if(!function_exists('get_current_academic_year'))
{
    function get_current_academic_year(){
        $current_year = strftime("%Y", time());
        $current_month = strftime("%m", time());
        return generate_academic_year($current_year, $current_month);
    }
}


if(!function_exists('get_mysql_date_format'))
{
    function get_mysql_date_format($date)
    {
        $date_info = date_parse($date);
        return $date_info['year'] . '-' . 
                $date_info['month'] . '-' . 
                $date_info['day'];
    }
}


if(!function_exists('get_mysql_datetime_format'))
{
    function get_mysql_datetime_format($date)
    {
        $date_info = date_parse($date);
        return $date_info['year'] . '-' . 
                $date_info['month'] . '-' . 
                $date_info['day'] . ' ' .
                $date_info['hour']. ':' .
                $date_info['minute'] . ':'.
                $date_info['second'] ;
    }
}



if (!function_exists('is_valid_date'))
{
    function is_valid_date($date){
        $date_info = date_parse($date);
        if($date_info['warning_count'] == 0 && $date_info['error_count'] == 0)
        {
            return true;
        }
        return false;
    }
}



if (!function_exists('get_next_academic_year'))
{
    function get_next_academic_year($academic_year){
        $current_year = substr($academic_year, 0, 4);       
        $year1 = $current_year + 1;
        $year2 = $current_year + 2;
        return $year1 . '/' . $year2;    
    }
}

if (!function_exists('get_year'))
{
    /*
     * Receives an academic year and returns the first or second year
     * in the passed academic year, depending on the $year_number field passed
     */
    function get_year($academic_year, $year_number = 1)
    {  
        if($year_number == 1)
        {
            return substr($academic_year, 0, 4); 
        }
        if($year_number == 2)
        {
            return substr($academic_year, 5, 4); 
        }
    }
}
     

if (!function_exists('get_keyword_encryption_key'))
{
    function get_keyword_encryption_key() {
        return "oops7510";
    }
}
if (!function_exists('get_decrypted_format'))
{
    function get_decrypted_format($academic_year) {
        return str_replace('and', '/', $academic_year);
    }
}

if (!function_exists('get_all_students_link'))
{

    function get_all_students_link() {
        return site_url('students');
    }

}

if (!function_exists('GetAllCoursesLink'))
{

    function GetAllCoursesLink() {
        return site_url('courses');
    }

}

if (!function_exists('GetNewCourseLink'))
{

    function GetNewCourseLink($class_id = 0) 
    {
        return site_url('courses/new_course/' . $class_id);
    
    }

}



if(!function_exists('get_yes_no'))
{
    function get_yes_no($bit)
    {
        return $bit? get_resource(res_yes) : get_resource(res_no);
    }
}

if(!function_exists('get_true_false'))
{
    function get_true_false($statement)
    {
        if (strtolower($statement) == 'true')
        {
            return 'true';//get_resource(res_true);
        }
        if (strtolower($statement) == 'false')
        {
            return 'false'; //get_resource(res_false);
        }
    }
}
    

if(!function_exists('get_current_date_mysql_format'))
{
    function get_current_date_mysql_format($time_zone_index = 1)
    { 
        $year = gmdate("Y");
        $month = gmdate("m");
        $day = gmdate("d");
        $hour = gmdate("G") + $time_zone_index;
        $minute = gmdate("i");
        $second = gmdate("s");
        return $year . '-' . 
               $month . '-' . 
               $day . ' ' .
               $hour . ':' . 
               $minute . ':' . 
               $second;
        
    }    
}

if(!function_exists('get_mysql_date_format_from_time_stamp'))
{
    function get_mysql_date_format_from_time_stamp($time_stamp)
    {
        $year = strftime('%Y',$time_stamp);
        $month = strftime('%m', $time_stamp);
        $day = strftime('%d', $time_stamp);
        $hour = strftime('%H', $time_stamp);
        $minute = strftime('%M', $time_stamp);
        $second = strftime('%S', $time_stamp);
        return $year . '-' . 
               $month . '-' . 
               $day . ' ' .
               $hour . ':' . 
               $minute . ':' . 
               $second;
    }
}
if(!function_exists('get_current_date_plain'))
{
    function get_current_date_plain()
    {
        $time_stamp = now();
        $year = strftime('%Y',$time_stamp);
        $month = strftime('%B', $time_stamp);
        $day = strftime('%d', $time_stamp);
        return $day . ' ' . 
               $month . ' ' . 
               $year;
    }
}


if(!function_exists('get_language'))
{
    function get_language($language)
    {
        if(strtoupper($language) == 'ENGLISH')
        {
            return get_resource(res_english);
        }
        if(strtoupper($language) == 'FRENCH')
        {
            return get_resource(res_french);
        }
        
        return '';
    }
}

if(!function_exists('get_languages_array'))
{
    function get_languages_array()
    {
        return array(
            'English' => get_resource(res_english), 
            'French' => get_resource(res_french));
    }
}


if(!function_exists('get_gender'))
{
    function get_gender($gender)
    {
        switch (strtoupper($gender))
        {
            case 'MALE':
                return get_resource(res_male);
                
            case 'FEMALE':
                return get_resource(res_female);
                
            default :
                return '';
        }
    }
}

if(!function_exists('get_CFA_format'))
{
    function get_CFA_format($integer)
    {
        return number_format($integer) . ' CFA';
    }
}

if(!function_exists('get_table_template'))
{
    function get_table_template()
    {
        return array(
                    'table_open'=> '<table class="table table-hover table-bordered table-condensed"'
                );
    }
}

if(!function_exists('get_label_attribute'))
{
    function get_label_attribute()
    {
        return array('class'=> 'col-sm2 control-label');
    }
}


if(!function_exists('get_resource'))
{
    function get_resource($resource_name)
    {
        if(current_user()->user_id)
        {
            return get_resource_with_language($resource_name, current_user()->language);
        }
        
        $language = get_current_user_language();
        
        return get_resource_with_language($resource_name, $language);
    }
}


if(!function_exists('get_current_user_language'))
{
    function get_current_user_language()
    {
        $user = current_user();
        if($user->user_id)
        {
            return current_user()->language;
        }
        
        return $user->get_cookie_language();
    }
}

if(!function_exists('get_system_setting'))
{
    function get_system_setting ($setting_name)
    {
        $setting = new setting();
        
        return $setting->get_setting_value($setting_name);
    }
}


if(!function_exists('get_resource_with_language'))
{
    function get_resource_with_language($resource_name, $language)
    {
        $resource = new resource();
        return $resource->get_resource($resource_name, $language);
    }
}

if(!function_exists('get_male_female_array'))
{
    function get_male_female_array()
    {
        return array(
            'Male'=>get_resource(res_male), 
            'Female'=>get_resource(res_female));
    }
}


if(!function_exists('get_system_setting'))
{
    function get_system_setting($setting_name)
    {
            $setting = new setting();
            return $setting->get_setting_value($setting_name);
    }
}


if(!function_exists('get_enrollment_collection_type_id'))
{
    function get_enrollment_collection_type_id()
    {
            return get_system_setting(sys_School_Fees_Collection_Id);
    }
}

