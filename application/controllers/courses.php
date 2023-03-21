<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class courses extends MY_Controller
{
    protected $permission = 64;
    
    public function index() 
    {             
        $this->show_courses_for_class(get_first_class_id());
    }

    public function show_courses_for_class($classID = 0) {

        $providedClassID = $classID;
        $posted_class_id = $this->input->post('class');
        
        //overwrite the passed argument if a posted value exists
        if($posted_class_id > 0)
        {
            redirect(site_url('courses/show_courses_for_class/' . $posted_class_id));
        }

        //get all classes
        $allClasses = $this->get_all_classes();
        $data = array('classes' => $allClasses, 'selectedClass' => 0);

        //get all courses for current class
        if ($providedClassID > 0)
        {
            $courseInstance = new Course();
            $classCourses = $courseInstance->get_courses_by_class($providedClassID);
            $data['selectedClass'] = $providedClassID;
            $data['courses'] = $classCourses;
        }
       
        $this->load_views($data);
    }

    public function new_course($class_id = 0) {
        $allClasses = $this->get_all_classes();
        $data = array('classes' => $allClasses);
        
        $data['course'] = new Course();
        
        $data['selected_class_id'] = $class_id;
        

        $this->form_validation->set_rules(array(
            array(
                'field' => 'course_name',
                'label' => get_resource(res_subject_name),
                'rules' => 'required'),
            array(
                'field' => 'course_code',
                'label' => get_resource(res_subject_code),
                'rules' => 'required'),
            array(
                'field' => 'class_id',
                'label' => get_resource(res_class),
                'rules' => 'required'),
        ));
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');
        if (!$this->form_validation->run())
        {
            $this->LoadViewHeader();
            $this->load->view('courses/new_course_view', $data);
            $this->LoadViewFooter();
        }
        else
        {
            $this->load->model('Course');
            $newCourse = new Course();
            $newCourse->course_code = $this->input->post('course_code');
            $newCourse->course_description = $this->input->post('course_description');
            $newCourse->course_name = $this->input->post('course_name');
            $newCourse->class_id = $this->input->post('class_id');

            if(course_exists($newCourse->course_code, $newCourse->course_name, $newCourse->class_id))
            {                
                $allClasses = $this->get_all_classes();
                $new_data = array('classes' => $allClasses);
                $new_data['error_message'] = get_resource(res_subject_code_and_name_must_be_unique_for);
                $new_data['course'] = $newCourse;
                $new_data['selected_class_id'] = $newCourse->class_id;
                get_resource(res_name);

                $this->LoadViewHeader();
                $this->load->view('courses/new_course_view', $new_data);
                $this->LoadViewFooter();
            }
            else
            {
                $newCourse->save();
                redirect(site_url('courses/show_courses_for_class/' . $newCourse->class_id));  
            }
        }
    }

    public function view_course($courseID) {
        $course_id = $courseID;
        $allClasses = $this->get_all_classes();
        
        $course = new Course();
        $course->load($course_id);
        $data = array('classes' => $allClasses, 'course' => $course);
        

        $this->form_validation->set_rules(array(
            array(
                'field' => 'course_name',
                'label' => get_resource(res_subject_name),
                'rules' => 'required'),
            array(
                'field' => 'course_code',
                'label' => get_resource(res_subject_code),
                'rules' => 'required'),
            array(
                'field' => 'class_id',
                'label' => get_resource(res_class),
                'rules' => 'required'),
        ));
        
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');
        
        if (!$this->form_validation->run())
        {
            $this->LoadViewHeader();
            $this->load->view('courses/edit_course_view', $data);
            $this->LoadViewFooter();
        }
        else
        {
            
            $thisCourse = new Course();
            $thisCourse->course_id = $this->input->post('course_id');
            $thisCourse->course_code = $this->input->post('course_code');
            $thisCourse->course_description = $this->input->post('course_description');
            $thisCourse->course_name = $this->input->post('course_name');
            $thisCourse->class_id = $this->input->post('class_id');
            
            //check for duplicates then save if no duplicates are found
            $course_instance = new Course();
            if($course_instance->course_exists($thisCourse->course_code, $thisCourse->course_name, $thisCourse->class_id, $thisCourse->course_id))
            {
                $error_msg = get_resource(res_subject_code_and_name_must_be_unique_for);
                $allClasses = $this->GetAllClases();
                $data = array('classes' => $allClasses, 'course' => $thisCourse);
                $data['error_message'] = $error_msg;
                
                
                $this->LoadViewHeader();
                $this->load->view('courses/edit_course_view', $data);
                $this->LoadViewFooter();
            }
            else
            {
                $thisCourse->save();
                redirect(site_url('courses/show_courses_for_class/' . $course->class_id));  
            }

           
        }
    }
    


    public function delete_course($courseID) {
        $post = $this->input->post();

        $course_id = $courseID;
        $this->load->model('course');
        $course = new Course();
        $course->load($course_id);

        if (isset($post['no']))
        {
            redirect(site_url('courses/show_courses_for_class/' . $course->class_id));
            return;
        }
        if (isset($post['yes']))
        {
            $class_id = $course->class_id;
            $course->delete();
            redirect(site_url('courses/show_courses_for_class/' . $class_id));
            return;
        }

        $message = get_resource(res_are_you_sure);
        $data = array('message'=>$message);
        
        $this->LoadViewHeader();
        $this->load->view('yes_no_message_box', $data);
        $this->LoadViewFooter();
    }

    private function get_all_classes() {
        $classInstance = new Classs();
        $allClasses = $classInstance->get_all_classes();
        return $allClasses;
    }

    private function load_views($data) {
        $this->LoadViewHeader();
        $this->load->view('courses/courses_header');
        $this->load->view('courses/courses', $data);
        $this->LoadViewFooter();
    }

}
