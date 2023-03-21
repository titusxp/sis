<?php echo validation_errors() ?>

<h3><?php get_resource(res_new_student_created) ?><h3/>
 
<?php echo get_resource(res_student_name) . ': ' .  $student->student_name; ?><br/>

<?php
    echo form_open();
    echo form_label(get_resource(res_student_number));
    echo form_input('student_number', $student->student_number);
    echo form_submit('Save', get_resource(res_save));
    echo form_close();

        


