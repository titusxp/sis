<div id='single_student_block'>
    <div id='student_picture' class="student_field">
        <img
            src='<?php echo $student->get_full_picture_path(); ?>'
            alt='<?php echo $student->student_name; ?>'
            />
    </div>

    <div id='student_number' class="student_field">
        <?php echo $student->student_number; ?>
    </div>

    <div id='student_name' class="student_field">
        <?php echo $student->student_name; ?>
    </div>

    <div id='gender' class="student_field">
        <nav class="label"><?php echo get_resource(res_gender) . ': '?></nav>
        <nav><?php echo get_gender($student->gender); ?></nav>
    </div>

    <div id='date_of_birth' class="student_field">
        <nav class="label"><?php echo get_resource(res_date_of_birth) . ': '?> </nav>
        <nav><?php echo format_date_of_birth($student->date_of_birth); ?></nav>
    </div>

<!--    <div id='nationality' class="student_field">
        <nav class="label"><?php echo get_resource(res_nationality) . ': '?> </nav>
        <nav><?php echo $student->nationality; ?></nav>
    </div>-->

    <div id='language' class="student_field">
        <nav class="label"><?php echo get_resource(res_language) . ': '?> </nav>
        <nav><?php echo get_language($student->language); ?></nav>
    </div>
    <div class="side_links">
        <?php         
            if(current_user_has_permission(Add_Edit_or_Delete_Enrollments))
            {
                echo get_edit_link($student->student_number);
                echo ' | ';
                echo get_delete_link($student->student_number);
            }
        ?>
    </div>



</div>

<div id="single_student_block">
    <h2>Enrollments</h2>
    <?php
        
        $this->table->set_heading(
                get_resource(res_academic_year),
                get_resource(res_class),
                get_resource(res_fees),
                get_resource(res_fees_paid),                
                get_resource(res_is_scholarship) . '?',
                get_resource(res_status),
                ''
            );
        foreach($enrollments as $enrollment)
        {
            $scholarship_class = $enrollment->is_scholarship() ? 'scholarship' : '';
            
            $this->table->add_row
            (
                    $enrollment->academic_year,
                    $enrollment->get_class()->class_name,
                    $enrollment->fees_due(),
                    $enrollment->fees_paid(),
                    
                    '<nav class = "'
                    . $scholarship_class 
                    . '">' 
                    . get_yes_no($enrollment->is_scholarship())
                    . '</nav>', 
                    
                    '<nav class = "'
                    . $enrollment->status_for_css() 
                    . '">' 
                    . $enrollment->fee_payment_status() 
                    . '</nav>',
                    
                    get_direct_fee_payment_view($enrollment->enrollment_id, $enrollment->fees_completed())
            );
        }
        echo $this->table->generate();
    
    ?>
</div>



<?php

function get_edit_link($student_number) {
    return "<a href = '" . site_url('students/add_edit_student/' . $student_number) . "'>" . get_resource(res_edit) . "</a>";
}

function get_delete_link($student_number) {
    return "<a href = '" . site_url('students/delete_student/' . $student_number) . "' >" . get_resource(res_delete) . "</a>";
}


function get_direct_fee_payment_view($enrollment_id, $fees_completed)
{
    return $fees_completed? '': anchor(site_url('student_fee_payments/add_payment_from_students_view/' . $enrollment_id), get_resource(res_new_payment));
}


?>

