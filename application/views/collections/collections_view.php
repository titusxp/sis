<div  ng-controller="CollectionsController as search" ng-init="getCollections()">
    
     <ul class="nav nav-pills" ng-init="setSection('student_related')">
        <li ng-class="{active:section==='student_related'}">
            <a href="#" ng-click="setSection('student_related')">
                Student Related Transactions
            </a>
        </li>
        <li ng-class="{active:section==='other'}">
            <a href="#" ng-click="setSection('other')">
                Other Transactions
            </a>
        </li>
        </li>
        <li ng-class="{active:section==='bank'}">
            <a href="#" ng-click="setSection('bank')">
                Bank Transactions
            </a>
        </li>
    </ul>
    <br/>
    
    <!--student related-->
    <div ng-show="section==='student_related'">
    <form name="filterForm" class="form-inline">

        <label>Type: </label>
        <select class="form-control input-sm" ng-model="selectedTypeId" ng-change="collectionTypeChanged()">
            
            <option value="0" disabled=""> 
            </option><option value="0" disabled="">Income Types</option>
            <option ng-repeat="ctype in allIncomeTypes" value="{{ctype.type_id}}">
                {{ctype.type_name}}
            </option>
            
            <option value="0" disabled=""> </option>
            <option value="0" disabled="">Expense Types</option>
            <option ng-repeat="ctype in allExpenseTypes" value="{{ctype.type_id}}">
                {{ctype.type_name}}
            </option>
            
        </select>
        
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
        
    <table ng-hide="showWaitForm" class="table table-hover table-bordered table-condensed table-striped table-responsive">
        <thead>
        <th>Student</th>
        <th ng-hide="selectedType.is_expense > 0">Amount Due</th>
        <th>{{selectedType.is_expense > 0 ? "Amount Spent": "Total Paid"}}</th>
        <th ng-hide="selectedType.is_expense > 0">Amount Owed</th>
        <th></th>
        </thead>
        <tr ng-show="collectionsEmpty">
            <td colspan="5">No students in the selected class and academic year</td>
        </tr>
        <tr ng-repeat="col in collections">
            <td>{{col.student_name}}</td>
            <td ng-hide="selectedType.is_expense > 0">{{col.amount_due}}</td>
            <td>{{col.amount_paid}}</td>
            <td ng-hide="selectedType.is_expense > 0">{{col.amount_owed}}</td>
            <td>
                <button type="button" class="btn btn-success btn-xs" 
                        ng-click="showCollection(col)" style="width:100%;"
                    data-toggle="modal" data-target="#viewCollection">
                    View
                </button>
            </td>
        </tr>
    </table>
    
    </div>
    
    
<!-- Other transactions-->
    <div ng-show="section==='other'">
        <form name="filterForm" class="form-inline">

        <label>Academic Year: </label>
        <select class="form-control input-sm" ng-model="selectedOtherYear" ng-change="getOtherCollections()">
            <option ng-repeat="year in allAcademicYears" value="{{year.year_value}}">
                {{year.year_name}}
            </option>
        </select>
        
        <input type="submit" value="Search" ng-click="getOtherCollections()" 
               class="btn btn-xs btn-primary"  ng-disabled="!selectedOtherYear" />
        
        <button type="button" class="btn btn-success btn-xs"
                ng-click="createNewOtherCollection()" data-toggle="modal" 
                data-target="#addOtherCollection">
            New Transaction
        </button>
        
        <br/><br/>
    
    </form>
        
    <?php echo_wait_form("")?><br>
        
    <table ng-hide="showWaitForm" class="table table-hover table-bordered table-condensed table-striped table-responsive">
        <thead>
        <th>Date</th>
        <th>Transaction Type</th>
        <th>Amount</th>
        <th></th>
        </thead>
        <tr ng-show="otherCollectionsEmpty">
            <td colspan="5">No Records</td>
        </tr>
        <tr ng-repeat="col in otherCollections">
            <td>{{col.date_recorded_iso|date:'dd MMM yyyy'}}</td>
            <td >{{col.type_name}}</td>
            <td>{{col.amount}}</td>
            <td>
                <button type="button" class="btn btn-success btn-xs" 
                        ng-click="setCurrentOtherTransaction(col)" style="width:100%;"
                    data-toggle="modal" data-target="#viewOtherCollection">
                    View
                </button>
            </td>
        </tr>
        <tr>
            <td colspan="4" ng-show="noOtherTransactions">No records</td>
        </tr>
    </table>
    
    </div>    
    

<!-- Bank transactions-->
    <div ng-show="section==='bank'">
        
        <button class="btn btn-success" ng-click="recordNewTransaction()"
                 data-toggle="modal" data-target="#addBankTransaction">New Transaction</button>
        <br/><br/>
        
        <?php echo_wait_form("")?><br>
        
        <table class="table table-striped">
            <thead>
                <th>Transaction Date</th>
                <th>Transaction Type</th>
                <th>Amount</th>
                <th>Balance</th>
                <th>Recorded By</th>
                <th>Date Recorded</th>
                <th></th>
            </thead>
            <tr ng-repeat="tr in bankTransactions">
                <td>{{tr.transaction_date_formatted}}</td>
                <td>{{tr.transaction_type_full}}</td>
                <td>{{tr.amount}}</td>
                <td>{{tr.balance}}</td>
                <td>{{tr.recorded_by}}</td>
                <td>{{tr.date_recorded_formatted}}</td>
                <td>
                    <button type="button" class="btn btn-success btn-xs" 
                            ng-click="setCurrentBankTransaction(tr)" style="width:100%;"
                        data-toggle="modal" data-target="#viewBankTransaction">
                        View
                    </button>
                </td>
            </tr>
        </table>
    </div>


      <!--View Bank transaction-->
    <div id="viewBankTransaction" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times</button>
                                     
                </div>
                
                <div class="modal-body">
                    
                    <table class="table table-bordered">
                        <tr>
                            <td>Transaction Date</td>
                            <td>{{currentBankTransaction.transaction_date_formatted}}</td>
                        </tr>
                        <tr>
                            <td>Transaction Type</td>
                            <td>{{currentBankTransaction.transaction_type_full}}</td>
                        </tr>
                        <tr>
                            <td>Amount</td>
                            <td>{{currentBankTransaction.amount}}</td>
                        </tr>
                        <tr>
                            <td>Notes</td>
                            <td>{{currentBankTransaction.notes}}</td>
                        </tr>
                        <tr>
                            <td>Recorded by</td>
                            <td>{{currentBankTransaction.recorded_by}}</td>
                        </tr>
                        <tr>
                            <td>Date Recorded</td>
                            <td>{{currentBankTransaction.date_recorded_formatted}}</td>
                        </tr>
                    </table>
                                      
                </div>
                                
                <div class="modal-footer">
                    <a ng-click="currentBankTransactionDeletable = true">
                        Delete  &nbsp;&nbsp;
                    </a> 
                    <span ng-show="currentBankTransactionDeletable === true" style="background-color: black;color:white; margin:5px; padding:15px;">
                        Are you sure?
                        <a href="#" ng-click="deleteBankTransaction(currentBankTransaction)"z>
                            Yes
                        </a>
                        |  
                        <a href="#" ng-click="currentBankTransactionDeletable = false">
                            No
                        </a> 
                    </span>
                    <button class="btn btn-danger" class="close" data-dismiss="modal">
                        Close
                    </button>                 
                </div>
            </div>
        </div>
    </div>


     <!--add bank transaction-->
    <div id="addBankTransaction" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times</button>
                    <p ng-show="showWaitForm">
                        <?php echo_wait_form("")?>{{waitFormMessage}}
                    </p>
                    <h2>New Transaction</h2><br>                  
                </div>
                
                <div class="modal-body" ng-hide="showWaitForm">
                    <label>Transaction Type
                        <select ng-model="newTransaction.transaction_type" class="form-control">
                            <option value="-1">Withdrawal</option>
                            <option value="1">Deposit</option>
                        </select>
                    </label>
                    <label>
                        Amount 
                        <input type="text" ng-model="newTransaction.Amount" 
                               class="form-control" ng-change="allowOnlyNumbers()"/>
                    </label>
                    <label>
                        Date 
                        <input id="date_field" type="text" ng-model="newTransaction.transaction_date" 
                               class="form-control"/>
                    </label>
                    <br />
                    <label>Notes 
                        <textarea  cols="57" rows="5" class="form-control" 
                                   ng-model="newTransaction.notes">
                            
                        </textarea>
                    </label>
                </div>
                                
                <div class="modal-footer">
                    <button class="btn btn-success" class="close" 
                            ng-click="saveBankTransaction(newTransaction)" data-dismiss="modal">Save</button> 
                    <button class="btn btn-danger" class="close"data-dismiss="modal">Cancel</button>                 
                </div>
            </div>
        </div>
    </div>

   <!--add other collection-->
    <div id="addOtherCollection" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times</button>
                    <p ng-show="showWaitForm">
                        <?php echo_wait_form("")?>{{waitFormMessage}}
                    </p>
                    <h2>New Transaction</h2><br>                  
                </div>
                
                <div class="modal-body" ng-hide="showWaitForm">
                    
                    <br/><br/>
                    
                    <label>Transaction Type
                        <select ng-model="newTransaction.type_id" class="form-control-static">
                            <option value="{{type.type_id}}"ng-repeat="type in nonStudentTypes">{{type.type_name}}</option>
                            <option value="" disabled></option>
                            <option value="0">Create New</option>
                        </select>
                        <input type="text"  ng-show="newTransaction.type_id == 0" class="form-control-static"
                               ng-model="newTransaction.newOtherTransactionType" placeholder="New transaction name"/>
                    </label><br> 
                    <label>Amount
                        <input type="text" ng-model="newTransaction.amount"  class="form-control"/>
                    </label><br>  
                    <label>Notes
                        <textarea ng-model="newTransaction.notes" class="form-control"></textarea>
                    </label><br>  
                    <label> Transaction Date
                        <input type="text" ng-model="newTransaction.date_recorded" id="date_field" class="form-control"/>
                    </label><br>  
                    <label> Recorded By
                        <input type="text" ng-model="newTransaction.recorded_by" ng-disabled="true" class="form-control"/>
                    </label>                    
                </div>
                                
                <div class="modal-footer">
                    <button class="btn btn-success" class="close" 
                            ng-click="saveNewTransaction(newTransaction)" data-dismiss="modal">Save</button>                 
                </div>
            </div>
        </div>
    </div>
   
   
      <!--View other collection-->
    <div id="viewOtherCollection" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times</button>
                                     
                </div>
                
                <div class="modal-body">
                    
                    <table class="table table-bordered">
                        <tr>
                            <td>Date</td>
                            <td>{{currentOtherTransaction.date_recorded_iso|date:'dd MMM yyyy'}}</td>
                        </tr>
                        <tr>
                            <td>Transaction</td>
                            <td>{{currentOtherTransaction.type_name}}</td>
                        </tr>
                        <tr>
                            <td>Amount</td>
                            <td>{{currentOtherTransaction.amount}}</td>
                        </tr>
                        <tr>
                            <td>Recorded by</td>
                            <td>{{currentOtherTransaction.recorded_by}}</td>
                        </tr>
                        <tr>
                            <td>Notes</td>
                            <td>{{currentOtherTransaction.notes}}</td>
                        </tr>
                    </table>
                                      
                </div>
                                
                <div class="modal-footer">
                    <a ng-click="currentTransactionDeletable = true">
                        Delete  &nbsp;&nbsp;
                    </a> 
                    <span ng-show="currentTransactionDeletable === true" style="background-color: black;color:white; margin:5px; padding:15px;">
                        Are you sure?
                        <a href="#" ng-click="deleteTransaction(currentOtherTransaction)">
                            Yes
                        </a>
                        |  
                        <a href="#" ng-click="currentTransactionDeletable = false">
                            No
                        </a> 
                    </span>
                    <button class="btn btn-danger" class="close" data-dismiss="modal">
                        Close
                    </button>                 
                </div>
            </div>
        </div>
    </div>
     
   <!--add/edit collection-->
    <div id="viewCollection" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" ng-show="showWaitForm">
                       <?php echo_wait_form()?><br>                  
                </div>
                
                <div class="modal-body" ng-hide="showWaitForm">
                    <button type="button" class="close" data-dismiss="modal">&times</button>
                    <br/><br/>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Collection Info</h3>
                            </div>

                            <div class="panel-body"> 
                                <table class="table table-hover table-bordered table-condensed">
                                    <tr>
                                        <td>Type</td>
                                        <td>{{selectedType.type_name}}</td>
                                    </tr>
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

                        <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">{{selectedType.is_expense > 0 ? "Expenditure": "Payments"}}</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-hover table-bordered table-condensed">
                                        <a href="#" ng-model="canAddNewfee" ng-click="canAddNewfee=!canAddNewfee">
                                            Add
                                        </a><br>
                                 
                                        <input type="text" ng-model="newFee" ng-show="canAddNewfee"  ng-change="allowOnlyNumbers()" />
                                        <button ng-click="addFee()" ng-show="canAddNewfee">Add</button>
                                
                                    <thead class="thead-default">
                                        <td>Date</td>
                                        <td>Recorded By</td>
                                        <td>Amount Paid</td>
                                        <td></td>
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

                    <span ng-hide="selectedType.is_expense > 0">
                        Amount Due = {{currentCollection.amount_due}} ,
                        Total Paid = {{currentCollection.totalFeesPaid}} ,
                        Amount Owed = {{currentCollection.amount_due - currentCollection.totalFeesPaid - currentCollection.totalDeductions}} ,
                    </span>
                    
                    <span ng-show="selectedType.is_expense > 0">
                        Total Spent = {{currentCollection.totalFeesPaid}}
                    </span>
                    <hr>
                    <button type="submit" ng-click="saveCurrentCollection()" 
                            class="btn btn-xs btn-success" data-dismiss="modal">
                        Save All Changes</button>
                    
                    <button type="submit" ng-click="canDelete = true" 
                            ng-show="currentCollection.collection_id > 0"
                            class="btn btn-xs btn-danger" >
                        Delete this</button>
                    
                    <div class="alert alert-danger" ng-show="canDelete===true">
                        This will not only delete 
                        this entire record but also any records of payments associated with it.
                        Are you sure?
                        <button class="btn btn-primary btn-xs" data-dismiss="modal" 
                                ng-click="deleteCollection()">
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




