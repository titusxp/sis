<div  ng-controller="financeSummaryController as fnCtler">

    <?php echo_wait_form()?><br>
    
    <h4> &nbsp; Bank Account Balance: <strong>{{bank_account_balance}}</strong></h4> 
    
    <div ng-repeat="summary in summaries" class="finance_summary" 
         style="width: 400px; margin:10px; display: inline-block; border: solid 5px black;">
        <table class="table table-hover table-bordered table-condensed table-striped table-responsive">
            <thead>
            <th colspan="3" style="text-align: center">{{summary.academic_year}}</th>
            </thead>
            <tr style="color:#fff; background-color: #444">
                <td>Item</td>
                <td>Income</td>
                <td>Expense</td>
            </tr>
            <tr ng-repeat="item in summary">
                <td>{{item.type_name}}</td>
                <td>{{item.is_expense > 0 ? "": item.amount}}</td>
                <td>{{item.is_expense > 0 ? item.amount : ""}}</td>
            </tr>
            <tr style="font-weight: bold">
                <td>Total</td>
                <td>{{summary.total_income}}</td>
                <td>{{summary.total_expense}}</td>
            </tr>
            <tr  style="color:#fff; background-color: #444; font-weight: bold">
                <td>Income - Expense</td>
                <td colspan="2" style="text-align: center">
                    {{summary.total_income - summary.total_expense}} CFA
                </td>
            </tr>
        </table>
    </div>

</div>

