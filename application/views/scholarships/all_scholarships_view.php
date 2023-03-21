<?php

echo form_open();

echo form_label(get_resource(res_academic_year));
echo form_dropdown('academic_year', $all_academic_years, $selected_academic_year);

echo form_submit('Search', get_resource(res_search));

echo form_close();

echo anchor(site_url('student_enrollments'), get_resource(res_back));

$this->table->set_heading(
           get_resource(res_date_recorded),
            get_resource(res_student),
            get_resource(res_class),   
            get_resource(res_scholarship_amount),
            get_resource(res_recorded_by)
        );

foreach($all_scholarships as $scholarship)
{
    $enrollment = $scholarship->get_enrollment();
    
    $this->table->add_row(
                format_date($scholarship->date_recorded),
                $enrollment->get_student()->student_name,
                $enrollment->get_class()->class_name,
                $scholarship->amount . ' CFA',
                $scholarship->get_recording_user()->full_name
            );
}

echo $this->table->generate();


function get_view_payment_link($payment_id)
{
    if(current_user_has_permission(View_Scholarships))
    {
        return anchor(site_url('scholarships/view_edit_scholarship/' . $payment_id), get_resource(res_view));
    }
    return '';
}

function get_delete_payment_link($payment_id)
{
    if(current_user_has_permission(Award_Scholarships))
    {
        return anchor(site_url('scholarships/view_edit_scholarship/' . $payment_id), get_resource(res_delete));
    }
    return '';
    
}


