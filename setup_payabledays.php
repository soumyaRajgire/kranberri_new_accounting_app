<style>
    .benefit-btn, .deduction-btn {
        cursor: pointer;
    }
    .benefit-cell, .deduction-cell {
        position: relative;
    }
    .delete-icon {
        color: red;
        cursor: pointer;
        margin-left: 10px;
    }
</style>

<div class="row mt-5">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h2>Preview of Payroll Members</h2>
                <span>Pay Period - AUG 2024</span>
            </div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="payrollTable">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Payable Days</th>
                                <th>Benefit</th>
                                <th>Deduction</th>
                                <th>PF</th>
                                <th>ESI</th>
                                <th>PT</th>
                                <th>Gross</th>
                                <th>CTC</th>
                                <th>Payout</th>
                                <th>Error Logs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-id="1">
                                <td>3</td>
                                <td>vamshi</td>
                                <td>
                                    <span class="edit-icon" data-toggle="modal" data-target="#payableDaysModal">
                                        <i class="fas fa-pencil-alt"></i>
                                    </span>
                                </td>
                                <td class="benefit-cell">
                                    <span class="benefit-btn btn-primary" data-toggle="modal" data-target="#benefitModal">+Benefits</span>
                                </td>
                                <td class="deduction-cell">
                                    <span class="deduction-btn btn-danger" data-toggle="modal" data-target="#deductionModal">-Deduction</span>
                                </td>
                                <td>INR 0</td>
                                <td>INR 80</td>
                                <td>INR 0</td>
                                <td>INR 11,646</td>
                                <td>INR 15,488</td>
                                <td>INR 10,566</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-secondary">PREVIOUS</button>
                    <button class="btn btn-primary next-btn">NEXT STEP</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payable Days Modal -->
<div class="modal fade" id="payableDaysModal" tabindex="-1" role="dialog" aria-labelledby="payableDaysModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payableDaysModalLabel">Payable Days</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="payableDaysForm">
                    <div class="form-group">
                        <input type="number" class="form-control" id="payableDaysInput" placeholder="Enter payable days" min="0" max="31">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="savePayableDaysBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Benefit Modal -->
<div class="modal fade" id="benefitModal" tabindex="-1" role="dialog" aria-labelledby="benefitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="benefitModalLabel">One Time Benefit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="componentName">Component Name</label>
                                <input type="text" class="form-control" id="componentName" placeholder="Enter component name">
                            </div>
                            <div class="col-6">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="amount" placeholder="Enter amount">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBenefitBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Deduction Modal -->
<div class="modal fade" id="deductionModal" tabindex="-1" role="dialog" aria-labelledby="deductionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deductionModalLabel">One Time Deduction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="deductionComponentName">Component Name</label>
                                <input type="text" class="form-control" id="deductionComponentName" placeholder="Enter component name">
                            </div>
                            <div class="col-6">
                                <label for="deductionAmount">Amount</label>
                                <input type="text" class="form-control" id="deductionAmount" placeholder="Enter amount">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveDeductionBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('savePayableDaysBtn').addEventListener('click', function() {
        var payableDays = document.getElementById('payableDaysInput').value;

        if (payableDays) {
            // Update the table with the new value and keep the edit icon
            var row = document.querySelector('#payrollTable tbody tr');
            row.cells[2].innerHTML = `
                <span>${payableDays}</span>
                <span class="edit-icon" data-toggle="modal" data-target="#payableDaysModal">
                    <i class="fas fa-pencil-alt"></i>
                </span>
            `;

            // Close the modal
            $('#payableDaysModal').modal('hide');
        } else {
            alert("Please enter a valid number of payable days.");
        }
    });

    document.getElementById('saveBenefitBtn').addEventListener('click', function() {
        var componentName = document.getElementById('componentName').value;
        var amount = document.getElementById('amount').value;
        var row = document.querySelector('#payrollTable tbody tr[data-id="1"]'); // Adjust selector if you have multiple rows

        if (componentName && amount) {
            // Update the Benefit cell in the table
            row.querySelector('.benefit-cell').innerHTML = `
                ${componentName} INR ${amount}
                <i class="fas fa-trash delete-icon" onclick="removeEntry(this)"></i>
                <span class="benefit-btn btn-primary" data-toggle="modal" data-target="#benefitModal">+Benefits</span>
            `;

            // Close the modal
            $('#benefitModal').modal('hide');
        } else {
            alert("Please enter valid details for the benefit.");
        }
    });

    document.getElementById('saveDeductionBtn').addEventListener('click', function() {
        var componentName = document.getElementById('deductionComponentName').value;
        var amount = document.getElementById('deductionAmount').value;
        var row = document.querySelector('#payrollTable tbody tr[data-id="1"]'); // Adjust selector if you have multiple rows

        if (componentName && amount) {
            // Update the Deduction cell in the table
            row.querySelector('.deduction-cell').innerHTML = `
                ${componentName} INR ${amount}
                <i class="fas fa-trash delete-icon" onclick="removeEntry(this)"></i>
                <span class="deduction-btn btn-danger" data-toggle="modal" data-target="#deductionModal">-Deduction</span>
            `;

            // Close the modal
            $('#deductionModal').modal('hide');
        } else {
            alert("Please enter valid details for the deduction.");
        }
    });

    function removeEntry(element) {
        var cell = element.closest('td');
        element.closest('span').remove();
        cell.querySelector('span').style.display = 'inline';
    }
</script>
 