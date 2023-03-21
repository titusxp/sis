<?php

echo '<div class="shadow_block" id="school_info">';
echo '<img src = "'. $school_info->get_logo_path() .'"/>';
echo '<p>' . $school_info->school_name .  '</p>';
echo '<nav class="label" class="field">' . get_resource(res_address) .': </nav>'.
        '<nav class="field">' . $school_info->address .  '</nav>';
echo '<nav class="label" class="field">' . get_resource(res_phone_number) .': </nav>'.
        '<nav class="field">' . $school_info->phone_number .  '</nav>';
echo '<nav class="label" class="field">' . get_resource(res_email) .': </nav>'.
        '<nav class="field">' . $school_info->email .  '</nav>';

if(current_user_has_permission(Edit_School_Info))
{
    echo anchor(site_url('school_info/edit_school_info'), get_resource(res_edit));
}

echo '</div>';

