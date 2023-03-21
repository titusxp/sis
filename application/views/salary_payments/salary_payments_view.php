<?php


echo form_open();

echo form_label(get_resource(res_academic_year));
echo form_dropdown('academic_year', $all_academic_years, $selected_academic_year);

echo form_submit('Search', get_resource(res_search));

echo form_close();

if(current_user_has_permission(Record_Salary_Payments))
{
    echo anchor(site_url('salary_payments/add_edit_payment'), get_resource(res_new_payment));
}

$this->table->set_heading(
            get_resource(res_payment_date),
            get_resource(res_staff),
            get_resource(res_amount_paid),
            '',
            ''
        );

foreach($all_payments as $payment)
{
    $this->table->add_row(
        format_date($payment->payment_date),
        $payment->get_this_staff()->staff_name,
        get_CFA_format($payment->amount),
        get_view_payment_link($payment->payment_id),
        get_delete_payment_link($payment->payment_id)
    );
}

echo $this->table->generate();


function get_view_payment_link($payment_id)
{
    if(current_user_has_permission(View_Salary_Payments))
    {
        return anchor(site_url('salary_payments/add_edit_payment/' . $payment_id), get_resource(res_view));
    }
    return '';
}

function get_delete_payment_link($payment_id)
{
    if(current_user_has_permission(Record_Salary_Payments))
    {
        return anchor(site_url('salary_payments/delete_payment/' . $payment_id), get_resource(res_delete));
    }
    return '';
    
}


