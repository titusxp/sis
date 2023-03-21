<script type="text/javascript">

(function(){
angular.module('sis', [])
.controller('financeSummaryController', function($scope, $http)
    {
        $scope.showWaitForm = true;
        $scope.selectedYear = '<?php echo get_current_academic_year(); ?>';
        $scope.siteURL = '<?php echo site_url()?>' + '/';
        
        var url = $scope.siteURL + 'home/get_all_finance_summaries';
        
        $http.get(url).success(function(result)
        {
            $scope.showWaitForm = false;
            $scope.summaries = result;
        })
        .error(function (data, status, headers, config) 
        {
            $scope.showWaitForm = false;
        });
        
        var url2 = $scope.siteURL + 'bank_transactions/get_bank_account_balance';
        $http.get(url2).success(function(result)
        {
            $scope.showWaitForm = false;
            $scope.bank_account_balance = result;
        })
        .error(function (data, status, headers, config) 
        {
            $scope.showWaitForm = false;
        });
        
        

})
})();
    
</script>
