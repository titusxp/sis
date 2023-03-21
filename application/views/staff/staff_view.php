<div  ng-controller="StaffController as staffController">
    
    <ul class="nav nav-pills" ng-init="setSection('staff')">
        <li ng-class="{active:section==='staff'}"><a href="#" ng-click="setSection('staff')">Staff</a></li>
        <li ng-class="{active:section==='salaries'}"><a href="#" ng-click="setSection('salaries')">Salary Payments Records</a></li>
    </ul>
   <br/>
    
<!--    All Staff section-->
    <div ng-show = "isCurrentSection('staff')">
        
        <form name="filterForm" class="form-inline">
            <input type="text" placeholder="search..."   class="form-control input-sm" 
                   ng-model="staffSearchKeyword" ng-change="searchStaffByKeyWord()"  ng-model-options='{ debounce: 1000 }'/>        
            <button type="button" class="btn btn-info btn-xs" ng-click="searchStaffByKeyWord()">
                Refresh
            </button>        
            <button type="button" class="btn btn-primary btn-xs" 
                    ng-click="createNewStaff()" 
                data-toggle="modal" data-target="#viewStaff">
                New Staff
            </button>
        </form>
        

        <br/><?php echo_wait_form()?><br>
        
        <table ng-hide="showWaitForm" class="table table-hover table-bordered table-condensed table-striped table-responsive">
            <thead>
                <th>Name</th>
                <th>Role</th>
                <th>Gender</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th> </th>
            </thead>
            <tr ng-repeat="staff in AllStaff">
                <td>{{staff.staff_name}}</td>
                <td>{{staff.staff_role}}</td>
                <td>{{staff.gender}}</td>
                <td>{{staff.phone_number}}</td>
                <td>{{staff.email}}</td>
                <td>
                    <button type="button" class="btn btn-success btn-xs" 
                            ng-click="showStaff(staff.staff_id)" style="width:100%;"
                        data-toggle="modal" data-target="#viewStaff">
                        View
                    </button>
                </td>
            </tr>
        </table>
    </div>
    
<!--Salaries section-->
    <div ng-show = "isCurrentSection('salaries')">
        <form name="filterForm" class="form-inline">
            <label>Academic Year:</label>
            <select class="form-control input-sm" ng-model="selectedYear" ng-change="getPayroll()" 
                    style="width:150px;">
                <option ng-repeat="year in allAcademicYears" value="{{year.year_value}}">
                    {{year.year_name}}
                </option>
            </select>
            
            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" 
                    ng-click="createNewSalary()" data-target="#newSalary">
                New Salary
            </button>
            
            <button type="button" class="btn btn-info btn-xs" ng-click="getPayroll()">
                Refresh
            </button>
            
        </form>
        <br />
        
        <?php echo_wait_form()?><br>
        
         <table ng-hide="showWaitForm" class="table table-hover table-bordered table-condensed table-striped table-responsive">
            <thead>
                <th>Staff</th>
                <th>August</th>
                <th>September</th>
                <th>October</th>
                <th>November</th>
                <th>December</th>
                <th>January</th>
                <th>February</th>
                <th>March</th>
                <th>April</th>
                <th>May</th>
                <th>June</th>
                <th>July</th>
                <th>Total per year</th>
            </thead>
            <tr ng-repeat="prol in Payroll">
                <td>{{prol.staff_name}}</td>
                <td>{{prol.August}}</td>
                <td>{{prol.September}}</td>
                <td>{{prol.October}}</td>
                <td>{{prol.November}}</td>
                <td>{{prol.December}}</td>
                <td>{{prol.January}}</td>
                <td>{{prol.February}}</td>
                <td>{{prol.March}}</td>
                <td>{{prol.April}}</td>
                <td>{{prol.May}}</td>
                <td>{{prol.June}}</td>
                <td>{{prol.July}}</td>
                <td style="background-color: #222; color:white">{{prol.yearly_total}}</td>
            </tr>
            <tr style="background-color: black; color:white">
                <td>Total per month</td>
                <td>{{TotalPayroll[8].toLocaleString()}}</td>
                <td>{{TotalPayroll[9].toLocaleString()}}</td>
                <td>{{TotalPayroll[10].toLocaleString()}}</td>
                <td>{{TotalPayroll[11].toLocaleString()}}</td>
                <td>{{TotalPayroll[12].toLocaleString()}}</td>
                <td>{{TotalPayroll[1].toLocaleString()}}</td>
                <td>{{TotalPayroll[2].toLocaleString()}}</td>
                <td>{{TotalPayroll[3].toLocaleString()}}</td>
                <td>{{TotalPayroll[4].toLocaleString()}}</td>
                <td>{{TotalPayroll[5].toLocaleString()}}</td>
                <td>{{TotalPayroll[6].toLocaleString()}}</td>
                <td>{{TotalPayroll[7].toLocaleString()}}</td>
                <td>{{TotalPayroll[13].toLocaleString()}}</td>
            </tr>
        </table>
        
    </div>
   
  
<!--   view staff-->
    <div id="viewStaff" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" ng-show="showWaitForm">
                       <?php echo_wait_form()?><br>                  
                </div>
                
                <div class="modal-body" ng-hide="showWaitForm">
                    <button type="button" class="close" data-dismiss="modal">&times</button>
                    
                    <?php echo_wait_form()?><br>
                        
                    <ul class="nav nav-pills" ng-init="viewStaffSection = 'staff'">
                        <li ng-class="{active:viewStaffSection==='staff'}"><a href="#" ng-click="viewStaffSection = 'staff'">Staff Info</a></li>
                        <li ng-class="{active:viewStaffSection==='salaries'}"><a href="#" ng-click="viewStaffSection = 'salaries'">Salaries for {{currentStaff.staff_name}}</a></li>
                    </ul>
                    
                    <br/>
                    <div class="panel panel-primary" ng-show="viewStaffSection === 'staff'">
                        <div class="panel-heading">
                            <h3 class="panel-title">Staff Info</h3>
                        </div>
                        <div class="panel-body"> 
                            <form name="staffInfoForm" class="form form-inline">
                                <label>Name
                                    <input type="text" ng-model="currentStaff.staff_name" class="form-control"/>
                                </label>

                                <label>Role
                                    <input type="text" ng-model="currentStaff.staff_role" class="form-control"/>
                                </label>

                                <label>Base Salary
                                    <input type="text" ng-model="currentStaff.salary" class="form-control"/>
                                </label>
                                
                                <label>Gender 
                                    <select  ng-model="currentStaff.gender" class="form-control">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </label>

<!--                                <label>Date of Birth 
                                    <input type="text" ng-model="currentStaff.date_of_birth" class="form-control" id="date_field"/>
                                </label>-->

                                <label>Email
                                    <input type="text" ng-model="currentStaff.email" class="form-control" placeholder="optional" />
                                </label>

                                <label>Phone Number
                                    <input type="text" ng-model="currentStaff.phone_number" class="form-control"  placeholder="optional" />
                                </label>
                            </form>
                        </div>
                    </div>
                                     
                    
                    <div class="panel panel-primary" ng-show="viewStaffSection === 'salaries'">
                        <div class="panel-heading">
                            <h3 class="panel-title">Salaries for {{currentStaff.staff_name}}</h3>
                        </div>
                        <div class="panel-body"> 
                            
                            <label>Academic Year:</label>
                            <select class="form-control input-sm" ng-model="selectedYear" ng-change="getCurrentStaffSalaries()" 
                                    style="width:150px;">
                                <option ng-repeat="year in allAcademicYears" value="{{year.year_value}}">
                                    {{year.year_name}}
                                </option>
                            </select>
                            
                            <table class="table table-hover table-bordered table-condensed table-striped table-responsive">
                                <thead>
                                    <th>Month</th>
                                    <th>Amount Paid</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="salary in currentStaffSalaries">
                                        <td>{{salary.month}}</td>
                                        <td>{{salary.amount_paid}}</td>
                                        <td style="width:100px;">
                                            <button type="button" class="btn btn-danger btn-xs" 
                                                    ng-click="deleteSalaryButtonClicked(salary.collection_id)" style="width:100%;">
                                                Delete
                                            </button>
                                            <div ng-show="isClickedSalary(salary.collection_id)">
                                                are you sure?
                                                <a  href="#" ng-click="deleteSalaryCollection(salary.collection_id)">Yes</a>
                                                 | 
                                                <a  href="#" ng-click="clearCurrentSalary()">No</a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </div>
                    </div>
                    
                    <button type="submit" ng-click="saveCurrentStaff()" 
                            class="btn btn-xs btn-success" data-dismiss="modal">
                        Save All Changes
                    </button>
                    
                </div>
                <div class="modal-footer"></div>
            </div>   
        </div>   
    </div>   
   
  
<!--   new Salary-->
    <div id="newSalary" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" ng-show="showWaitForm">
                       <?php echo_wait_form()?><br>                  
                </div>
                
                <div class="modal-body" ng-hide="showWaitForm">
                    <button type="button" class="close" data-dismiss="modal">&times</button>
                    <br/>
                    
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">New Salary Payment</h3>
                        </div>
                        <div class="panel-body"> 
                            <form name="salaryForm" novalidate>
                                <label>Staff                                    
                                    <select ng-model="newSalaryPayment.staff_id" class="form-control"
                                            ng-change="authenticateNewSalaryForm()" required>
                                        <option ng-repeat="staff in AllStaff" value="{{staff.staff_id}}">{{staff.staff_name}}</option>
                                    </select>
                                </label>

                                <label>Month
                                    <select ng-model="newSalaryPayment.salaryMonthIndex"  class="form-control" 
                                              ng-change="authenticateNewSalaryForm()" required>
                                        <option ng-repeat="month in allMonths" value="{{month.index}}">{{month.month}}</option>
                                    </select>
                                </label>
                                
                                <label>Academic Year
                                    <select ng-model="newSalaryPayment.academic_year" class="form-control" 
                                              ng-change="authenticateNewSalaryForm()"required>
                                        <option ng-repeat="year in allAcademicYears" value="{{year.year_value}}">{{year.year_name}}</option>
                                    </select>
                                </label>
                                
                                <label>Amount Paid 
                                    <input type="text" ng-model="newSalaryPayment.amount" ng-keypress="allowOnlyNumbers()" 
                                       class="form-control"/>
                                </label>
                                
                                <div ng-show="showCheckingMessage" class="alert alert-info">
                                    <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
                                    Checking if a payment already exists for the selected month...
                                </div>
                                
                                <div ng-show ="salaryForm.$valid && salaryExistenceIndex > 0" class="alert alert-danger">
                                    There is already a salary payment record for the selected staff, academic year and month
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <button type="submit" ng-click="saveCurrentSalary()"ng-show ="salaryExistenceIndex < 0 && newSalaryPayment.amount>0"  
                            class="btn btn-group-sm btn-success" data-dismiss="modal">
                        Save 
                    </button>
                </div>
                <div class="modal-footer"></div>
            </div>   
        </div>   
    </div>    
</div>