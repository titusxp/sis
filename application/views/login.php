<?php if($this->session->flashdata('failed_login') && $this->session->flashdata('failed_login') == true){
    echo "<div class='error_label'> Username or passwor incorrect</div><br />";        
}
 ?>

<?php 
    echo validation_errors();
    
    if(isset($error))
    {
        echo $error;
    }
    
    echo form_open()
?>
    <div> 
        <?php 
           $username_label = current_user()->user_id ? get_resource(res_user_name) : "Username";
           $password = current_user()->user_id ? get_resource(res_password) : "Password";
           $login = current_user()->user_id ? get_resource(res_login) : "Login";
        ?>
        <table class="table">
            <tr>
                <td>
                    <?php echo form_label($username_label);?>
                </td>
                <td>
                    <?php echo form_input('username', set_value('user_name'));?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo form_label($password);?>
                </td>
                <td>
                    <?php echo form_password('password', set_value('password'));?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php echo form_submit('login', $login); ?>
                </td>
            </tr>
        </table>
    </div>
<?php echo form_close();






//$current_year = strftime("%Y", time()) + 1;
//$current_month = strftime("%m", time()) + 1;
//
//echo 'month: ' . $current_month;
//echo 'year: ' . $current_year;
    