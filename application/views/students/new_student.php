
<?php 
    if(isset($error_message))
    {
        echo '<div class="error_label">' . $error_message . '</div>';
    }
    echo validation_errors();
    echo $this->upload->display_errors('<div class="error_label">', '</div>');
?>

    
    
<?php echo '<div class = "edit_student">' . form_open_multipart(); ?>

<div class="student_field">
    <?php echo form_label(get_resource(res_student_name)) ?>
    <?php echo form_input('student_name', $student->student_name) ?>
</div>


<div class="student_field" id="date_field">
    <?php 
        $stamp = strtotime($student->date_of_birth);
        
        echo form_label(get_resource(res_date_of_birth) . ' (' . get_resource(res_dd_mm_yyyy) . ')');
        
        $checked_status = $student->date_of_birth != null? "checked" : "unchecked";
        
        echo '<input type="checkbox" name="IgnoreDateOfBirth" value="Ignore" ' . $checked_status . ' id = "hide_date_check_box" />';
      
        
        echo br();
        echo "<div class = 'dob'>";
            echo form_input('day', strftime('%d', $stamp));
            echo form_dropdown('month', get_all_months(), strftime('%m', $stamp));
            echo form_input('year', strftime('%Y', $stamp));
        echo "</div>";
//        echo br();
//        echo form_input('day', strftime('%d', $stamp));
//        echo form_dropdown('month', get_all_months(), strftime('%m', $stamp));
//        echo form_input('year', strftime('%Y', $stamp));
    ?>
    
</div>

<div class="student_field">
    <?php echo form_label(get_resource(res_gender)) ?>
    <?php echo form_dropdown('gender', array('Male' => get_resource(res_male), 'Female' => get_resource(res_female)), $student->gender) ?>
</div>

<div class="student_field">
    <?php echo form_label(get_resource(res_language)) ?>
    <?php echo form_dropdown('language', array('English' => get_resource(res_english), 'French' => get_resource(res_french)), $student->language) ?>
</div>

<!--<div class="student_field">
    <?php echo form_label(get_resource(res_nationality)) ?>
    <?php echo form_input('nationality', $student->nationality) ?>
</div>-->

<div class="student_field">
    <?php echo form_label(get_resource(res_picture_optional)) ?>
    <?php echo form_upload('userfile', $student->picture) ?>
</div>

<div class="student_field">
    <?php 
        if(current_user_has_permission(Add_Edit_or_Delete_Enrollments))
        {
            echo form_submit('Save', get_resource(res_save));
        }
    ?>
</div>
<?php echo form_close() . '</div>'; ?>
