<h2><?php echo get_resource(res_subject)  ?></h2>
<?php

    echo validation_errors();

    if(isset($error_message))
    {
        echo '<div class="error_message">' . $error_message . '</div>';
    }

    echo form_open();

    echo form_hidden('course_id', $course->course_id);

    echo form_label(get_resource(res_class));
    echo form_dropdown('class_id', $classes, $course->class_id);
    echo '<br />';

    echo form_label(get_resource(res_subject_code));
    echo form_input('course_code', $course->course_code);
    echo '<br />';

    echo form_label(get_resource(res_subject_name));
    echo form_input('course_name', $course->course_name);
    echo '<br />';

    echo form_label(get_resource(res_subject_description));
    echo form_textarea('course_description', $course->course_description);
    echo '<br />';

    if(current_user_has_permission(Edit_Classes_and_Subjects))
    {
        echo form_submit('Submit', get_resource(res_save));
    }

    echo form_close();

