<?php

echo form_open();
echo '<div class="question">' . $message . '</div>';
echo form_submit('yes', get_resource(res_yes));
echo ' ';
echo form_submit('no', get_resource(res_no));
echo form_close();
