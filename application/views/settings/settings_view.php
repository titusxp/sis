<div  ng-controller="settingsController as settingsCtrler" ng-init="getCollections()">
    
    <ul class="nav nav-pills" ng-init="setSection('collection_types')">
        <li ng-class="{active:type==='collection_types'}"><a href="#" ng-click="setSection('collection_types')">Collection Types</a></li>
        <li ng-class="{active:type==='expense_types'}"><a href="#" ng-click="setSection('expense_types')">Expense Types</a></li>
    </ul>
    <br/>
    
    <button class="btn btn-group btn-sm" ng-click="createNewCollectionType()"
                            data-toggle="modal" data-target="#viewCollectionType">
        Create New
    </button>
    
    <br/><?php echo_wait_form()?><br>
    
    <table ng-hide="showWaitForm" class="table table-hover table-bordered table-condensed table-striped table-responsive">
        <thead>
        <th>{{type_name}}</th>
        <th>Is Student Related Type</th>
        <th></th>

        </thead>
        <tbody>
            <tr ng-repeat="ctype in collectionTypes">
                <td>{{ctype.type_name}}</td>
                <td>
                    {{ctype.is_not_student_related > 0? 'NO': 'YES'}}
                </td>
                <td>
                    <button class="btn btn-xs btn-success" ng-click="loadCurrentCollectionType(ctype)"
                            data-toggle="modal" data-target="#viewCollectionType" ng-hide="ctype.is_system_type > 0"
                            style="width: 100%">
                        View
                    </button>
                    <span ng-show="ctype.is_system_type > 0">
                        [Not Editable]
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    
    
    
    
<!--    viewCollectionType-->
    <div id="viewCollectionType" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" ng-show="showWaitForm">
                       <?php echo_wait_form()?><br>                  
                </div>
               
                <div class="modal-body" ng-hide="showWaitForm">
                    
                    <button type="button" class="close" data-dismiss="modal">&times</button>
                    <br/><br/>
                    <form style="background-color: #eee; padding: 10px">
                        <label>Type Name:
                            <input type="text" ng-model="currentCollectionType.type_name"/>
                        </label>
                        <label ng-hide="currentCollectionType.is_different_cost_per_class">Cost: 
                            <input type="text" ng-model="currentCollectionType.cost" 
                                   ng-keypress="allowOnlyNumbers($event)" />
                        </label>
                        <br/>
                        <label> 
                            <input type="checkbox" ng-model="currentCollectionType.is_student_related"
                                   ng-change="toggle_is_student_related()" />
                            This is a student related type
                        </label>
                        <br />
                        <label> 
                            <input type="checkbox" ng-model="currentCollectionType.is_different_cost_per_class"/>
                            This type has different costs per class
                        </label>
                    </form>
                    <br>
                    <div ng-show="currentCollectionType.is_different_cost_per_class">
                        <table class="table table-hover table-bordered table-condensed table-striped table-responsive">
                            <thead>
                            <th>Class</th>
                            <th>Cost</th>
                            <th></th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="ctypecost in currentCollectionType.collection_type_costs">
                                    <td>{{ctypecost.class_name}}</td>
                                    <td>{{ctypecost.cost}}</td>
                                    <td style="width:100px;">
                                            <button type="button" class="btn btn-danger btn-xs" 
                                                    ng-click="deleteCollectionTypeButtonClicked(ctypecost.type_cost_id)" style="width:100%;">
                                                Delete
                                            </button>
                                            <div ng-show="isClickedCollectionType(ctypecost.type_cost_id)">
                                                are you sure?
                                                <a  href="#" ng-click="deleteCollectionTypeCost(ctypecost)">Yes</a>
                                                 | 
                                                <a  href="#" ng-click="clearCurrentCollectionType()">No</a>
                                            </div>
                                        </td>
                                </tr>
                                <tr ng-show="canAddNewCollectionTypeCost">
                                    <td>
                                        <select class="form-control input-sm" ng-model="selectedClass">
                                            <option value="" disabled selected>select a class</option>
                                            <option ng-repeat="class in EditedClasses" value="{{class.class_id}}">
                                                {{class.class_name}}
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" ng-model="newCost" ng-keypress="allowOnlyNumbers($event)" />
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-group btn-xs" ng-show="selectedClass > 0 && newCost > 0"
                                                ng-click="createCollectionTypeCost()" style="width:100%;">
                                            Record
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <a href="#" ng-click="canAddNewCollectionTypeCost = !canAddNewCollectionTypeCost">New Class Cost</a>
                    
                    </div>
                    
                    <button class="btn btn-success" ng-click="saveCurrentCollectionType()"
                            data-dismiss="modal" ng-disabled="currentCollectionType.is_system_type > 0">
                        Save Changes
                    </button>
                    
                    <button class="btn btn-danger" ng-click="showDelete = true" 
                            ng-show="currentCollectionType.type_id > 0">
                        Delete
                    </button>
                    <span ng-show="showDelete === true">
                        Are you sure? 
                        <button class="btn btn-xs btn-danger" ng-click="deleteCurrentCollectionType()"
                                data-dismiss="modal">
                            Yes
                        </button> 
                        <button class="btn btn-xs btn-info" ng-click="showDelete = false">
                            No
                        </button>
                    </span>
                    
                </div>
                
                <div class="modal-footer"></div>
            </div>
        </div>        
    </div>
    
</div>




