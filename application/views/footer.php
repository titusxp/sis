<div id="footer" class="navbar navbar-inverse navbar-static-bottom" style="clear: both">
    <div class="container">
        <div class="navbar-text pull-left">
            <p>&copy <?php echo strftime("%Y", time()); ?></p>
        </div>
    </div>
</div>

    <script type="text/javascript" src="<?php echo base_url(); ?>stuff/js/jquery-1.12.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>stuff/js/angular.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>stuff/js/app.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>stuff/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>stuff/js/titus.js"></script>
    
    <script type="text/javascript" src="<?php echo base_url(); ?>stuff/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>stuff/js/fix_navbar_overlap.js"></script>
   
    <?php
        if(isset($angular_js_view) && !empty($angular_js_view))
        {
            $this->load->view($angular_js_view);
        }
    ?>
    
</body>
</html>
