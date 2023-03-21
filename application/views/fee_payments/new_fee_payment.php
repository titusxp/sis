<?php

if(isset($error))
{
    echo $error;
}

echo form_open();

echo form_label(get_resource(res_academic_year));
echo form_dropdown('academic_year', $all_academic_years, $selected_academic_year);

echo form_label(get_resource(res_class));
echo form_dropdown('class', $all_classes, $selected_class_id);

echo form_submit('search', get_resource(res_search));

echo form_close();

echo '<br />';

echo validation_errors();

if(isset($all_students) && count($all_students) > 1)
{
    echo form_open();
    
    echo form_hidden('selected_academic_year', $selected_academic_year);
    echo form_hidden('selected_class_id', $selected_class_id);
    
    echo form_label(get_resource(res_student));
    echo form_dropdown('student', $all_students, 'selected_student');
    echo '<br />';

    echo form_label(get_resource(res_paid_by));
    echo form_input('payer');
    echo '<br />';
    
    echo '<div class="field">';
    echo '<nav class="label">' . form_label(get_resource(res_amount_due)). '</nav>';
    echo '<nav>' . form_label($selected_class->class_fees . ' CFA'). '</nav>';
    echo '</div>';    
    

    echo form_label(get_resource(res_amount_paid));
    echo form_input('amount_paid');
    echo '<br />';

//    echo form_label(get_resource(res_is_scholarship));
//    echo form_dropdown('is_scholarship', array('0' => get_resource(res_no), '1' => get_resource(res_yes)));
//    echo '<br />';
    
    if(current_user_has_permission(Record_Fee_Payments))
    {
        echo form_submit('save', get_resource(res_save));
    }
}
else
{
    echo '<div>'. get_resource(res_no_students) . ' </div>';
}

