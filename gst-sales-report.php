<?php
session_start(); 
if(!isset($_SESSION['LOG_IN'])){
   header("Location:login.php");
} else {
   $_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
include("config.php");
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <!-- Linking Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        /* Custom styles with higher specificity */
        #dataTables-example thead {
            background-color: #f8e9d3; /* Light beige color */
            color: #333333; /* Darker text for better contrast */
        }
        .table-responsive {
            overflow-x: auto;
        }
        /* Media queries for improved layout on smaller screens */
        @media (max-width: 768px) {
            .input-group.w-25, .d-flex > .me-2 {
                width: 100%;
                margin-bottom: 10px;
            }
            .d-flex .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
    <?php include("header_link.php");?>
</head>
<body>

    <?php include("menu.php");?>

    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Gst Sales Reports</h4>
                            </div>
                            <ul class="breadcrumb float-end" style="margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">View Employees</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mt-5">
                <div class="card p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="input-group w-25">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <div class="d-flex flex-wrap">
                            <div class="me-2">
                                <label for="fromDate" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="fromDate" value="2024-10-08">
                            </div>
                            <div class="me-2">
                                <label for="toDate" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="toDate" value="2024-11-08">
                            </div>
                            <button class="btn btn-info ms-2" style="color: #fff !important;">+ Generate New Report</button>

                            <button class="btn btn-dark ms-2 mb-2" data-bs-toggle="modal" data-bs-target="#reportsModal">Reports</button>

                                <!-- Modal -->
                                <div id="reportsModal" class="modal fade" tabindex="-1" aria-labelledby="reportsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="reportsModalLabel">Generate Report</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="reportForm" action="generate_report.php" method="POST">
                                                    <div class="mb-3">
                                                        <label for="fromDate" class="form-label">From Date</label>
                                                        <input type="date" id="fromDate" name="from_date" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="toDate" class="form-label">To Date</label>
                                                        <input type="date" id="toDate" name="to_date" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Export Format</label>
                                                        <div class="form-check">
                                                            <input type="radio" id="pdfFormat" name="export_format" value="pdf" class="form-check-input" required>
                                                            <label for="pdfFormat" class="form-check-label">PDF</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="radio" id="excelFormat" name="export_format" value="excel" class="form-check-input" required>
                                                            <label for="excelFormat" class="form-check-label">Excel</label>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="dataTables-example" class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Date</th>
                                    <th>Invoice No.</th>
                                    <th>Buyer Name</th>
                                    <th>GSTIN</th>
                                    <th>Taxable Amount</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>IGST</th>
                                    <th>CESS</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="11" class="text-muted">No data available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <label class="me-2">Show</label>
                            <select class="form-select form-select-sm" style="width: auto;">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                                <option>100</option>
                            </select>
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">«</a>
                                </li>
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">»</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Employee Modal -->
    <div class="modal fade" id="viewEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewEmployeeModalLabel">Employee Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Employee details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable({
                "pageLength": 10
            });

            $('.view-employee').on('click', function() {
                var employeeId = $(this).data('id');
                $.ajax({
                    url: 'fetch_employee.php',
                    type: 'POST',
                    data: {id: employeeId},
                    success: function(response) {
                        $('#viewEmployeeModal .modal-body').html(response);
                        $('#viewEmployeeModal').modal('show');
                    }
                });
            });
        });
    </script>

</body>
</html>