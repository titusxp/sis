<?php

echo anchor(site_url('permission_groups/add_edit_group'), get_resource(res_new_permission_group));

$this->table->set_heading(get_resource(res_group_name), '');
foreach($all_permission_groups as $permission_group)
{
    $this->table->add_row(
            $permission_group->group_name,
            anchor(site_url('permission_groups/add_edit_group/' . $permission_group->group_id), get_resource(res_view))
            );
}

echo $this->table->generate();