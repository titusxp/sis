<?php

echo form_open();

echo form_label(get_resource(res_class));
echo form_dropdown('class', $classes, $selectedClass);

echo form_submit('search', get_resource(res_search));

echo form_close();

if(current_user_has_permission(Edit_Classes_and_Subjects))
{
    echo anchor(GetNewCourseLink($selectedClass), get_resource(res_new_subject));
}

if (isset($courses))
{
    $this->table->set_heading(
            get_resource(res_subject_code), 
            get_resource(res_subject_name), 
            '',
            '');
    
    
    foreach ($courses as $course)
    {
        $this->table->add_row(
                $course->course_code, 
                $course->course_name, 
                GetEditLink($course->course_id), 
                GetDeleteLink($course->course_id)
        );
    }

    echo $this->table->generate();
}

function GetEditLink($id) 
{
    if(current_user_has_permission(View_Classes__and_Subjects))
    {
        return anchor(site_url('courses/view_course/' . $id), get_resource(res_view));
    }
    return '';
}

function GetDeleteLink($id) 
{
    if(current_user_has_permission(Edit_Classes_and_Subjects))
    {
        return anchor(site_url('courses/delete_course/' . $id), get_resource(res_delete));
    }
    return '';
}
