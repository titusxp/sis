<!DOCTYPE html>
<html ng-app="sis">
    <head>
        <title>School Information System</title>
        <meta charset="utf-8">
        
        <link rel="icon" href="<?php echo base_url() ?>favicon.ico" type="image/ico">
        
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?php echo base_url(); ?>stuff/css/site.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>stuff/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>stuff/css/bootstrap-datepicker.min.css"/>
        
    </head>
    <body  style="font-size: 1.2em">
        
        
                <?php
               
                //IMPORTANT COMMENTED CODE, NEVER DELETE
//                foreach(get_all_permissions() as $p)
//                {
//                    echo "define('" . str_replace(' ', '_', $p->permission_name) . "', " . $p->permission_level . ");" . br();
//                }
                
                
               // show_array(current_user_permissions());
                
                
//                $sql = 'SELECT distinct resource_name FROM resources order by resource_name';
//                $query = $this->db->query($sql);
//                $result = $query->result_array(); 
//                foreach($result as $r)
//                {
//                    echo "define('res_" . $r['resource_name'] . "', '" . $r['resource_name'] . "');" . br();
//                }

                ?>
        
        <div class="container">
            <nav class="navbar navbar-default navbar-fixed-top" id="main-navbar">
                <div class="container">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                      data-target = ".navbar-collapse">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>                        
                    </button>
                    
                    <?php
                        //load school info
                    $school_info = get_school_info();

//                    echo '<div id="school_info">';
//                        //load logo
//                        echo anchor(
//                                site_url(),
//                                img($school_info->get_logo_path())
//                                );
//
//
//                        //show school name 
//                        echo '<div>' . $school_info->school_name . '</div>';
//
//                    echo '</div>';
                    ?>
                    
                    <a class="navbar-brand" href="<?php site_url() ?>"><?php echo $school_info->school_name?></a>

                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">                            
                            <?php
                                if(current_user_has_permission(View_Enrollments))
                                {
                                    echo "<li>" . anchor(site_url('home'), get_resource(res_home)) . "</li>";
                                }
                                if(current_user_has_permission(View_Enrollments))
                                {
                                    echo "<li>" . anchor(site_url('collections/school_fees'), get_resource(res_enrollments)) . "</li>";                                    
                                }
//                                if(current_user_has_permission(View_Classes__and_Subjects))
//                                {
//                                    echo "<li>" . anchor(site_url('courses'), get_resource(res_subjects)) . "</li>";
//                                }
//                                if(current_user_has_permission(View_Classes__and_Subjects))
//                                {
//                                    echo "<li>" . anchor(site_url('classes'), get_resource(res_classes)) . "</li>";
//                                }
                                if(current_user_has_permission(View_Staff_Information))
                                {
                                    echo "<li>" . anchor(site_url('staff'), get_resource(res_staff)) . "</li>";
                                }                                 
                                if(current_user_has_permission(View_Enrollments))
                                {
                                    echo "<li>" . anchor(site_url('collections/load_collections'), "Other Transactions") . "</li>";                                    
                                }
                                if(current_user_has_permission(View_Staff_Information))
                                {
                                    echo "<li>" . anchor(site_url('settings'), "Settings") . "</li>";
                                }
//                                if(current_user_has_permission(View_Users))
//                                {
//                                    echo "<li>" . anchor(site_url('users'), get_resource(res_users)) . "</li>";
//                                }
//                                if(current_user_has_permission(View_School_Info))
//                                {
//                                    echo "<li>" . anchor(site_url('school_info'), get_resource(res_school_info)) . "</li>";
//                                }
                            ?>
                           
                            <?php
                            
                                $language = current_user()->language;

                                if($language == 'en-GB')
                                {
                                    $language_link = 'Français';
                                    $short_link = 'fr-FR';
                                }
                                if($language == 'fr-FR')
                                {
                                    $language_link = 'English';
                                    $short_link = 'en-GB';
                                }

                                if(current_user()->user_id)
                                {
                                    echo '
                                        <li class="dropdown">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="">
                                                ' . current_user()->full_name .
                                                '<b class="caret"></b>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="">' . anchor('authentication/change_current_user_language/' . $short_link, $language_link) .'</li>                                    
                                                <li class="">' .anchor(site_url('authentication/logout'), get_resource(res_logout)) . '</li>
                                            </ul>
                                        </li> ';
                                }
                                
                            
                            ?>
                            
                                                      
                           
                            
                        </ul>
                    </div>
                </div>
            </nav>
        
            


