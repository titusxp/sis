<?php

echo validation_errors();

echo form_open();

echo form_hidden('payment_id', $payment->payment_id);

echo form_label(get_resource(res_academic_year));
echo form_dropdown('academic_year', $all_academic_years, $payment->academic_year);
echo '<br />';

echo form_label(get_resource(res_staff));
echo form_dropdown('staff', $all_staff, $payment->staff_id);
echo '<br />';

echo form_label(get_resource(res_amount_cfa));
echo form_input('amount', $payment->amount);
echo '<br />';

echo form_label(get_resource(res_purpose_of_payment));
echo form_textarea('purpose', $payment->purpose);
echo '<br />';


if(current_user_has_permission(Record_Salary_Payments))
{
    echo form_submit('save', get_resource(res_save));
}

echo form_close();

