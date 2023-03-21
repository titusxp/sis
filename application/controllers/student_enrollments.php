<?php

class Student_EnrollMents extends MY_Controller{
    
    protected $permission = 1;
    
    public function index(){
        $this->load_enrollments();
    }
    
    public function load_enrollments($class_id = 1, $encrypted_academic_year = null)
    {
        //get all posted variables
       $post = $this->input->post();
       
       //get appropriate academic year
       if(isset($post['academic_year']))
       {
           $selected_academic_year = $post['academic_year'];
       }
       else
       {
           $selected_academic_year = isset($encrypted_academic_year)
                ? get_decrypted_format($encrypted_academic_year) 
                : get_decrypted_format(get_current_academic_year());
       }
       
       //get appropriate class
       $selected_class = new Classs();
       if(isset($post['class']))
       {
           $selected_class->load($post['class']);
       }
       else
       {
           $selected_class->load($class_id);
       }
       
       
       //go to new enrollment section if new enrollment button is clicked
       if(isset($post['new']))
       {
           redirect(site_url('student_enrollments/new_enrollment/' 
                   . $selected_class->class_id 
                   . '/' 
                   . get_encrypted_format($selected_academic_year)));
       }
       
       //get all classes
        $all_classes = array();        
        $class_instance = new Classs();
        foreach($class_instance->get_all_classes_ordered_by_section() as $class){
            $all_classes[$class->class_id] = $class->get_full_class_name();
        }
       
       //get all academic years
       $academic_year_instance = new Academic_year();
       $all_academic_years = $academic_year_instance->load_all_academic_years();
       
       //get all enrollments for selected class and academic
       $enrolment_instance = new enrollment();
       $all_enrollments = 
               $enrolment_instance->get_enrollment_by_class_and_year(
                       $selected_class->class_id, 
                       $selected_academic_year);
       
       //get enrollment stat
       $stat = new enrollment_statistic($selected_academic_year);
       
       
       $data = array(
           'selected_academic_year' => $selected_academic_year,
           'all_academic_years' => $all_academic_years,
           'selected_class' => $selected_class,
           'all_classes'=> $all_classes,
           'teacher' => $selected_class->get_this_class_teacher(),
           'all_enrollments' => $all_enrollments,
           'enrollment_statistic' => $stat
       );
       
       $this->LoadViewHeader();
       $this->load->view('enrollments/enrollments_view', $data);
       $this->LoadViewFooter();
    }
    
        
    /*
     * Opens a view for the user to add a new enrolment into the system
     * Provides the user with functionality to search for a student either by 
     * student number or student name
     */
    public function new_enrollment($class_id, $encrypted_academic_year, $student_id = null){
                
        //if a student has been selected to enroll
        if(isset($student_id)){
            redirect(site_url('student_enrollments/add_enrollment/' . $student_id . '/' . $class_id . '/' . $encrypted_academic_year));
            return;
        }
        
        //decrypt academic year for use        
        $academic_year = get_decrypted_format($encrypted_academic_year); 
        
        //get class object
        $class = new Classs();
        $class->load($class_id);
        
        
        $data = array('class' => $class, 'current_academic_year' => $academic_year);
        
        
        $this->form_validation->set_rules(array(
            array(
                'field' => 'student_search_keyword',
                'label' => get_resource(res_keyword),
                'rules' => 'required'),
            ));
        
        if(!$this->form_validation->run())
        {
            $this->LoadViewHeader();
            $this->load->view('enrollments/new_enrollment', $data);
            $this->LoadViewFooter();
        }
        else{
            $keyword = $this->input->post('student_search_keyword'); 
            
            
            $students = $this->student->get_students_by_keyword($keyword);
                        
            $data['students'] = $students;
            
            $this->LoadViewHeader();
            $this->load->view('enrollments/new_enrollment', $data);
            $this->LoadViewFooter();
        }
        
        
    }
    
    
    
     /*
     * Opens a view for the user to add a new enrolment into the system for a new student
     */
    public function new_enrollment_new_student($class_id, $encrypted_academic_year)
    {        
        
        //decrypt academic year for use        
        $academic_year = get_decrypted_format($encrypted_academic_year); 
        
        //get class object
        $class = new Classs();
        $class->load($class_id);
        
        //prepare new student object
        $student = new Student();
        $student->nationality = "Cameroonian";
        
        
        $data = array(
            'class' => $class, 
            'current_academic_year' => $academic_year,
            'student' => $student
            );
        
        
        //load libraries and prepare things fotudent_images_path()r picture upload
        $config['upload_path'] = student_short_images_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '2048';
        $config['max_width'] = '2048';
        $config['max_height'] = '1024';
        $config['remove_spaces'] = true;

        $this->upload->initialize($config);
        
        
        //set form validation rules
                //prepare form validation
        $this->form_validation->set_rules(array(
            array(
                'field' => 'student_name',
                'label' => get_resource(res_student_name),
                'rules' => 'required'),
            array(
                'field' => 'day',
                'label' => get_resource(res_date_of_birth),
                'rules' => 'required|is_numeric'
            ),
            array(
                'field' => 'month',
                'label' => get_resource(res_date_of_birth),
                'rules' => 'required'
            ),
            array(
                'field' => 'year',
                'label' => get_resource(res_date_of_birth),
                'rules' => 'required|is_numeric'
            ),
            array(
                'field' => 'gender',
                'label' => get_resource(res_gender),
                'rules' => 'required'),
            array(
                'field' => 'language',
                'label' => get_resource(res_language),
                'rules' => 'required'),
//            array(
//                'field' => 'nationality',
//                'label' => get_resource(res_nationality),
//                'rules' => 'required'
//            ),
        ));
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');

        $no_file_provided = !($_FILES && $_FILES['userfile']['name']);


        
        
        if (!$this->form_validation->run() || (!$no_file_provided && !$this->upload->do_upload()))
        {
            $this->LoadViewHeader();
            $this->load->view('enrollments/new_enrollment_new_student', $data);
            $this->LoadViewFooter();
        }
        else
        { 
            $post = $this->input->post();
            
            
            
            if(!isset($post['IgnoreDateOfBirth']))
            {
                $student->date_of_birth = null;
            } 
            else
            {
                $student->date_of_birth = $post['year'] . '-' . $post['month'] . '-' . $post['day']; 
                
            }
            
            if(isset($student->date_of_birth) && !is_valid_date($student->date_of_birth))
                {
                    $data = array();
                    $data['student'] = $student;
                    $data['error_message'] = get_resource(res_provided_date_invalid);

                    $this->load->view('header');
                    $this->load->view('enrollments/new_enrollment_new_student', $data);
                    $this->load->view('footer');
                    return;
                }
            
            //load values entered by user
            $student->student_name = $this->input->post('student_name');
            $student->language = $this->input->post('language');
            //$student->nationality = $this->input->post('nationality');
            $student->gender = $this->input->post('gender');

            
            
            if (!$no_file_provided)
            {
                $student->picture = $this->get_uploaded_picture_name();
                $this->resize_and_save_student_picture($student->picture);
            }
            
            //show_array($student);
            
            $student->save();

            redirect(
                   'student_enrollments/confirm_student_number/'
                   . $student->student_number . '/'
                   . $class_id . '/'
                   . $encrypted_academic_year
               );
        }
        
        
    }
    
    
    public function confirm_student_number($student_number, $class_id, $encrypted_academic_year) 
    {
        $student = new Student();
        $student->get_student_from_number($student_number);

        $this->form_validation->set_rules(array(
                array(
                    'field' => 'student_number',
                    'label' => get_resource(res_student_number),
                    'rules' => 'required'
                    )
            ));
        
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');

        if (!$this->form_validation->run())
        {
            $this->LoadViewHeader();
            $this->load->view("students/confirm_student_number", array('student' => $student));
            $this->LoadViewFooter();
        }
        else
        {
            $student->student_number = $this->input->post('student_number');
            $student->save();
            
            redirect(site_url('student_enrollments/add_enrollment/' 
                    . $student->student_id . '/'
                    . $class_id . '/'
                    . $encrypted_academic_year));
        }
    }
    
    
    
    private function resize_and_save_student_picture($file_name) {
        $fileName = student_short_images_path . $file_name;
        $this->load->model('image_resizer');
        $imageResizer = new image_resizer($fileName);
        $imageResizer->resizeImage(150, 150, 'auto');
        $imageResizer->saveImage($fileName, 100);
    }

    private function get_uploaded_picture_name() {
        $data = $this->upload->data();
        return $data['file_name'];
    }
    
    
    /*
     * Contains the actual functions to add an enrollment.
     * 
     */
    public function add_enrollment($student_id, $class_id, $encrypted_academic_year)
    { 
        $academic_year = get_decrypted_format($encrypted_academic_year);
        //get student object
        $student = new Student();
        $student->load($student_id);
        
        //get class object
        $class = new Classs();
        $class->load($class_id);
        
        //verify if enrollment already exists
       $enrollment_instance = new Enrollment();
       $existing_enrollment = $enrollment_instance->get_enrollment($student->student_id, $class->class_id, $academic_year);
              
       $error_message = get_resource(res_student_already_in_class_and_year);
       if($existing_enrollment != null){
           $data = array(
               'error_message' => $error_message,
//               $student->student_name . 
//                                    ' is already enrolled in ' .
//                                    $class->class_name . ' in ' . $academic_year,
               'redirect_link' => site_url('student_enrollments')
           );
           
           $this->LoadViewHeader();
           $this->load->view('error_view', $data);
           $this->LoadViewFooter();
       }
       else
       {
           //create and save the new enrollment
           $enrollment = new Enrollment();
           $enrollment->student_id = $student->student_id;
           $enrollment->class_id = $class->class_id;
           $enrollment->academic_year = $academic_year;
           $enrollment->fees_due = $class->class_fees;
           $enrollment->save();
           
           //get all courses for current class
          $course = new Course();
          $class_courses = $course->get_courses_by_class($class->class_id);
           
           //add all courses to student
          $class_instance = new classs();
          $class_teacher = $class_instance->get_class_teacher($class->class_id);
          
          foreach($class_courses as $course){
              $student_course = new enrollment_course();
              $student_course->student_id = $student->student_id;
              $student_course->course_id = $course->course_id;
              $student_course->enrollment_id = $enrollment->enrollment_id;
              $student_course->teaching_staff_id = $class_teacher->staff_id;
              $student_course->save();              
          }
          redirect(site_url('student_courses/show_student_course_list/' 
                  . $student->student_id . '/' 
                  . $class->class_id . '/' 
                  . get_encrypted_format($academic_year)));
       }
       
    }
    
    /*
     * function to confirm whether or not the user wants to delete an enrollment
     */
    public function delete_enrollment($student_id, $class_id, $encrypted_academic_year){
        $post = $this->input->post();
               
        
        $redirect_link = site_url(
                'student_enrollments/load_enrollments/' . 
                $class_id . '/' . $encrypted_academic_year);
        
        if(isset($post['yes'])){   
            $enrollment = new Enrollment();
            $enrollment->load_enrollment($student_id, $class_id, get_decrypted_format($encrypted_academic_year));
            
            //delete all student_courses for this enrollment
            $student_course = new enrollment_course();
            $student_course->delete_courses_from_enrollment($enrollment->enrollment_id);
           
            //delete enrollment
            $enrollment->delete();
            redirect($redirect_link);
        }
        
        if(isset($post['no'])){
            redirect($redirect_link);
        }
        
        $message = get_resource(res_are_you_sure);
        $data = array('message' => $message);
        
        $this->LoadViewHeader();
        $this->load->view('yes_no_message_box', $data);
        $this->LoadViewFooter();
    }
    
    public function promote_student($student_id, $class_id, $encrypted_academic_year){
        //get redirect link
        $redirect_link = site_url('student_courses/show_student_course_list/'. $student_id .'/'. $class_id .'/'. $encrypted_academic_year);

        //check for post variables
        $post = $this->input->post();
        
        if(isset($post['no']))
        {
            redirect ($redirect_link);
        }
        
        
        //get class object of selected class
        $class = new Classs();
        $class->load($class_id);

        //if there is no class to promote to. Abort
        $next_class = $class->get_next_class($class->class_id);
        if($next_class == null){
            $message = get_resource(res_no_class_after) . ' ' . $class->class_name;
            $this->load->view('error_view', array('error_message' => $message, 'redirect_link'=>$redirect_link));
            return;
        }
            
        //get student object
        $student = new Student();
        $student->load($student_id);
        
        //get next academic year to promote to
        $academic_year = get_decrypted_format($encrypted_academic_year);
        $next_year = get_next_academic_year($academic_year); 
        
       if(isset($post['yes']))
       {
           $add_enrol_link = site_url('student_enrollments/add_enrollment/' . $student->student_id . '/' . $next_class->class_id . '/' . get_encrypted_format($next_year));
        
           redirect ($add_enrol_link);
       }
       
       //the message is:
       //are you sure you want to promote <student name> to <class name> in <academic year>?
       $msg = get_resource(res_are_you_sure_to_promote);
       $msg = str_replace('%student', $student->student_name, $msg);
       $msg = str_replace('%class', $next_class->class_name , $msg);
       $msg = str_replace('%academic_year', $next_year, $msg);
       
       $this->LoadViewHeader();
       $this->load->view('yes_no_message_box', array('message' => $msg));
       $this->LoadViewFooter();
    }
}
