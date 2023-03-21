<?php

class school_info extends MY_Controller
{
    protected $permission = 16384;
    
    public function index()
    {
        redirect(site_url('school_info/view_school_info'));
    }
    
    public function view_school_info()
    {
        $this->load->model('model_school_info');
        $school_info = new model_school_info();
        $school_info->load_school_info();
        
        $data = array('school_info'=> $school_info);
        
        $this->LoadViewHeader();
        $this->load->view('school_info/view_school_info', $data);
        $this->LoadViewFooter();
    }
    
    public function edit_school_info()
    {
        $this->load->model('model_school_info');
        $school_info = new model_school_info();
        $school_info->load_school_info();
        
        $data = array('school_info'=> $school_info);
        
        
        $this->form_validation->set_rules(array(
            array(
                'field' => 'school_name',
                'label' => get_resource(res_school_name),
                'rules' => 'required'
            ),
            array(
                'field' => 'phone_number',
                'label' => get_resource(res_phone_number),
                'rules' => 'is_numeric'
            ),
            array(
                'field' => 'time_zone',
                'label' => get_resource(res_time_zone),
                'rules' => 'is_numeric|required'
            )
        ));
        
        $this->form_validation->set_error_delimiters('<div class="error_message">', '</div>');
        
        $config['upload_path'] = school_images_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '1024';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $config['remove_spaces'] = true;

        $this->upload->initialize($config);
        
        $no_file_provided = !($_FILES && $_FILES['userfile']['name']);
        

        if (!$this->form_validation->run() || (!$no_file_provided && !$this->upload->do_upload()))
        {
            $this->LoadViewHeader();
            $this->load->view('school_info/edit_school_info_view', $data);
            $this->LoadViewFooter();
        }
        else
        {
            $post = $this->input->post();
                        
            $school_info->school_id = $post['school_id'];
            $school_info->school_name = $post['school_name'];
            $school_info->email = $post['email'];
            $school_info->phone_number = $post['phone_number'];
            $school_info->address = $post['address'];
            $school_info->time_zone = $post['time_zone'];
            
            if (!$no_file_provided)//i.e. a file was uploaded
            {
                $school_info->logo = $this->get_uploaded_picture_name();
                $this->resize_and_save_pic($school_info->logo);
            }
            
                        
            $school_info->save();
            
            redirect(site_url('school_info/view_school_info'));
        }
        
    }
    
    private function resize_and_save_pic($single_file_name) {
        $file_name = school_images_path . $single_file_name;
        $this->load->model('image_resizer');
        $imageResizer = new image_resizer($file_name);
        $imageResizer->resizeImage(logo_width, logo_height, 'auto');
        $imageResizer->saveImage($file_name, 100);
    }
    
    private function get_uploaded_picture_name() {
        $data = $this->upload->data();
        return $data['file_name'];
    }
}
