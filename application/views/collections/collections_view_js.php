<script type="text/javascript">

(function(){
angular.module('sis', [])
.controller('CollectionsController', function($scope, $http)
    {   
        $scope.selectedClass = '0';
        $scope.selectedTypeId = '0';
        $scope.selectedOtherTransactionTypeId = -2;
        $scope.selectedType = {};
        $scope.siteURL = '<?php echo site_url() ?>' + '/';
        $scope.selectedYear = '<?php echo get_current_academic_year(); ?>';
        $scope.selectedOtherYear = '<?php echo get_current_academic_year(); ?>';
        $scope.showWaitForm = true;    
        $scope.waitFormMessage = "loading, please wait..."
         
        $scope.allowOnlyNumbers = function()
        {
            if(isNaN($scope.newFee))
            {
                $scope.newFee = $scope.newFee.slice(0, -1);
            }
        };
        
        $scope.setSection = function(thisSection)
        {
            $scope.section = thisSection;
            if(thisSection === 'other')
            {
                $scope.getOtherCollections();
            }
            if(thisSection === 'bank')
            {
                $scope.loadBankTransactions();
            }
        }
        
        $scope.currentUser = [];
        $http.get($scope.siteURL + 'users/json_get_current_user').success(function(result)
        {
            $scope.currentUser = result;
        });
        
        $scope.allClasses = [];
        $http.get($scope.siteURL + 'global_json_repo/get_all_classes').success(function(result)
        {
            $scope.allClasses = result;
            $scope.selectedClass = result[0].class_id;
            $scope.getCollections();
        });
        
        $scope.allIncomeTypes = [];
        $http.get($scope.siteURL + 'collection_types/json_get_non_system_types/0').success(function(result)
        {
            $scope.allIncomeTypes = result;
            if(result.length > 0){
                $scope.selectedTypeId = result[0].type_id;
            }
            $scope.getCollections();
        });
        
        
        $scope.loadNonStudentTypes = function(){
            $scope.waitFormMessage = "loading transaction types..."
            $scope.showWaitForm = true;
            $http.get($scope.siteURL + 'collection_types/json_get_non_student_types').success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.nonStudentTypes = result;
            });
        };
        
        $scope.loadNonStudentTypes();
        
        
        $scope.allExpenseTypes = [];
        $http.get($scope.siteURL + 'collection_types/json_get_non_system_types/1').success(function(result)
        {
            $scope.allExpenseTypes = result;
            if($scope.selectedTypeId === 0)
            {
                if(result.length > 0){
                    $scope.selectedTypeId = result[0].type_id;
                }
                $scope.getCollections();
            }
        });
        
                
        $scope.loadAllAcademicYears = function(){
            $scope.waitFormMessage = "loading academic years..."
            $scope.showWaitForm = true;
            $http.get($scope.siteURL + 'global_json_repo/get_all_academic_years').success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.allAcademicYears = result;
                $scope.getCollections();
            });
        };
        
        $scope.loadAllAcademicYears();
        
        $scope.collectionTypeChanged = function()
        {
            for(var i = 0; i < $scope.allIncomeTypes.length; i++)
            {
                if($scope.allIncomeTypes[i].type_id === $scope.selectedTypeId)
                {
                    $scope.selectedType = $scope.allIncomeTypes[i];
                    $scope.getCollections();
                    return;
                }
            }
            
            for(var i = 0; i < $scope.allExpenseTypes.length; i++)
            {
                if($scope.allExpenseTypes[i].type_id === $scope.selectedTypeId)
                {
                    $scope.selectedType = $scope.allExpenseTypes[i];
                    $scope.getCollections();
                    return;
                }
            }
        };
                
        
        $scope.getCollections = function()
        {
            $scope.showWaitForm = true;
            $scope.collections = [];
            
            if($scope.selectedClass > 0 && $scope.selectedTypeId > 0 && $scope.selectedYear)
            {
                var url = $scope.siteURL + 'collections/json_get_collections';
                url += '/' + $scope.selectedClass + '/' + $scope.selectedYear.replace("/", "and");
                url += '/' + $scope.selectedTypeId;
                $http.get(url).success(function(result)
                {
                    $scope.showWaitForm = false;
                    $scope.collections =  result;

                    $scope.collectionsEmpty = result.length === 0;

                }).error(function (data, status, headers, config) 
                {
                    $scope.showWaitForm = false;
                });
            }
            else
            {
                $scope.collectionsEmpty = true;
                $scope.showWaitForm = false;
            }
        };
        
        $scope.showCollection = function(col)
        {
            $scope.showWaitForm = true;
            $scope.currentCollection = [];
            if(col.collection_id > 0)
            {
                var collectionUrl = $scope.siteURL + 'collections/json_get_collection_detail_by_id';
                collectionUrl += '/' + col.collection_id;
                $http.get(collectionUrl).success(function(result)
                {
                    $scope.showWaitForm = false;
                    $scope.currentCollection = result;                    
                    $scope.calculateTotalFees();
                });
            }
            else
            {
                var url = $scope.siteURL + 'collections/json_create_new_collection';
                url += "/" + col.student_id + "/" 
                    + $scope.selectedClass + "/" 
                    + $scope.selectedYear.replace("/", "and") + "/"
                    + $scope.selectedTypeId;
                
                $http.get(url).success(function(result)
                {
                    $scope.showWaitForm = false;
                    $scope.currentCollection =  result;
                });
            }
            
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
        };
        
        
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
        
        
            
        $scope.removeFee = function(feeToRemove)
        {
           $scope.currentCollection.fees.splice($scope.currentCollection.fees.indexOf(feeToRemove), 1);
           $scope.calculateTotalFees();
        };
        
        
        $scope.deleteCollection = function()
        {
            $scope.showWaitForm = true;
            $scope.canDelete = false;
            var url = $scope.siteURL + 'collections/delete_collection/';
            url += '/';
            url += $scope.currentCollection.collection_id;
            
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.getCollections();
            });
        };
        
        $scope.saveCurrentCollection = function()
        {
            $scope.showWaitForm = true;
            var url = $scope.siteURL + 'collections/save_collection_json';
            
            $http({
                method: 'POST',
                url: url,
                data: angular.toJson($scope.currentCollection)
                })
                .success(function (data, status, headers, config) 
                {
                    $scope.getCollections();
                })
        };
        
        
        $scope.getOtherCollections = function()
        {
            $scope.waitFormMessage = "Loading transactions..."
            $scope.showWaitForm = true;
            $scope.otherCollections = [];
            var url = $scope.siteURL + 'collections/get_other_transactions/';
            url += $scope.selectedOtherYear.replace('/', 'and');
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.otherCollections = result;
                $scope.noOtherTransactions = result.length === 0;
            })
            .error(function (data, status, headers, config) 
            {
                $scope.showWaitForm = false;
            });
        };
        
        $scope.setCurrentOtherTransaction = function(col){
            $scope.currentOtherTransaction = col;
        };
        
        $scope.createNewOtherCollection = function(){
            $scope.newTransaction = {
                date_recorded: '29 Nov 2016',
                recorded_by: $scope.currentUser.full_name,
                recorded_by_user_id:$scope.currentUser.user_id,
            };
            
            $scope.newTransaction.date_recorded = '<?php echo get_current_date_plain(); ?>';
        };
        
        $scope.saveNewTransaction = function(newTransaction){
            
            $scope.waitFormMessage = "Saving"
            $scope.showWaitForm = true;
            
            var url = $scope.siteURL + 'collections/save_new_transaction';
            
             $http({
                method: 'POST',
                url: url,
                data: angular.toJson(newTransaction)
                })
                .success(function (data, status, headers, config) 
                {
                    console.log(data);
                    $scope.loadAllAcademicYears();
                    $scope.loadNonStudentTypes();
                    $scope.getOtherCollections();
                }).error(function (data, status, headers, config) 
                {
                    condole.log(data);
                    $scope.showWaitForm = false;
                });
        };
        $scope.deleteTransaction = function(transaction){
            $scope.currentTransactionDeletable = false;
            $scope.waitFormMessage = "deleting..."
            $scope.showWaitForm = true;
            
            var url = $scope.siteURL + 'collections/delete_collection/' + transaction.collection_id;

            var index = $scope.otherCollections.indexOf(transaction);
            $scope.otherCollections.splice(index, 1);
            
            $http.get(url).success(function()
            {
                $scope.showWaitForm = false;
            })
            .error(function (data, status, headers, config) 
            {
                $scope.showWaitForm = false;
            });
        };
        
        
        $scope.recordNewTransaction = function(){
            var currentDate = '<?php echo get_current_date_plain(); ?>';
            $scope.newTransaction = {transaction_date: currentDate};
            $scope.recordingNewTransaction = true;
        };
        
        $scope.allowOnlyNumbers = function()
        {
            if(isNaN($scope.newTransaction.Amount))
            {
                $scope.newTransaction.Amount = $scope.newTransaction.Amount.slice(0, -1);
            }
        };
        
        $scope.loadBankTransactions = function(){
            $scope.waitFormMessage = "Loading bank transactions..."
            $scope.showWaitForm = true;
            $scope.bankTransactions = [];
            var url = $scope.siteURL + 'bank_transactions/json_get_all/';
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.bankTransactions = result;
            })
            .error(function (data, status, headers, config) 
            {
                $scope.showWaitForm = false;
            });  
        };
        
        $scope.saveBankTransaction = function(transaction){
            
            $scope.waitFormMessage = "Saving"
            $scope.showWaitForm = true;
            
            var url = $scope.siteURL + 'bank_transactions/json_save_transaction';
            console.log(url)
             $http({
                method: 'POST',
                url: url,
                data: angular.toJson(transaction)
                })
                .success(function (data, status, headers, config) 
                {
                    $scope.showWaitForm = true;
                    $scope.loadBankTransactions();
                }).error(function (data, status, headers, config) 
                {
                    console.log(data);
                    $scope.showWaitForm = false;
                });
        };
        
        $scope.setCurrentBankTransaction = function(transaction){
            $scope.currentBankTransaction = transaction;
        };
        
        $scope.deleteBankTransaction = function(transaction){
            $scope.currentTransactionDeletable = false;
            $scope.waitFormMessage = "deleting..."
            $scope.showWaitForm = true;
            
            var url = $scope.siteURL + 'bank_transactions/delete/' + transaction.transaction_id;
            var index = $scope.bankTransactions.indexOf(transaction);
            $scope.bankTransactions.splice(index, 1);
            
            $http.get(url).success(function()
            {
                $scope.showWaitForm = false;
                $scope.loadBankTransactions();
            })
            .error(function (data, status, headers, config) 
            {
                $scope.showWaitForm = false;
            });
        };
})
})();
    
</script>
