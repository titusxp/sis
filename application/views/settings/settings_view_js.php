<script type="text/javascript">

(function(){
angular.module('sis', [])
.controller('settingsController', function($scope, $http)
    {
        $scope.showWaitForm = true;
        $scope.selectedClass = '';
        $scope.siteURL = '<?php echo site_url() ?>' + '/';
        $scope.selectedYear = '<?php echo get_current_academic_year(); ?>';
        
        $scope.currentUser = [];
        $http.get($scope.siteURL + 'users/json_get_current_user').success(function(result)
        {
            $scope.currentUser = result;
        });
        
        $scope.allClasses = [];
        $http.get($scope.siteURL + 'global_json_repo/get_all_classes/').success(function(result)
        {
            $scope.allClasses = result;
        });
                
        $scope.allAcademicYears = [];
        $http.get($scope.siteURL + 'global_json_repo/get_all_academic_years/yes').success(function(result)
        {
            $scope.allAcademicYears = result;
        });
       
        $scope.setSection = function(thisSection)
        {
            $scope.type = thisSection;  
            $scope.collectionTypes = [];
            
            if($scope.type === 'collection_types')
            {
                $scope.isExpenseSection = 0;
                $scope.type_name = "Collection Types";
                $scope.getCollectionTypes();
            }
            
            if($scope.type === 'expense_types')
            {
                $scope.isExpenseSection = 1;
                $scope.type_name = "Expense Types";
                $scope.getCollectionTypes();
            }
        }
        
        $scope.getCollectionTypes = function()
        {
            $scope.showWaitForm = true;
            var url = $scope.siteURL + 'collection_types/json_get_all/' + $scope.isExpenseSection;
            $http.get(url).success(function(result)
            {
                $scope.showWaitForm = false;
                $scope.collectionTypes = result;
            });
        }
        
        $scope.resetNewTypeCostFields = function()
        {
            $scope.canAddNewCollectionTypeCost = false;
            $scope.newCost = 0;
            $scope.selectedClass = "";
        }
        
        
        $scope.loadCurrentCollectionType = function(collection_type)
        {
            $scope.showDelete = false;
            $scope.resetNewTypeCostFields();
            $scope.currentCollectionType = collection_type;
            $scope.updateCurrentCollectinTypeCostsIsDifferentCostPerClass();
            $scope.updateCurrentCollectinTypeCostsIsNotStudentRelated();
            $scope.updateClassList();
        }
        
        
        $scope.createNewCollectionType = function()
        {
            $scope.loadCurrentCollectionType(0);
        }

        
        $scope.updateCurrentCollectinTypeCostsIsNotStudentRelated = function()
        {
            if($scope.currentCollectionType.is_not_student_related > 0)
            {
                $scope.currentCollectionType.is_not_student_related = true;
                $scope.currentCollectionType.is_student_related = false;
            }
            else
            {                    
                $scope.currentCollectionType.is_not_student_related = false;
                $scope.currentCollectionType.is_student_related = true;
                
            }
        }  
        
        $scope.toggle_is_student_related = function()
        {
            $scope.currentCollectionType.is_not_student_related = 
                    !$scope.currentCollectionType.is_student_related;
        }
        
        $scope.updateCurrentCollectinTypeCostsIsDifferentCostPerClass = function()
        {
            if($scope.currentCollectionType.is_different_cost_per_class > 0)
            {
                $scope.currentCollectionType.is_different_cost_per_class = true;
            }
            else
            {                    
                $scope.currentCollectionType.is_different_cost_per_class = false;
            }
        }
        
        $scope.updateClassList = function()
        {
            $scope.EditedClasses = [];
            
            for(var j = 0; j < $scope.allClasses.length; j++)
            {
                var exists = false;
                for(var i = 0; i < $scope.currentCollectionType.collection_type_costs.length; i++)
                {
                    if($scope.allClasses[j].class_id === $scope.currentCollectionType.collection_type_costs[i].class_id)
                    {
                        exists = true;
                        break;
                    }
                }
                if(!exists)
                {
                    $scope.EditedClasses.push($scope.allClasses[j]);
                }
            }
            
            
        }
         
        $scope.allowOnlyNumbers = function($event)
        {
            if(isNaN(String.fromCharCode($event.keyCode)))
            {
                $event.preventDefault();
            }
        }
        
        $scope.deleteCollectionTypeButtonClicked = function(id)
        {
            $scope.deleteId = id;
        }
        
        $scope.isClickedCollectionType = function(id)
        {
            return $scope.deleteId === id;
        }
        
        $scope.deleteCollectionTypeCost = function(typeCost)
        {
            var index = $scope.currentCollectionType.collection_type_costs.indexOf(typeCost);
            $scope.currentCollectionType.collection_type_costs.splice(index, 1);
        }
        
        /*don't let the name of the function confuse you. nothing like what it actually does :)*/
        $scope.clearCurrentCollectionType = function()
        {
            $scope.deleteId = 0;
        }
        
        $scope.createCollectionTypeCost = function()
        {
            $scope.canAddNewCollectionTypeCost = false;
            var className = "";
            
            for(var i = 0; i <= $scope.EditedClasses.length; i++)
            {
                if($scope.EditedClasses[i].class_id === $scope.selectedClass)
                {
                    className = $scope.EditedClasses[i].class_name;
                    break;
                }
            }
            
            var newTypeCost = {class_id: $scope.selectedClass,
                                cost: $scope.newCost,
                                type_cost_id: "0",
                                type_id: $scope.currentCollectionType.type_id,
                                class_name: className
                            };
                            
            $scope.currentCollectionType.collection_type_costs.push(newTypeCost);
            
        }
        
        $scope.deleteCurrentCollectionType = function()
        {
            var url = $scope.siteURL + "collection_types/delete_collection_type/";
            url += $scope.currentCollectionType.type_id;
            $http.get(url).success(function(result)
            {
                $scope.collectionTypes = [];
                $scope.getCollectionTypes();
            });
        }
                
        $scope.saveCurrentCollectionType = function()
        {
            if(!$scope.currentCollectionType.type_id > 0)
            {
                $scope.currentCollectionType.is_expense = $scope.isExpenseSection;
            }
            
            var url = $scope.siteURL + "collection_types/json_save_collection_type";
            $http({
                method: 'POST',
                url: url,
                data: angular.toJson($scope.currentCollectionType)
                })
                .success(function (data, status, headers, config) {
                    $scope.getCollectionTypes($scope.currentCollectionType.is_expense);
                });
                
        }
})
})();
    
</script>