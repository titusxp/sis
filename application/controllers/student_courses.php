<?php

class student_courses extends MY_Controller{
    
    protected $permission = 1;
    
    public function index(){
        
    }
    
    public function show_student_course_list_from_enrollment($enrollment_id)
    {
        $enrollment = new enrollment();
        $enrollment->load($enrollment_id);
        
        $student_id = $enrollment->student_id;
        $class_id = $enrollment->class_id;
        $academic_year = $enrollment->academic_year;
        
        //check if search button is clicked
        $search = $this->input->post('search');
        
        if($search == 'Search'){
            $class_id = $this->input->post('class');
            $academic_year = $this->input->post('academic_year');
        }
        
        //get student object
        $student = new Student();
        $student->load($student_id);
        
        //get class object
        $class = new Classs();
        $class->load($class_id);
        
        
        //get all student courses for the passed academic year
        $student_course = new enrollment_course();
        $student_courses = $student_course->get_all_student_courses($student->student_id, $class->class_id, $academic_year);
                

        
        $data = array(
            'student' => $student, 
            'selected_class' => $class, 
            'selected_academic_year' => $academic_year, 
            'student_courses' => $student_courses,
            'enrollment' => $enrollment
            );
        
        $this->LoadViewHeader();
        $this->load->view('enrollments/student_course_sheet', $data);
        $this->LoadViewFooter();
    }
    
    public function show_student_course_list($student_id, $class_id, $encrypted_academic_year)
    {
        
        $academic_year = get_decrypted_format($encrypted_academic_year);
        
        $enrollment = new enrollment();
        $enrollment->load_enrollment($student_id, $class_id, $academic_year);
        
        //check if search button is clicked
        $search = $this->input->post('search');
        
        if($search == 'Search'){
            $class_id = $this->input->post('class');
            $academic_year = $this->input->post('academic_year');
        }
        
        //get student object
        $student = new Student();
        $student->load($student_id);
        
        //get class object
        $class = new Classs();
        $class->load($class_id);
        
        
        //get all student courses for the passed academic year
        $student_course = new enrollment_course();
        $student_courses = $student_course->get_all_student_courses($student->student_id, $class->class_id, $academic_year);
                

        
        $data = array(
            'student' => $student, 
            'selected_class' => $class, 
            'selected_academic_year' => $academic_year, 
            'student_courses' => $student_courses,
            'enrollment' => $enrollment
            );
        
        $this->LoadViewHeader();
        $this->load->view('enrollments/student_course_sheet', $data);
        $this->LoadViewFooter();
    }
    
    public function edit_student_course($enrollment_course){
        $data = array();
        //get student course
        $student_course = new enrollment_course();
        $student_course->load($enrollment_course);
        $data['student_course'] = $student_course;
        
        //get course object
        $course = $student_course->Course();
        $data['course'] = $course;
        
        //get teaching staff object
        $staff = $student_course->staff();
        $data['staff'] = $staff;
        
        //get student object
        $student = $student_course->student();
        $data['student'] = $student;
        
        //get enrollment object. necessary to get the class object
        
        $enrollment = new enrollment();
        $enrollment->load($student_course->enrollment_id);
            
        $class = new Classs();
        $class->load($enrollment->class_id);
        $data['class'] = $class;
        
        $this->form_validation->set_rules(
                array(
//                    array(
//                        'field' => 'grade',
//                        'label' => 'Grade',
//                        'rules' => 'callback_is_valid_grade'
//                    ),
                    array(
                        'field'=>'score',
                        'label'=>  get_resource(res_score),
                        'rules'=>'is_numeric'
                    )
                ));
        
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');
        
        if(!$this->form_validation->run())
        {
            $this->LoadViewHeader();
            $this->load->view('enrollments/edit_student_course', $data);
            $this->LoadViewFooter();
        }
        else{
             $score = $this->input->post('score');
             
             $student_course->score = empty($score) ? null: $score;
            
             $student_course->save();
                       
            
            redirect(site_url('student_courses/show_student_course_list/' . $student_course->student_id . '/' . $enrollment->class_id . '/' . get_encrypted_format($enrollment->academic_year)));
        }
    }
    
//    public function is_valid_grade($input){
//        return strlen($input) <= 2 && !is_numeric($input);
//    }
}