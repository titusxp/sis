<div>
    <div>
        <?php
        echo validation_errors();
        
        echo form_open();

        echo '<i>'. get_resource(res_required_fields) .'</i><br />';
                
        echo form_label(get_resource(res_permission));
        $gender = array('Male' => get_resource(res_male), 'Female' => get_resource(res_female));
        echo form_dropdown('permission_group', $permission_groups);
        echo '<br />';

        echo form_label(get_resource(res_full_name));
        echo form_input('full_name');
        echo "<br />";

        echo form_label(get_resource(res_user_name));
        echo form_input('username');
        echo "<br />";
        
        echo form_label(get_resource(res_password));
        echo form_password('password');
        echo '<br />';
        
        echo form_label(get_resource(res_language));
        echo form_dropdown('language', array('en-GB'=>'English', 'fr-FR'=>'Francais'));
        echo br();
        
        echo form_checkbox('is_admin') . get_resource(res_is_admin);
        echo br();
        
        
        
        

        echo '<br /><i>'. get_resource(res_optional_fields) . '</i><br />';

        echo form_label(get_resource(res_email));
        echo form_input('email');
        echo "<br />";

        echo form_label(get_resource(res_phone_number));
        echo form_input('phone_number');
        echo '<br />';
        
        if(current_user_has_permission(Edit_User_Info))
        {
            echo form_submit('save', get_resource(res_save));
        }
        
        echo form_close();
        ?>
    </div>
</div>

