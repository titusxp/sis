
<h2>Permission Group</h2>
<?php

echo validation_errors();
echo form_open();

echo form_hidden('group_id', $permission_group->group_id);

echo form_label(get_resource(res_group_name));
echo form_input('group_name', $permission_group->group_name);
echo br();

echo form_label(get_resource(res_group_description));
echo br();
echo form_textarea('group_description', $permission_group->group_description);
echo br();

echo form_label(get_resource(res_group_permissions));
echo br();

$all_permissions = get_all_permissions();
$group_permissions = $permission_group->get_permissions();


//usort($all_permissions, 'cmp');

foreach($all_permissions as $permission)
{
    $checked = array_key_exists($permission->permission_level, $group_permissions);
    echo '<nav class="permissions">';
    echo form_checkbox('permissions[]', $permission->permission_level, $checked). ' ' . $permission->permission_name;
    echo '</nav>';
}

if(current_user()->is_admin)
{
    echo form_submit('save', get_resource(res_save));
}

echo form_close();





function cmp($a, $b)
{
    return strcmp($a->permission_name, $b->permission_name);
}