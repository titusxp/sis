<?php

if(isset($error))
{
    echo $error;
}

echo validation_errors();

echo form_open();

?>

    <div class="student_field">
        <nav class="label"><?php echo form_label(get_resource(res_academic_year) . ': '); ?> </nav>
        <nav><?php echo form_label($enrollment->academic_year); ?><br /></nav>
    </div>

    <div class="student_field">
        <nav class="label"><?php echo form_label(get_resource(res_class) . ': '); ?> </nav>
        <nav><?php echo form_label($enrollment->get_class()->class_name); ?><br /></nav>
    </div>

    <div class="student_field">
        <nav class="label"><?php echo form_label(get_resource(res_student) . ': '); ?> </nav>
        <nav><?php echo form_label($enrollment->get_student()->student_name); ?><br /></nav>
    </div>

    <div class="student_field">
        <nav class="label"><?php echo form_label(get_resource(res_amount_due) . ': '); ?> </nav>
        <nav><?php echo form_label($enrollment->fees_due() . ' CFA'); ?><br /></nav>
    </div>

<?php

echo form_label(get_resource(res_amount_paid));
echo form_input('amount_paid');
echo '<br />';

echo form_label(get_resource(res_paid_by));
echo form_input('payer');
echo '<br />';

//echo form_label(get_resource(res_is_scholarship));
//echo form_dropdown('is_scholarship', array('0' => get_resource(res_no), '1' => get_resource(res_yes)));
//echo '<br />';

if(current_user_has_permission(Record_Fee_Payments))
{
    echo form_submit('save', get_resource(res_save));
}


