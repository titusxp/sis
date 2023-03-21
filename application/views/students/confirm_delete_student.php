
<?php
echo '<div class="question">' . get_resource(res_are_you_sure) . '</div>';

echo anchor(site_url("students/delete_student/" . $student->student_number . "/true"), get_resource(res_yes));
echo ' | ';
echo anchor(site_url("students/index/"), get_resource(res_no));
?>

<a href='<?php echo site_url("students/delete_student/" . $student->student_number . "/true") ?>'> <?php get_resource(res_yes) ?> </a>
|
<a href='<?php echo site_url("students/index/") ?>'> <?php get_resource(res_no) ?> </a>