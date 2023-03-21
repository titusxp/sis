<h2>New Class View</h2>
<?php

echo validation_errors();

echo form_open();

echo form_hidden('class_id', $class->class_id);

echo form_label(get_resource(res_section) . ': ');
echo form_dropdown('class_section', get_languages_array(), $class->class_section);
echo '<br />';

echo form_label(get_resource(res_index) . ': ');
echo form_input('class_index', $class->class_index);
echo '<br />';

echo form_label(get_resource(res_class_name) . ': ');
echo form_input('class_name', $class->class_name);
echo '<br />';

echo form_label(get_resource(res_fees_cfa) . ': ');
echo form_input('class_fees', $class->class_fees);
echo '<br />';

echo form_label(get_resource(res_teacher_optional) . ': ');
echo form_dropdown('teacher', $all_teachers, $teacher);
echo '<br />';

if(current_user_has_permission(Edit_Classes_and_Subjects))
{
    echo form_submit('save', get_resource(res_save));
}

echo form_close();

