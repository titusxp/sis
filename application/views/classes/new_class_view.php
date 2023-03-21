<h2>New Class</h2>

<?php

echo validation_errors();

echo form_open();

echo form_label('Section: ');
echo form_dropdown('class_section', get_languages_array() ,$selected_section );
echo '<br />';

echo form_label(get_resource(res_index) . ': ');
echo form_input('class_index');
echo '<br />';

echo form_label(get_resource(res_class_name) . ': ');
echo form_input('class_name');
echo '<br />';

echo form_label(get_resource(res_fees_cfa) . ': ');
echo form_input('class_fees');
echo '<br />';

echo form_label(get_resource(res_teacher_optional) . ': ');
echo form_dropdown('teacher', $all_teachers);
echo '<br />';

if(current_user_has_permission(Edit_Classes_and_Subjects))
{
    echo form_submit('save', get_resource(res_save));
}

echo form_close();

