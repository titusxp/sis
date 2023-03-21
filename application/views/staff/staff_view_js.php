<script type="text/javascript">

(function(){
angular.module('sis', [])
.controller('StaffController', function($scope, $http)
    {
        $scope.showWaitForm = true;
        $scope.selectedYear = '<?php echo get_current_academic_year(); ?>';
        $scope.allAcademicYears = [];
        $scope.siteURL = '<?php echo site_url()?>' + '/';
        $scope.KeyWordEncryptionkey = '<?php echo get_keyword_encryption_key() ?>';
        
        //since we need a list of all academic years, let's get it
        $http.get($scope.siteURL + 'global_json_repo/get_all_academic_years/').success(function(result)
        {
            $scope.allAcademicYears = result;
        });
        
        
        $scope.setSection = function(thisSection)
        {
            $scope.section = thisSection;
            if(thisSection === 'staff')
            {
                $scope.getAllStaff();
            }
            
            if(thisSection === 'salaries')
            {
                 $scope.getPayroll();
            }
        }
        
        
        $scope.getPayroll = function()
        {
            $scope.showWaitForm = true;
            var encryptedYear = $scope.selectedYear.replace("/", "and");
            var url = $scope.siteURL + 'staff/json_get_payroll';
            url += '/' + encryptedYear;
            console.log(url);
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.Payroll =  result;
                $scope.TotalPayroll = [0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                                
                for(var i = 0; i < result.length; i++)
                {
                    //alert(isNaN(result[i].january)? 0 : result[i].january);
                    
                    $scope.TotalPayroll[1] += parseInt(isNaN(result[i].January)? 0 : result[i].January);
                    $scope.TotalPayroll[2] += parseInt(isNaN(result[i].February)? 0 : result[i].February); 
                    $scope.TotalPayroll[3] += parseInt(isNaN(result[i].March)? 0 : result[i].March);
                    $scope.TotalPayroll[4] += parseInt(isNaN(result[i].April)? 0 : result[i].April);
                    $scope.TotalPayroll[5] += parseInt(isNaN(result[i].May)? 0 : result[i].May);
                    $scope.TotalPayroll[6] += parseInt(isNaN(result[i].June)? 0 : result[i].June);
                    $scope.TotalPayroll[7] += parseInt(isNaN(result[i].July)? 0 : result[i].July);
                    $scope.TotalPayroll[8] += parseInt(isNaN(result[i].August)? 0 : result[i].August);
                    $scope.TotalPayroll[9] += parseInt(isNaN(result[i].September)? 0 : result[i].September);
                    $scope.TotalPayroll[10] += parseInt(isNaN(result[i].October)? 0 : result[i].October);
                    $scope.TotalPayroll[11] += parseInt(isNaN(result[i].November)? 0 : result[i].November);
                    $scope.TotalPayroll[12] += parseInt(isNaN(result[i].December)? 0 : result[i].December);
                    $scope.TotalPayroll[13] =  parseInt(isNaN(result[i].yearly_total)? 0 : result[i].yearly_total);
                }
            })
            .error(function(data)
            {
                $scope.showWaitForm = false;
            });
        }
        
        $scope.createNewSalary = function()
        {
            $scope.showWaitForm = true;
            $scope.salaryExistenceIndex = 0;
            $scope.newSalaryPayment = [];
            $scope.newSalaryPayment.academic_year = $scope.selectedYear;
            $scope.canSaveNewSalary = false;
            $scope.allDataProvided = false;
                        
            $http.get($scope.siteURL + 'global_json_repo/get_all_months/').success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.allMonths = result;
            })
            .error(function(data)
            {
                $scope.showWaitForm = false;
            });
        }
        
        $scope.authenticateNewSalaryForm = function()
        {              
            $scope.salaryExistenceIndex = 0;//pretty important code. Just remember that lol
            
            if($scope.salaryForm.$valid)
            {
                $scope.showCheckingMessage = true;
                var url = $scope.siteURL + 'collections/collection_exists/';
                url += $scope.newSalaryPayment.staff_id + '/';
                url += $scope.newSalaryPayment.academic_year.replace("/", "and") + '/';
                url += $scope.newSalaryPayment.salaryMonthIndex;
                
                $http.get(url).success(function(result)
                {
                    $scope.showCheckingMessage = false;
                    $scope.showWaitForm = false;
                    if(result==='true')
                    {
                        $scope.salaryExistenceIndex = 1;
                    }
                    else
                    {
                        $scope.salaryExistenceIndex = -1;
                    }
                }).error(function(data)
                {
                    $scope.showWaitForm = false;
                });
            }
        }
        
        $scope.saveCurrentSalary = function()
        {
            $scope.showWaitForm = true;
            var url = $scope.siteURL + 'staff/json_new_salary_payment/';
            url += $scope.newSalaryPayment.academic_year.replace("/", "and");
            url += '/' + $scope.newSalaryPayment.staff_id;
            url += '/' + $scope.newSalaryPayment.amount;
            url += '/' + $scope.newSalaryPayment.salaryMonthIndex;
            
            //alert(url);
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.getPayroll();
                $scope.canSaveNewSalary = false;
            })
            .error(function (data, status, headers, config) 
            {
                alert('an error occured');
                console.log("Error: status = " + status + '  data = ' + data);
                $scope.showWaitForm = false;
            });
        }
        
        
        $scope.isCurrentSection = function(section)
        {
            return $scope.section === section;
        }
          
        $scope.getAllStaff = function()
        {   
            $scope.showWaitForm = true;
            var url = $scope.siteURL + 'staff/json_get_all_staff';
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.AllStaff =  result;
            }).error(function(data)
            {
                $scope.showWaitForm = false;
            });
        };
        
        $scope.searchStaffByKeyWord = function()
        {
            $scope.showWaitForm = true;
            
            var enryptedKey = $scope.staffSearchKeyword.replace(' ', $scope.KeyWordEncryptionkey);
            
            var url = $scope.siteURL + 'staff/json_get_staff_by_keyword';
            url += "/" + enryptedKey;
            
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.AllStaff =  result;
            }).error(function(data)
            {
                $scope.showWaitForm = false;
            });
        }
        
        $scope.clearStaffSearchKeyword = function()
        {
            $scope.staffSearchKeyword = '';
            $scope.searchStaffByKeyWord();
        }
        
        $scope.showStaff = function(id)
        {
            $scope.showWaitForm = true;
            if(id > 0)
            {
                $scope.currentStaff = {};
                var url = $scope.siteURL + 'staff/json_get_staff';
                url += '/' + id;
                $http.get(url).success(function(result)
                {
                    $scope.showWaitForm = false;
                    $scope.currentStaff = result;
                    $scope.getCurrentStaffSalaries();
                })
                .error(function (data, status, headers, config) 
                {
                    $scope.showWaitForm = false;
                });
            }
            else
            {
                $scope.showWaitForm = false;
            }
        };
        
        $scope.saveCurrentStaff = function()
        {
            var url = $scope.siteURL + 'staff/json_save_staff';
            $http({
                method: 'POST',
                url: url,
                data: angular.toJson($scope.currentStaff)
                })
                .success(function (data, status, headers, config) 
                {
                    $scope.currentStaff = data;
                }).error(function(data)
                {
                    $scope.showWaitForm = false;
                });
    
            $scope.getAllStaff();
        }
        
        $scope.createNewStaff = function()
        {
            $scope.currentStaff = {staff_id:0};
        }
        
        $scope.allowOnlyNumbers = function()
        {
            if(isNaN($scope.newSalaryPayment.amount))
            {
                $scope.newSalaryPayment.amount = $scope.newSalaryPayment.amount.slice(0, -1);
            }
        }
        
        
        $scope.getCurrentStaffSalaries = function()
        {
            $scope.showWaitForm = true;
            var url = $scope.siteURL + 'staff/get_staff_salaries/' 
                    + $scope.currentStaff.staff_id + '/' 
                    + $scope.selectedYear.replace("/", "and");
            
            
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.currentStaffSalaries = result;
            }).error(function (data, status, headers, config) 
            {
                $scope.showWaitForm = false;
            });
        }
        
        $scope.deleteSalaryButtonClicked = function(salaryId)
        {
            $scope.deleteId = salaryId;
        }
        
        $scope.isClickedSalary = function(salaryId)
        {
            return $scope.deleteId === salaryId;
        }
        
        $scope.deleteSalaryCollection = function(salaryId)
        {
            $scope.showWaitForm = true;
            var url = $scope.siteURL + 'collections/delete_collection/' + salaryId;
            
            $http.get(url).success(function(result){
                $scope.getCurrentStaffSalaries();
            }).error(function (data, status, headers, config) 
            {
                $scope.showWaitForm = false;
            });;
        }
        
        /*don't let the name of the function confuse you. nothing like what it actually does :)*/
        $scope.clearCurrentSalary = function()
        {
            $scope.deleteId = 0;
        }
        
})
})();
    
</script>