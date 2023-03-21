<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Students extends MY_Controller
 {
     protected $permission = 1;
     
    /*
     * Index method just lists all students
     */
    function index()
    {      
        //check if keyword has been provided and use it if it has
        $post = $this->input->post();
        $keyword = $post['keyword'];
        //show_array($post);
        
        $student = new Student();
        $data = array();
        $data['all_students'] = empty($keyword)? $student->get_all_students() : 
                                $student->get_students_by_keyword($keyword);
        $data['keyword'] = empty($keyword)? '' : $keyword;
        
        $this->load->view('header');
        $this->load->view('students/students', $data);
        $this->load->view('footer');
    }
    
    
    /*
     * Show details of a single student
     */
    function show_student($student_number) {
        
        $student = new Student();
        $student->get_student_from_number($student_number);
        $data = array();
        $data['student'] = $student;
        
        //get student enrollments
        $enrollment_instance = new enrollment();
        $student_enrollments = $enrollment_instance->get_student_enrollments($student->student_id);
        $data['enrollments'] = $student_enrollments;      
 
        $this->load->view('header');
        $this->load->view('students/single_student_view', $data);
        $this->load->view('footer');
    }
    

    function add_edit_student($student_number = NULL) {
        $this->load->model('student');
        $IsnewStudent = true;
        $student = new Student();
        ////get the student
        if (isset($student_number))
        {
            $student->get_student_from_number($student_number);
            $IsnewStudent = false;
        }
        else
        {
            $student->nationality = "Cameroonian";
        }
        
        //load libraries and prepare things fotudent_images_path()r picture upload
        $config['upload_path'] = student_short_images_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '1024';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $config['remove_spaces'] = true;

        $this->upload->initialize($config);

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
            $data = array();
            $data['student'] = $student;

            $this->load->view('header');
            $this->load->view("students/new_student", $data);
            $this->load->view('footer');
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
            
            
            //load values entered by user
            $student->student_name = $this->input->post('student_name');
            $student->language = $this->input->post('language');
            $student->nationality = $this->input->post('nationality');
            $student->gender = $this->input->post('gender');

            if(isset($student->date_of_birth) && !is_valid_date($student->date_of_birth))
            {
                $data = array();
                $data['student'] = $student;
                $data['error_message'] = get_resource(res_provided_date_invalid);

                $this->load->view('header');
                $this->load->view("students/new_student", $data);
                $this->load->view('footer');
                return;
            }
            
            if (!$no_file_provided)
            {
                $student->picture = $this->get_uploaded_picture_name();
                $this->resize_and_save_student_picture($student->picture);
            }
            $student->save();

            if ($IsnewStudent)
            {
                redirect(base_url() . "index.php/students/confirm_student_number/" . $student->student_number);
            }
            else
            {
                redirect(base_url() . "index.php/students/show_student/" . $student->student_number . "/Saved Successfully");
            }
        }
    }

    public function confirm_student_number($student_number) {

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
            echo 'there is a problem';
            $this->LoadViewHeader();
            $this->load->view("students/confirm_student_number", array('student' => $student));
            $this->LoadViewFooter();
        }
        else
        {
            $student->student_number = $this->input->post('student_number');
            $student->save();
            redirect(base_url() . "index.php/students/show_student/" . $student->student_number);
        }
    }

    public function delete_student($student_number) {

        ////get the student if exists, it not do nothing
        if (isset($student_number))
        {
            $this->load->model('student');
            $student = new Student();
            $student->get_student_from_number($student_number);
            
            
            if (isset($student))
            {
                $post = $this->input->post();
            
                if($post['yes'])
                {
                   $student->delete();
                   redirect(site_url('students'));
                }
                
                if($post['no'])
                {
                   redirect(site_url('students/show_student/' . $student_number));
                }
                
                $message = get_resource(res_are_you_sure);
                $data = array('message'=>$message);
                
                $this->LoadViewHeader();
                $this->load->view('yes_no_message_box', $data);
                $this->LoadViewFooter();
            }
            else
            {
                redirect(site_url('students'));
            }
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
     * receives a student's ID and returns the student object
     * in json format
     */
    public function json_get_student($id)
    {
        $student = new Student();
        $student->load($id);
        
        echo json_encode($student);
    }
    
    public function json_get_all_students()
    {
        $st = new Student();
        
        $all_st = $st->get_all_students();
        
        //show_array($all_st);
        
        echo json_encode($all_st);
    }

        
    public function json_get_students_by_keyword($encrypted_keyword = '')
    {
        $key = get_keyword_encryption_key();
        
        $keyword = str_replace($key, ' ', $encrypted_keyword);
        
        $st = new student();
        
        $students = $st->search_by_keyword($keyword);
        
        echo json_encode($students);
    }
    
    
    public function json_save_student()
    {
        $data = json_decode(file_get_contents("php://input"));
        //show_array($data);
        $student = new Student();
        if(isset($data->student_id) && $data->student_id > 0)
        {
            $student->student_id = $data->student_id;
        }
        else
        {
            //if it's a new student, set the date created
            $student->date_created = get_current_date_mysql_format();
        }
        $student->student_name = isset($data->student_name)? $data->student_name : "";
        $student->date_of_birth = isset($data->date_of_birth)? get_mysql_date_format($data->date_of_birth ): null;
        $student->gender = isset($data->gender)? $data->gender: "";
        $student->language = isset($data->language)? $data->language : "";
        $student->student_number =  isset($data->student_number)? $data->student_number: "";
        $student->guardian_name = isset($data->guardian_name)? $data->guardian_name : "";
        $student->guardian_phone_number = isset($data->guardian_phone_number)? $data->guardian_phone_number : "";
        
        $student->save();

        //show_array($student);
    }
    
}