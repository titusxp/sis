<div  ng-controller="SearchFeesController as search" ng-init="getCollections()">
    
    <ul class="nav nav-pills" ng-init="setSection('enrollment')">
        <li ng-class="{active:section==='enrollment'}"><a href="#" ng-click="setSection('enrollment')">Enrollments</a></li>
        <li ng-class="{active:section==='student'}"><a href="#" ng-click="setSection('student')">Students List</a></li>
    </ul>
    <br/>
    
    <!--enrollment-->
    <div  ng-show="section==='enrollment'">
    <form name="filterForm" class="form-inline" ng-submit="getCollections()">

        <label>Class: </label>
        <select class="form-control input-sm" ng-model="selectedClass" ng-change="getCollections()">
            <option ng-repeat="class in allClasses" value="{{class.class_id}}">
                {{class.class_name}}
            </option>
        </select>


        <label>Academic Year: </label>
        <select class="form-control input-sm" ng-model="selectedYear" ng-change="getCollections()">
            <option ng-repeat="year in allAcademicYears" value="{{year.year_value}}">
                {{year.year_name}}
            </option>
        </select>

        <input type="submit" value="Search" ng-click="getCollections()" class="btn btn-xs btn-primary"/>

        <br/><br/>
    
    </form>
    <?php echo_wait_form()?><br>
    <table ng-show="!showWaitForm" class="table table-hover table-bordered table-condensed table-striped table-responsive">
        <thead>
        <th>Student</th>
        <th>Class</th>
        <th>Academic Year</th>
        <th>Class Fees</th>
        <th>Total Paid</th>
        <th>Deductions</th>
        <th>Amount Owed</th>
        <th></th>
        </thead>        
        <tr ng-show="enrollmentsEmpty">
            <td colspan="8">No enrollments in the selected class and academic year</td>
        </tr>
        <tr ng-repeat="col in collections">
            <td>{{col.student_name}}</td>
            <td>{{col.class_name}}</td>
            <td>{{col.academic_year}}</td>
            <td>{{col.amount_due}}</td>
            <td>{{col.amount_paid}}</td>
            <td>{{col.deductions}}</td>
            <td>{{col.amount_owed}}</td>
            <td>
                <button type="button" class="btn btn-success btn-xs" 
                        ng-click="showCollection(col.collection_id)" style="width:100%;"
                    data-toggle="modal" data-target="#viewCollection">
                    View
                </button>
            </td>
        </tr>
    </table>
    
    </div>
 
    <!--student-->
    <div  ng-show="section==='student'">
        <form name="filterForm" class="form-inline">
            <input type="text"  class="form-control input-sm" placeholder="search..." 
                   ng-model="searchStudentKeyword" ng-change="getStudentsByKeyword()" ng-model-options='{ debounce: 1000 }'/>
            <button ng-click="getStudentsByKeyword()" class="btn btn-info btn-xs" >
                Refresh
            </button>
            &nbsp;
            <button type="button" class="btn btn-primary btn-xs" 
                        ng-click="showStudent(0)"
                    data-toggle="modal" data-target="#addEditStudent">
                    New Student
            </button>
        </form>
        
    <?php echo_wait_form()?><br>
    
        <table ng-hide="showWaitForm" class="table table-hover table-bordered table-condensed table-striped table-responsive">
        <thead>
        <th>Student Number</th>
        <th>Name</th>
        <th>Gender</th>
        <th>Date Of Birth</th>
        <th>Guardian</th>
        <th>Contact</th>
        <th></th>
        </thead>
        <tr ng-repeat="stu in students">
            <td>{{stu.student_number}}</td>
            <td>{{stu.student_name}}</td>
            <td>{{stu.gender}}</td>
            <td>{{stu.date_of_birth}}</td>
            <td>{{stu.guardian_name}}</td>
            <td>{{stu.guardian_phone_number}}</td>
            <td>
                <button type="button" class="btn btn-success btn-xs" 
                        ng-click="showStudent(stu.student_id)" style="width:100%; margin:0px;"
                    data-toggle="modal" data-target="#addEditStudent">
                    View
                </button>
            </td>
        </tr>
    </table>
    </div>
 
   <!--addEditStudent--> 
   <div id="addEditStudent" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-header" ng-show="showWaitForm">
                       <?php echo_wait_form()?><br>                  
                </div>
                
                <div class="modal-body" ng-hide="showWaitForm">
                    
                    <button type="button" class="close" data-dismiss="modal">&times</button>
                    <br/>
    
                    <ul class="nav nav-pills" ng-init="student_section='student_info'">
                        <li ng-class="{active:student_section==='student_info'}"><a href="#" ng-click="student_section = 'student_info'">Student Info</a></li>
                        <li ng-class="{active:student_section==='student_enrollments'}" ng-show="currentStudent.student_id > 0">
                            <a href="#" ng-click="student_section = 'student_enrollments'">Enrollments</a>
                        </li>
                    </ul>
                    <br/>

                    <div class="panel panel-primary" ng-show="student_section==='student_info'">
                        <div class="panel-heading">
                            <h3 class="panel-title">student Info</h3>
                        </div>

                        <div class="panel-body">
                            <table>
                                <tr>
                                    <td>Name</td>
                                    <td>
                                        <input type="text" ng-model="currentStudent.student_name"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Date of Birth</td>
                                    <td>
                                        <input type="text" ng-model="currentStudent.date_of_birth" id="date_field"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td>
                                        <select  ng-model="currentStudent.gender">
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Guardian</td>
                                    <td>
                                        <input type="text" ng-model="currentStudent.guardian_name" 
                                       placeholder="optional"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Guardian Contact</td>
                                    <td>
                                        <input type="text" ng-model="currentStudent.guardian_phone_number" 
                                       placeholder="optional"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="panel panel-primary" ng-show="student_section==='student_enrollments'">
                        <div class="panel-heading">
                            <h3 class="panel-title">Student Enrollments</h3>
                        </div>

                        <div class="panel-body" ng-init="canAddNewCollection = false"> 
                            <a href ng-click="canAddNewCollection = !canAddNewCollection">New Enrollment</a>
                            
                            <form class="form-inline" ng-show="canAddNewCollection">
                                <select class="form-control input-sm" ng-model="newAcademicYear"
                                        placeholder="new academic year">
                                    <option value="" disabled selected>select an academic year</option>
                                    <option ng-repeat="year in allAcademicYears" value="{{year.year_value}}">
                                        {{year.year_name}}
                                    </option>
                                </select>
                                <select class="form-control input-sm" ng-model="newClass"
                                        placeholder="new class">
                                    <option value="" disabled selected>select a class</option>
                                    <option ng-repeat="class in allClasses" value="{{class.class_id}}">
                                        {{class.class_name}}
                                    </option>
                                </select>
                                <input type="submit" class="btn btn-xs btn-primary" ng-show='newClass > 0 && newAcademicYear != ""'
                                        ng-click="createCollection(newClass, newAcademicYear, currentStudent.student_id)" 
                                        data-toggle="modal" data-target="#viewCollection" 
                                    value = "Create"/>                                 
                            </form>
                            
                            <table class="table table-hover table-bordered table-condensed table-striped table-responsive">
                                <thead>
                                <th>Year</th>
                                <th>Class</th>
                                <th></th>
                                </thead>
                                <tr ng-repeat="en in currentStudent.enrollments">
                                    <td>{{en.academic_year}}</td>
                                    <td>{{en.class_name}}</td>
                                    <td>                                        
                                        <button type="button" class="btn btn-success btn-xs" 
                                                ng-click="showCollection(en.collection_id)" style="width:100%;"
                                            data-toggle="modal" data-target="#viewCollection">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            </table>
                    
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" ng-click="saveCurrentStudent()" 
                                class="btn btn-xs btn-success" data-dismiss="modal">
                            Save All Changes
                        </button>
                    </div>
                </div>
            </div> 
        </div>
   </div>
  
   
   <!--viewCollection-->
    <div id="viewCollection" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" ng-show="showWaitForm">
                       <?php echo_wait_form()?><br>                  
                </div>
                
                <div class="modal-body" ng-hide="showWaitForm">
                    
                    <!--<form method="post" >-->
                        <button type="button" class="close" data-dismiss="modal">&times</button>
                        
                        
                        <ul class="nav nav-pills" ng-init="tab='basic'">
                            <li ng-class="{active:tab==='basic'}"><a href="#" ng-click="tab = 'basic'">Basic Info</a></li>
                            <li ng-class="{active:tab==='fees'}"><a href="#" ng-click="tab = 'fees'">Fee Payments</a></li>
                            <li ng-class="{active:tab==='deductions'}"><a href="#" ng-click="tab = 'deductions'">Deductions</a></li>
                        </ul>
                        <br/>
                        
                        <div class="panel panel-primary" ng-show="tab==='basic'">
                            <div class="panel-heading">
                                <h3 class="panel-title">Basic Info</h3>
                            </div>

                            <div class="panel-body"> 
                                <table class="table table-hover table-bordered table-condensed">
                                    <tr>
                                        <td>Student</td>
                                        <td>{{currentCollection.student_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Class</td>
                                        <td>{{currentCollection.class_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Academic Year</td>
                                        <td>{{currentCollection.academic_year}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-primary" ng-show="tab==='fees'">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Fee Payments</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-hover table-bordered table-condensed table-striped table-responsive">
                                        <a href="#" ng-model="canAddNewfee" ng-click="canAddNewfee=!canAddNewfee">Add Payment</a><br>
                                 
                                        <input type="text" ng-model="newFee" ng-show="canAddNewfee"  ng-change="allowOnlyNumbers()"/>
                                        <button ng-click="addFee()" ng-show="canAddNewfee">Add</button>
                                
                                    <thead class="thead-default"  style="font: bolder">
                                        <td>Date</td>
                                        <td>Recorded By</td>
                                        <td>Amount Paid</td>
                                        <td> </td>
                                    </thead>
                                    <tr ng-repeat="fee in currentCollection.fees">
                                        <td>{{fee.date_recorded_iso|date:'dd MMM yyyy, hh:mm'}}</td>
                                        <td>{{fee.recorded_by}}</td>
                                        <td class="text-right">{{fee.amount}} CFA</td>
                                        <td>
                                            <button type="button" class="close"  ng-click="removeFee(fee)">&times</button>
                                        </td>
                                    </tr>
                                    <tr style="background-color: #000; color: #fff">
                                        <td></td>
                                        <td>Total Paid</td>
                                        <td class="text-right">{{currentCollection.totalFeesPaid}} CFA</td>
                                        <td></td>
                                    </tr>
                                    
                                </table>
                                    
                                </div>
                            </div>


                                    
                            <div class="panel panel-primary" ng-show="tab==='deductions'">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Deductions</h3>
                                </div>

                                <div class="panel-body">
                                    <table class="table table-hover table-bordered table-condensed table-striped table-responsive">
                                            <a href="#" ng-model="canAddNewDeduction" ng-click="canAddNewDeduction=!canAddNewDeduction">
                                                Add Deduction</a><br>
                                        <tr ng-show="canAddNewDeduction">
                                        
                                        <form ng-show="canAddNewDeduction">
                                            <input type="text" ng-model="newDeductionAmount" ng-show="canAddNewDeduction" placeholder="Deduction Amount"  ng-change="allowOnlyNumbers()"/>
                                            <input type="text" ng-model="newDeductionReason" ng-show="canAddNewDeduction" placeholder="Reason for Deduction"/>
                                            <input type='submit' ng-click="addDeduction(); canAddNewDeduction = !canAddNewDeduction"ng-show="canAddNewDeduction" value='Add'/>
                                        </form>
                                            
                                        </tr>
                                        <thead class="thead-default" style="font: bolder">
                                            <td>Date</td>
                                            <td>Deducted By</td>
                                            <td>Reason</td>
                                            <td>Amount</td>
                                            <td></td>
                                        </thead>
                                        <tr ng-repeat="ded in currentCollection.AllDeductions">
                                            <td>{{ded.date_recorded_iso|date:'dd MMM yyyy, hh:mm'}}</td>
                                            <td>{{ded.recorded_by}}</td>
                                            <td>{{ded.description}}</td>
                                            <td class="text-right">{{ded.amount}} CFA</td>
                                            <td>
                                                <button type="button" class="close"  ng-click="removeDeduction(ded)">&times</button>
                                            </td>
                                        </tr>
                                        <tr style="background-color: #000; color: #fff">
                                            <td></td>
                                            <td></td>
                                            <td>Total Deductions</td>
                                            <td class="text-right">{{currentCollection.totalDeductions}} CFA</td>
                                            <td></td>
                                        </tr>

                                    </table>
                                </div>
                            </div> 
                    <!--</form>-->
                    Class Fees = {{currentCollection.amount_due}} ,
                    Total Paid = {{currentCollection.totalFeesPaid}} ,
                    Deductions = {{currentCollection.totalDeductions}} ,
                    Amount Owed = {{currentCollection.amount_due - currentCollection.totalFeesPaid - currentCollection.totalDeductions}} ,

                    <hr>
                    <button type="submit" ng-click="saveCurrentCollection()" 
                            class="btn btn-xs btn-success" data-dismiss="modal">
                        Save All Changes</button>
                    
                    <button type="submit" ng-click="showStudent(currentCollection.student_id)"
                            class="btn btn-xs btn-info"  data-dismiss="modal" 
                                 data-toggle="modal" data-target="#addEditStudent"
                            ng-show="currentCollection.collection_id > 0">
                        View/Edit Student details</button>
                    
                    <button type="submit" ng-click="canDelete = true" 
                            class="btn btn-xs btn-danger" >
                        Delete this Enrollment</button>
                    
                    <div class="alert alert-danger" ng-show="canDelete===true">
                        This will not only delete 
                        this enrollment but also any records of fee payments 
                        and deductions associated with it. Continue?
                        <button class="btn btn-primary btn-xs" data-dismiss="modal" 
                                ng-click="deleteEnrollment()">
                            Yes
                        </button>
                        <button class="btn btn-danger btn-xs" ng-click="canDelete = !canDelete">
                            No
                        </button>
                    </div>
                            
                
                </div>
            </div>
            <div class="modal-footer">&nbsp;</div>
        </div>
    </div>
</div>




