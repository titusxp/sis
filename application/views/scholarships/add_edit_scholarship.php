<?php

    echo validation_errors();

    echo form_open();
?>


    <div class="student_field">
        <nav class="label"><?php echo form_label(get_resource(res_academic_year) . ': '); ?> </nav>
        <nav><?php echo $enrollment->academic_year;?><br></nav>
    </div>

    <div class="student_field">
        <nav class="label"><?php echo form_label(get_resource(res_class) . ': '); ?> </nav>
        <nav><?php echo $enrollment->get_class()->class_name;?><br></nav>
    </div>

    <div class="student_field">
        <nav class="label"><?php echo form_label(get_resource(res_student) . ': '); ?> </nav>
        <nav><?php echo $enrollment->get_student()->student_name;?><br></nav>
    </div>

    <div class="student_field">
        <nav class="label"><?php echo form_label(get_resource(res_scholarship_amount) . '(CFA): '); ?> </nav>
        <nav><?php echo form_input('amount', $scholarship->amount) ?><br></nav>
    </div>   

    <div class="student_field">
        <nav class="label"><?php echo form_label(get_resource(res_reason)); ?> </nav>
        <br />
        <nav><?php echo form_textarea('description', $scholarship->description) ?><br></nav>
    </div> 

<?php

if(current_user_has_permission(Award_Scholarships))
{
    echo form_submit('Save', get_resource(res_save));
}

echo form_close();