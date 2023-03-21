<script type="text/javascript">

(function(){
angular.module('sis', [])
.controller('SearchFeesController', function($scope, $http)
    {   
        $scope.selectedClass = '0';
        $scope.siteURL = '<?php echo site_url() ?>' + '/';
        $scope.selectedYear = '<?php echo get_current_academic_year(); ?>';
        $scope.KeyWordEncryptionkey = '<?php echo get_keyword_encryption_key() ?>';
                 
        $scope.allowOnlyNumbers = function()
        {
            if(isNaN($scope.newFee))
            {
                $scope.newFee = $scope.newFee.slice(0, -1);
            }
            if(isNaN($scope.newDeductionAmount))
            {
                $scope.newDeductionAmount = $scope.newDeductionAmount.slice(0, -1);
            }
        }
        
        $scope.currentUser = [];
        $http.get($scope.siteURL + 'users/json_get_current_user').success(function(result)
        {
            $scope.currentUser = result;
        });
        
        $scope.allClasses = [];
        $http.get($scope.siteURL + 'global_json_repo/get_all_classes/yes').success(function(result)
        {
            $scope.allClasses = result;
        });
        
                
        $scope.allAcademicYears = [];
        $http.get($scope.siteURL + 'global_json_repo/get_all_academic_years').success(function(result)
        {
            $scope.allAcademicYears = result;
        });
        
        $http.get($scope.siteURL + 'collections/json_get_enrolment_collections' 
                + '/' + $scope.selectedClass + '/' 
                + $scope.selectedYear.replace("/", "and")).success(function(result)
        {
            $scope.showWaitForm = false;
            $scope.collections = result;
        });
        
        $scope.setSection = function(thisSection)
        {
            $scope.section = thisSection;
            if(thisSection === 'student')
            {
                $scope.getStudents();
            }
        }
        
        $scope.getCollections = function()
        {   
            $scope.showWaitForm = true;
            var url = $scope.siteURL + 'collections/json_get_enrolment_collections';
            url += '/' + $scope.selectedClass + '/' + $scope.selectedYear.replace("/", "and");
            
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.collections =  result;
                $scope.enrollmentsEmpty = result.length === 0;
            });
        };
        
        $scope.showCollection = function(id)
        {
            $scope.showWaitForm = true;
            //this is for when we come here from the single student view
            $scope.canAddNewCollection = false; 
            
            $scope.tab = 'basic';//this is bad code but, sorry. I'm tired massa
            
            
            if(id > 0)
            {
                $scope.currentCollection = {};
                var collectionUrl = $scope.siteURL + 'collections/json_get_collection_detail_by_id';
                collectionUrl += '/' + id;
                $http.get(collectionUrl).success(function(result)
                {
                    $scope.showWaitForm = false;
                    $scope.currentCollection = result;
                    
                    $scope.calculateTotalFees();
                    
                    $scope.calculateTotalDeductions();
                });
            }
            else
            {
                $scope.showWaitForm = false;
            }
            $scope.canAddNewDeduction = false;
            $scope.canAddNewfee = false;
        };
        
        $scope.calculateTotalFees = function()
        {
            var total = 0;
            for(var i = 0; i < $scope.currentCollection.fees.length; i++)
            {
                 total = 1*total + 1*$scope.currentCollection.fees[i].amount;
            }
            $scope.currentCollection.totalFeesPaid = total;
        }
        
        $scope.calculateTotalDeductions = function()
        {
            total = 0;
            for(var i = 0; i < $scope.currentCollection.AllDeductions.length; i++)
            {
                 total = 1*total + 1*$scope.currentCollection.AllDeductions[i].amount;
            }
            $scope.currentCollection.totalDeductions = total;
        }
        
        $scope.addFee = function()
        {
            $scope.newFee = $scope.newFee.replace(/\D/g, '');
            if(isNaN($scope.newFee) || !$scope.newFee > 0)
            {
                alert('You must provide a valid CFA number');
            }
            else
            {
                var newFeeObj = {
                'transaction_id':0,
                'collection_id' : $scope.currentCollection.collection_id,
                'amount':$scope.newFee,
                'collection_type_id':$scope.currentCollection.type_id,
                'is_input':'1',
                'date_recorded':new Date() | 'date: dd MMM yyyy HH:mm',
                'date_recorded_iso':new Date(),
                'recorded_by_user_id':$scope.currentUser.user_id,
                'recorded_by':$scope.currentUser.full_name
                 };
           
                $scope.currentCollection.fees.push(newFeeObj);
                
                $scope.canAddNewfee = false;
            }
            
           $scope.newFee = null;
           
           $scope.calculateTotalFees();
        };  
        
        
        $scope.addDeduction = function()
        {
            $scope.newDeductionAmount = $scope.newDeductionAmount.replace(/\D/g, '');
            if(isNaN($scope.newDeductionAmount) || !$scope.newDeductionAmount > 0)
            {
                alert('You must provide a valid CFA deduction amount');
            }
            else
            {
                var newDedObj = {
                'deduction_id':0,
                'amount':$scope.newDeductionAmount,
                'description':$scope.newDeductionReason,
                'collection_id':$scope.currentCollection.collection_id,
                'date_recorded':new Date() | 'date: dd MMM yyyy HH:mm',
                'date_recorded_iso':new Date(),
                'recorded_by_user_id':$scope.currentUser.user_id,
                'recorded_by':$scope.currentUser.full_name
                 };
           
                $scope.currentCollection.AllDeductions.push(newDedObj);
            }
            
           $scope.newDeductionAmount = null;
           $scope.newDeductionReason = null;
           
           $scope.calculateTotalDeductions();
        };  
        
            
        $scope.removeFee = function(feeToRemove)
        {
           $scope.currentCollection.fees.splice($scope.currentCollection.fees.indexOf(feeToRemove), 1);
           $scope.calculateTotalFees();
        };
        
        $scope.removeDeduction= function(dedToRemove)
        {
           $scope.currentCollection.AllDeductions.splice($scope.currentCollection.AllDeductions.indexOf(dedToRemove), 1);
           $scope.calculateTotalDeductions();
        };
        
        
        $scope.deleteEnrollment = function()
        {   
            var url = $scope.siteURL + 'collections/delete_collection/';
            url += '/';
            url += $scope.currentCollection.collection_id;
            
            $http.get(url).success(function(result){
                $scope.getCollections();
            });
        };
        
        $scope.saveCurrentCollection = function()
        {
            var url = $scope.siteURL + 'collections/save_collection_json';
                        
            $http({
                method: 'POST',
                url: url,
                data: angular.toJson($scope.currentCollection)
                })
                .success(function (data, status, headers, config) {
                    $scope.getCollections();
                })
                .error(function (data, status, headers, config) 
                {
                    console.log("Error: status = " + status + '  data = ' + data);
                });
            
            //$scope.loadCurrentStudentEnrollments();
            
        };
        
        $scope.getStudents = function()
        { 
            $scope.showWaitForm = true;
            var url = $scope.siteURL + 'students/json_get_all_students';
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.students =  result;
            });
        };
        
        
        $scope.getStudentsByKeyword = function()
        {
            $scope.showWaitForm = true;
            var enryptedKey = $scope.searchStudentKeyword.replace(' ', $scope.KeyWordEncryptionkey);
            var url = $scope.siteURL + 'students/json_get_students_by_keyword';
            url += "/" + enryptedKey;
            
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.students =  result;
            })
            .error(function (data, status, headers, config) 
            {
                $scope.showWaitForm = false;
                console.log(data);
            });
        };
        
        $scope.showStudent = function(id)
        {
            $scope.showWaitForm = true;
            $scope.student_section = 'student_info';
            $scope.currentStudent = {};
            
            
            var url = $scope.siteURL + 'students/json_get_student';
            url += '/' + id;
            
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.currentStudent =  result;
                $scope.loadCurrentStudentEnrollments();

                $scope.newClass = null;
                $scope.newAcademicYear = null;
            });
        };   
        
        
        $scope.loadCurrentStudentEnrollments = function()
        {
            $scope.showWaitForm = true;
            var url = $scope.siteURL + 'collections/json_get_student_enrollments';
            url += '/' + $scope.currentStudent.student_id;
            
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.currentStudent.enrollments =  result;
            });
        }
        
        
        $scope.saveCurrentStudent = function()
        {
            $scope.showWaitForm = true;
            var url = $scope.siteURL + 'students/json_save_student';
            $http({
                method: 'POST',
                url: url,
                data: angular.toJson($scope.currentStudent)
                })
                .success(function (data, status, headers, config) 
                {
                    $scope.getStudents();
                })
                .error(function (data, status, headers, config) 
                {
                    $scope.showWaitForm = false;
                });
        };
        
        $scope.createCollection = function(class_id, academic_year, student_id)
        {
            $scope.showWaitForm = true;
            $scope.newClass = null;
            $scope.newAcademicYear = null;
            $scope.canAddNewCollection = false;
            
            if(!class_id > 0)
            {
                alert("Please Select the class");
                return;
            }
            if(academic_year === null)
            {
                alert("Please select the new academic year");
                return;
            }
            
            $scope.currentCollection = [];
            
            var url = $scope.siteURL + 'collections/json_create_new_collection';
            url += "/" + student_id + "/" + class_id + "/" + academic_year.replace("/", "and");
            //alert(url);
            
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                
                $scope.currentCollection =  result;
                
                $scope.currentStudent.enrollments.push($scope.currentCollection);
                
                //var x = angular.toJson(result);
                //alert(x);
            });
            
            $scope.showCollection(0);
        }
})
})();
    
</script>
