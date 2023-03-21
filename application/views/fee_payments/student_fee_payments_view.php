<?php

echo validation_errors();

echo form_open();
echo form_label(get_resource(res_academic_year));
echo form_dropdown('academic_year', $all_academic_years, $selected_academic_year);
echo form_submit('search', get_resource(res_search));
echo form_close();


//if(current_user_has_permission(Add_Edit_or_delete_fee_payments))
//{
//    echo anchor(site_url('student_fee_payments/add_payment'), get_resource(res_new_payment));
//}


echo  anchor(site_url('student_enrollments'), get_resource(res_back));

echo br() . br();

echo '<div class="shadow_block">';

$this->table->set_heading(
        get_resource(res_payment_date), 
        get_resource(res_paid_by), 
        get_resource(res_student), 
        get_resource(res_amount_paid), 
        get_resource(res_amount_owed),
        get_resource(res_recorded_by),
        ''
        );

$total = 0;

foreach ($all_payments as $payment)
{
    $enrollment = $payment->get_enrollment();
    
    $this->table->add_row(
            format_date($payment->payment_date),
            $payment->payer,
            $payment->get_student()->student_name,
            get_CFA_format($payment->amount_paid),
            get_CFA_format($enrollment->fees_due()),
            $payment->get_recording_user()->full_name,
            get_edit_fee_payment_link($payment->payment_id)            
        );
    $total += $payment->amount_paid;
}

echo $this->table->generate();


echo '<div class="label" id="total_summary" >Total: ' . $total . ' CFA </div>';

echo '</div>';

function get_edit_fee_payment_link($payment_id)
{
    if(current_user_has_permission(View_Fee_Payments))
    {
        return anchor(site_url('student_fee_payments/edit_payment/' . $payment_id), get_resource(res_view));
    }
}



