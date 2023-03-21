<div>
    <div>
        <?php
        echo validation_errors();
        
        echo form_open();

        echo '<i>' . get_resource(res_required_fields) .'</i><br />';
        
        echo form_hidden('user_id', $user->user_id);
        
        if(current_user_has_permission(Edit_User_Info))
        {
            echo form_label(get_resource(res_permission));
            echo form_dropdown('permission_group', $all_permission_groups, $user->permission_level);
            echo '<br />';
        }

        echo form_label(get_resource(res_full_name));
        echo form_input('full_name', $user->full_name);
        echo "<br />";

        echo form_label(get_resource(res_user_name));
        echo form_input('username',$user->username);
        echo "<br />";
        
        echo form_label(get_resource(res_password));
        echo form_password('password', $user->password);
        echo '<br />';
        
        echo form_label(get_resource(res_language));
        echo form_dropdown('language', array('en-GB'=>'English', 'fr-FR'=>'Francais'), $user->language);
        echo br();

        
        
        if(current_user_has_permission(Edit_User_Info))
        {
            echo form_checkbox('is_admin', $user->is_admin, $user->is_admin) . get_resource(res_is_admin);
            echo br();
        }
        
        
        echo '<br /><i>' . get_resource(res_optional_fields) .'</i><br />';

        echo form_label(get_resource(res_email));
        echo form_input('email', $user->email);
        echo "<br />";

        echo form_label(get_resource(res_phone_number));
        echo form_input('phone_number', $user->phone_number);
        echo '<br />';
                
        if(current_user_has_permission(Edit_User_Info))
        {
            echo form_submit('save', get_resource(res_save));
        }
        
        echo form_close();
        ?>
    </div>
</div>

