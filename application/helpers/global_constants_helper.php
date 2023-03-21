<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * This are my constants, used all over the app (TitusXP)
 */
define('student_image_height', 100);
define('student_image_width', 100);

define('logo_height', 100);
define('logo_width', 100);

define('student_short_images_path', 'uploads/students/images/');
define('school_images_path', 'uploads/school_info/images/');


//constants for permissions
define('Add_Edit_or_Delete_Enrollments', 2);
define('View_Fee_Payments', 4);
define('Record_Fee_Payments', 8);
define('View_Scholarships', 16);
define('Award_Scholarships', 32);
define('View_Classes__and_Subjects', 64);
define('Edit_Classes_and_Subjects', 128);
define('View_Staff_Information', 256);
define('View_Enrollments', 1);
define('Edit_Staff_Info', 512);
define('View_Salary_Payments', 1024);
define('Record_Salary_Payments', 2048);
define('Edit_User_Info', 8192);
define('View_Users', 4096);
define('Edit_School_Info', 32768);
define('View_School_Info', 16384);





//constants for resources
define('res_academic_year', 'academic_year');
define('res_address', 'address');
define('res_all_students', 'all_students');
define('res_amount', 'amount');
define('res_amount_cfa', 'amount_cfa');
define('res_amount_due', 'amount_due');
define('res_amount_owed', 'amount_owed');
define('res_amount_paid', 'amount_paid');
define('res_april', 'april');
define('res_are_you_sure', 'are_you_sure');
define('res_are_you_sure_to_promote', 'are_you_sure_to_promote');
define('res_august', 'august');
define('res_award_scholarship', 'award_scholarship');
define('res_back', 'back');
define('res_class', 'class');
define('res_classes', 'classes');
define('res_classses', 'classses');
define('res_class_enrollments', 'class_enrollments');
define('res_class_name', 'class_name');
define('res_class_teacher', 'class_teacher');
define('res_complete', 'complete');
define('res_completed_fee_payments', 'completed_fee_payments');
define('res_date_of_birth', 'date_of_birth');
define('res_date_recorded', 'date_recorded');
define('res_dd_mm_yyyy', 'dd_mm_yyyy');
define('res_december', 'december');
define('res_delete', 'delete');
define('res_edit', 'edit');
define('res_edit_or_save_the_proposed_student_number', 'edit_or_save_the_proposed_student_number');
define('res_email', 'email');
define('res_english', 'english');
define('res_enrollments', 'enrollments');
define('res_enrollment_count', 'enrollment_count');
define('res_enrollment_for', 'enrollment_for');
define('res_enrollment_stats', 'enrollment_stats');
define('res_enroll_student', 'enroll_student');
define('res_february', 'february');
define('res_fees', 'fees');
define('res_fees_cfa', 'fees_cfa');
define('res_fees_paid', 'fees_paid');
define('res_fee_payments', 'fee_payments');
define('res_female', 'female');
define('res_for', 'for');
define('res_french', 'french');
define('res_full_name', 'full_name');
define('res_gender', 'gender');
define('res_grade', 'grade');
define('res_group_description', 'group_description');
define('res_group_name', 'group_name');
define('res_group_permissions', 'group_permissions');
define('res_home', 'home');
define('res_incomplete', 'incomplete');
define('res_incomplete_payments', 'incomplete_payments');
define('res_index', 'index');
define('res_invalid_fields_validation', 'invalid_fields_validation');
define('res_is_admin', 'is_admin');
define('res_is_already_in', 'is_already_in');
define('res_is_numeric_validation', 'is_numeric_validation');
define('res_is_scholarship', 'is_scholarship');
define('res_january', 'january');
define('res_july', 'july');
define('res_june', 'june');
define('res_keyword', 'keyword');
define('res_language', 'language');
define('res_login', 'login');
define('res_logo', 'logo');
define('res_logout', 'logout');
define('res_male', 'male');
define('res_march', 'march');
define('res_may', 'may');
define('res_name', 'name');
define('res_nationality', 'nationality');
define('res_new_class', 'new_class');
define('res_new_enrollment', 'new_enrollment');
define('res_new_payment', 'new_payment');
define('res_new_permission_group', 'new_permission_group');
define('res_new_staff', 'new_staff');
define('res_new_student', 'new_student');
define('res_new_student_created', 'new_student_created');
define('res_new_subject', 'new_subject');
define('res_new_user', 'new_user');
define('res_no', 'no');
define('res_none', 'none');
define('res_november', 'november');
define('res_no_class_after', 'no_class_after');
define('res_no_class_in_database', 'no_class_in_database');
define('res_no_students', 'no_students');
define('res_no_students_in_this_class', 'no_students_in_this_class');
define('res_october', 'october');
define('res_ok', 'ok');
define('res_optional_fields', 'optional_fields');
define('res_paid_by', 'paid_by');
define('res_paid_to', 'paid_to');
define('res_password', 'password');
define('res_payment_date', 'payment_date');
define('res_pay_fees', 'pay_fees');
define('res_permission', 'permission');
define('res_permission_groups', 'permission_groups');
define('res_phone_number', 'phone_number');
define('res_picture_optional', 'picture_optional');
define('res_promote_student', 'promote_student');
define('res_provided_date_invalid', 'provided_date_invalid');
define('res_purpose_of_payment', 'purpose_of_payment');
define('res_qualification', 'qualification');
define('res_reason', 'reason');
define('res_recorded_by', 'recorded_by');
define('res_required_fields', 'required_fields');
define('res_required_fields_validation', 'required_fields_validation');
define('res_role', 'role');
define('res_salary', 'salary');
define('res_salary_payments', 'salary_payments');
define('res_save', 'save');
define('res_scholarships', 'scholarships');
define('res_scholarship_amount', 'scholarship_amount');
define('res_school_info', 'school_info');
define('res_school_name', 'school_name');
define('res_score', 'score');
define('res_search', 'search');
define('res_search_for_students_to_enroll', 'search_for_students_to_enroll');
define('res_section', 'section');
define('res_september', 'september');
define('res_staff', 'staff');
define('res_staff_name', 'staff_name');
define('res_status', 'status');
define('res_student', 'student');
define('res_students', 'students');
define('res_students_in', 'students_in');
define('res_student_already_in_class_and_year', 'student_already_in_class_and_year');
define('res_student_course_list', 'student_course_list');
define('res_student_name', 'student_name');
define('res_student_number', 'student_number');
define('res_subject', 'subject');
define('res_subjects', 'subjects');
define('res_subject_code', 'subject_code');
define('res_subject_code_and_name_must_be_unique_for', 'subject_code_and_name_must_be_unique_for');
define('res_subject_description', 'subject_description');
define('res_subject_name', 'subject_name');
define('res_teacher', 'teacher');
define('res_teacher_optional', 'teacher_optional');
define('res_time_zone', 'time_zone');
define('res_total_amount_expected', 'total_amount_expected');
define('res_total_amount_paid', 'total_amount_paid');
define('res_total_amount_pending', 'total_amount_pending');
define('res_total_scholarship_awarded', 'total_scholarship_awarded');
define('res_Unavailable', 'Unavailable');
define('res_unique_field_validation', 'unique_field_validation');
define('res_unpaid', 'unpaid');
define('res_unpaid_enrollments', 'unpaid_enrollments');
define('res_username_or_password_incorrect', 'username_or_password_incorrect');
define('res_users', 'users');
define('res_user_name', 'user_name');
define('res_view', 'view');
define('res_view_scholarship', 'view_scholarship');
define('res_yes', 'yes');






//system settings
define('sys_School_Fees_Collection_Id', 'School Fees Collection Id');
define('sys_Salaries_Collection_Id', 'salaries Collection ID');







