<?php

echo form_open();
echo form_label(get_resource(res_are_you_sure));
echo form_submit('yes', get_resource(res_yes));
echo form_submit('no', get_resource(res_no));

