<?php

echo validation_errors();
echo $this->upload->display_errors('<div class="error_label">', '</div>');

echo form_open_multipart();

echo form_hidden('school_id', $school_info->school_id);

echo form_label(get_resource(res_school_name) . ': ');
echo form_input('school_name', $school_info->school_name);
echo '<br />';

echo form_label('Email: ');
echo form_input('email', $school_info->email);
echo '<br />';

echo form_label('Phone Number: ');
echo form_input('phone_number', $school_info->phone_number);
echo '<br />';

echo form_label(get_resource(res_address) . ': ');
echo form_input('address', $school_info->address);
echo '<br />';

echo form_label(get_resource(res_time_zone) . ': GMT + ');
echo form_input('time_zone', $school_info->time_zone);
echo '<br />';

echo form_label(get_resource(res_logo) . ': ');
echo form_upload('userfile', $school_info->logo);
echo '<br />';


if(current_user_has_permission(Edit_School_Info))
{
    echo form_submit('save', 'Save');
}
