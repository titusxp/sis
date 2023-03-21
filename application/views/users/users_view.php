<?php

if(current_user_has_permission(Edit_User_Info))
{
    echo anchor(site_url('users/add_user'), get_resource(res_new_user)) . '  |  ';

    echo anchor(site_url('permission_groups/show_all_groups'), get_resource(res_permission_groups));
}

$this->table->set_heading(get_resource(res_full_name),  '', '');
foreach ($allUsers as $user)
{
    $this->table->add_row($user->full_name, GetEditLink($user->user_id), GetDeleteLink($user->user_id));
}

echo $this->table->generate();

function GetEditLink($user_id) 
{
    if(current_user_has_permission(View_Users))
    {
        return anchor(site_url('users/view_edit_user/' . $user_id), get_resource(res_view));
    }
    return '';
}

function GetDeleteLink($user_id) 
{
    if(current_user_has_permission(Edit_User_Info))
    {
        return anchor(site_url('users/delete_user/' . $user_id), get_resource(res_delete));
    }
    return '';
}
