<h2>
    <?php
        echo get_resource(res_classes);
    ?>
</h2>
<?php

if(current_user_has_permission(Edit_Classes_and_Subjects))
{
    echo anchor(site_url('classes/new_class/' . $section), get_resource(res_new_class));
}

echo form_open();
echo form_label('Section: ');
echo form_dropdown('class_section', array('English' => get_resource(res_english), 'French' => get_resource(res_french)), $section);
echo form_submit('search', get_resource(res_search));
echo '<br />';
echo form_close();

$table_template = get_table_template();

$this->table->set_template($table_template);
        
$this->table->set_heading(
        get_resource(res_index), 
        get_resource(res_class_name), 
        get_resource(res_section), 
        get_resource(res_fees), 
        '', 
        '',
        ''
    );
foreach ($classes as $thisClass)
{
    $this->table->add_row(
            $thisClass->class_index, 
            $thisClass->class_name, 
            get_language($thisClass->class_section), 
            $thisClass->class_fees . ' CFA', 
            GetEditLink($thisClass->class_id), 
            GetDeleteLink($thisClass->class_id),            
            get_students_link($thisClass->class_id)
        );
}

echo $this->table->generate();

function get_students_link($class_id)
{
    if(current_user_has_permission(View_Enrollments))
    {
        return anchor(
                site_url('student_enrollments/load_enrollments/' 
                        . $class_id . '/' 
                        . get_encrypted_format(get_current_academic_year())), 
                get_resource(res_students));
    }
    return '';
}

function GetEditLink($class_id) 
{    
    if(current_user_has_permission(View_Classes__and_Subjects))
    {
        return anchor(site_url('classes/view_edit/' . $class_id), get_resource(res_view));
    }
    return '';
}

function GetDeleteLink($class_id) 
{
    if(current_user_has_permission(Edit_Classes_and_Subjects))
    {
        return anchor(site_url('classes/Delete/' . $class_id), get_resource(res_delete));
    }
    return '';
}
