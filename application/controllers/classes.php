<?php



class Classes extends MY_Controller
{    
    protected $permission = 64;
    
    public $edit_class_id;
    public $edit_class_section;
    
    public function index() {
        redirect(site_url('classes/show_all_classes'));
    }

    public function show_all_classes($section = 'English') {
        $post = $this->input->post();

        if ($post['search'])
        {
            $section = $this->input->post('class_section');
        }
        
        $class_instance = new Classs();
        
        $allClasses = $class_instance->get_all_classes_fully($section);
        $data = array('classes' => $allClasses, 'section' => $section);

        $this->LoadViewHeader();
        $this->load->view('classes/classes_view', $data);
        $this->LoadViewFooter();
    }

    public function new_class($section) {
        
        $this->edit_class_section = $section;
        
        $this->form_validation->set_rules(array(
            array(
                'field' => 'class_index',
                'label' => get_resource(res_index),
                'rules' => 'required|is_numeric|callback_is_unique_class_index'
            ),
            array(
                'field' => 'class_name',
                'label' => get_resource(res_class_name),
                'rules' => 'required|callback_is_unique_class_name'
            ),
            array(
                'field' => 'class_fees',
                'label' => get_resource(res_fees),
                'rules' => 'required|is_numeric'
            ),
        ));
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');
        $this->form_validation->set_message('is_unique_class_name', get_resource(res_unique_field_validation));
        $this->form_validation->set_message('is_unique_class_index', get_resource(res_unique_field_validation));

        if (!$this->form_validation->run())
        {
            //get all teachers
            $staff_instancce = new Staff_member();
            $all_teachers = array(0 => null);
            foreach($staff_instancce->get_all_staff() as $staff)
            {
                $all_teachers[$staff->staff_id] = $staff->staff_name;
            }
            $data['all_teachers'] = $all_teachers;            
            
            $data['selected_section'] = $section;
                      
            $this->LoadViewHeader();
            $this->load->view('classes/new_class_view', $data);
            $this->LoadViewFooter();
        }
        else
        {
            $class = new Classs();

            $class->class_index = $this->input->post('class_index');
            $class->class_name = $this->input->post('class_name');
            $class->class_fees = $this->input->post('class_fees');
            $class->class_section = $this->input->post('class_section');
            $teacher_id = $this->input->post('teacher');
            if($teacher_id > 0){
                $class->add_teacher($teacher_id);
            }

            $class->save();

            redirect(site_url('classes/show_all_classes/' . $class->class_section));
        }
    }

    public function view_edit($class_id) {
        
        $this->edit_class_id = $class_id;
        
        $class = new Classs();
        $class->load($class_id);
        $data = array('class' => $class);
        
        $this->edit_class_section = $class->class_section;
        
        $this->form_validation->set_rules(array(
            array(
                'field' => 'class_index',
                'label' => get_resource(res_index),
                'rules' => 'required|is_numeric|callback_is_single_class_index'
            ),
            array(
                'field' => 'class_name',
                'label' => get_resource(res_class_name),
                'rules' => 'required|callback_is_single_class_name'
            ),
            array(
                'field' => 'class_fees',
                'label' => get_resource(res_fees),
                'rules' => 'required|is_numeric'
            ),
        ));
        $this->form_validation->set_error_delimiters('<div class="error_label">', '</div>');
        $this->form_validation->set_message('is_single_class_name', get_resource(res_unique_field_validation));
        $this->form_validation->set_message('is_single_class_index', get_resource(res_unique_field_validation));

        if (!$this->form_validation->run())
        {
            //get all teachers
            $staff_instancce = new Staff_member();
            $all_teachers = array(0 => null);
            foreach($staff_instancce->get_all_staff() as $staff)
            {
                $all_teachers[$staff->staff_id] = $staff->staff_name;
            }
            $data['all_teachers'] = $all_teachers;
            
            //get current teacher
            $teacher = $class->get_class_teacher($class->class_id);
            $data['teacher'] = isset($teacher) ? $teacher->staff_id : 0;
            
            $this->LoadViewHeader();
            $this->load->view('classes/single_class_view', $data);
            $this->LoadViewFooter();
        }
        else
        {
            $this->load->model('classs');

            $class = new Classs();

            $class->class_id = $this->input->post('class_id');
            $class->class_index = $this->input->post('class_index');
            $class->class_name = $this->input->post('class_name');
            $class->class_fees = $this->input->post('class_fees');
            $class->class_section = $this->input->post('class_section');
            $teacher_id = $this->input->post('teacher');
            if($teacher_id > 0){
                $class->add_teacher($teacher_id, $class->class_id);
            }

            $class->save();

            redirect(site_url('classes/show_all_classes/' . $class->class_section));
        }
    }

    public function delete($class_id) {
        $class = new Classs();
        $class->load($class_id);

        $post = $this->input->post();

        if ($post['no'])
        {
            redirect(site_url('classes/show_all_classes/' . $class->class_section));
            return;
        }
        if ($post['yes'])
        {
            $class->delete();
            redirect(site_url('classes/show_all_classes/' . $class->class_section));
            return;
        }
        
        $message = get_resource(res_are_you_sure);
        $data = array('message'=>$message);
        
        $this->LoadViewHeader();
        $this->load->view('yes_no_message_box', $data);
        $this->LoadViewFooter();
    }
    
    //returns true if there is no occurence of a class name in the database
    public function is_unique_class_name($class_name)
    {
        return is_unique_class_name($class_name);        
    }
    
    //returns true if there is only one occurence of a class name in the database
    public function is_single_class_name($class_name)
    {
        return is_single_class_name($this->edit_class_id, $class_name);        
    }
    
    public function is_unique_class_index($class_index)
    {
        return global_is_unique_class_index($this->edit_class_section, $class_index);
    }
    
    public function is_single_class_index($class_index)
    {
        return global_is_single_class_index($this->edit_class_id, $this->edit_class_section, $class_index);        
    }
    
    
    
    public function json_get_class($id)
    {
        $class = new Classs();
        $class->load($id);
        
        echo json_encode($class);
    }
    
}
