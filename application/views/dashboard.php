<?php



if(current_user_has_permission(View_Enrollments))
{
    echo '<h1>' . get_resource(res_enrollment_stats) . '</h1>';
    
    if(count($enrollment_statistics) == 0)
        echo "No Enrollment Statistics to show.";
    
    foreach($enrollment_statistics as $stat)
    {
        echo '<div class="enrollment_statistic">';

        echo '<nav>' . $stat->academic_year . '</nav>';
       // echo br();
        
        echo '<nav class="label">'. get_resource(res_enrollment_count) . ': </nav>';
            echo '<nav>' . $stat->enrollment_count . '</nav>';
           // echo br();

        if(current_user_has_permission(View_Fee_Payments))
        {
            echo '<nav class="label">'. get_resource(res_completed_fee_payments) . ': </nav>';
            echo '<nav>' . $stat->completed_fees_count. '</nav>';
            //echo br();

            echo '<nav class="label">'. get_resource(res_incomplete_payments) . ': </nav>';
            echo '<nav>' . $stat->incomplete_fees_count . '</nav>';
            //echo br();

            echo '<nav class="label">'. get_resource(res_unpaid_enrollments) . ': </nav>';
            echo '<nav>' . $stat->unpaid_fees_count . '</nav>';
            echo br() . br();
            
            echo '<nav class="label">'. get_resource(res_total_amount_expected). ': </nav>';
            echo '<nav>' . get_CFA_format($stat->total_amount_due). '</nav>';
            
            echo '<nav class="label">'. get_resource(res_total_amount_paid) . ': </nav>';
            echo '<nav>' . get_CFA_format($stat->total_amount_paid) . '</nav>';
            
            echo '<nav class="label">'. get_resource(res_total_scholarship_awarded) . ': </nav>';
            echo '<nav>' . get_CFA_format($stat->total_scholarship) . '</nav>';
            
            echo '<nav class="label">'. get_resource(res_total_amount_pending) . ': </nav>';
            echo '<nav>' . get_CFA_format($stat->total_outstanding) . '</nav>';
        }
            echo '</div>';
        
    }
}
