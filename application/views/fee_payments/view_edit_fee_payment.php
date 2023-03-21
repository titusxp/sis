<?php

//surely useless. haven't had time to check
if(isset($error))
{
    echo $error;
}

echo validation_errors();
$enrollment = $payment->get_enrollment();
$academic_year = $enrollment->academic_year;
$class = $enrollment->get_class();
$student = $enrollment->get_student();

echo form_open();

echo form_hidden('academic_year', $academic_year);
echo form_hidden('class_id', $class->class_id);

echo '<div class="field">';
echo '<nav class="label">' . form_label(get_resource(res_academic_year)) . '</nav>';
echo '<nav>' . form_label($academic_year) . '</nav>'; 
echo '</div>';

echo '<div class="field">';
echo '<nav class="label">' . form_label(get_resource(res_class)) . '</nav>';
echo '<nav>' . form_label($class->class_name) . '</nav>';
echo '</div>';

echo '<div class="field">';
echo '<nav class="label">' . form_label(get_resource(res_student)) . '</nav>';
echo '<nav>' . form_label($student->student_name . ' (' . $student->student_number . ')') . '</nav>';
echo '</div>';

echo '<div class="field">';
echo '<nav class="label">' . form_label(get_resource(res_amount_due)) . '</nav>';
echo '<nav>' . form_label($class->class_fees . ' CFA') . '</nav>';
echo '</div>';

echo form_label(get_resource(res_amount_paid));
echo form_input('amount_paid', $payment->amount_paid);
echo '<br />';

echo form_label(get_resource(res_paid_by));
echo form_input('payer', $payment->payer);
echo '<br />';

//echo form_label(get_resource(res_is_scholarship));
//echo form_dropdown('is_scholarship', array('0' => get_resource(res_no), '1' => get_resource(res_yes)), $payment->is_scholarship);
//    echo '<br />';

if(current_user_has_permission(Record_Fee_Payments))
{
    echo form_submit('save', get_resource(res_save));
    echo get_delete_fee_payment_link($payment->payment_id);
}

echo form_close();

echo anchor(site_url('student_fee_payments/show_payments/' . get_encrypted_format($academic_year)), get_resource(res_back));



function get_delete_fee_payment_link($payment_id)
{
    return anchor(
            site_url('student_fee_payments/delete_payment/' . $payment_id) , 
    get_resource(res_delete));
}