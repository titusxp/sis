<?php

    echo form_open();
    echo form_label('');
    echo form_input('keyword', $keyword);
    echo form_submit('search', get_resource(res_search));
    echo form_close();

?>

<?php 
    echo anchor(site_url('student_enrollments'), get_resource(res_back));                
?>



<h2><?php echo get_resource(res_students) ?></h2>
<div  class="tables" id="all_students">
<?php
    $this->table->set_heading(
            get_resource(res_student_number), 
            get_resource(res_student_name), 
            get_resource(res_date_of_birth), 
            get_resource(res_gender), 
            get_resource(res_language), 
            ''
           );

    //now print out every student
    foreach ($all_students as $student)
    {
        $this->table->add_row(
                $student->student_number, 
                $student->student_name, 
                format_date_of_birth($student->date_of_birth), 
                get_gender($student->gender),
                get_language($student->language),
                get_view_student_link($student->student_number)
            );
    }

    echo $this->table->generate();
?>
</div>


<?php
    function get_view_student_link($student_number) 
    {
        if(current_user_has_permission(View_Enrollments))
        {
            return anchor(site_url('students/show_student/' . $student_number), 
                          get_resource(res_view),
                          array('class'=>'view_link'));
            //return "<a href='" . site_url('students/show_student/') . "/" . $student_number . "'>". get_resource(res_view) ."</a>";        
        }
        return '';
    }


