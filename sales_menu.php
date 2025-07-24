 <?php
$currentPage = basename($_SERVER['PHP_SELF']); // Get the current page name
?>
 <div class="card">
 <div class="row align-items-center">
                    <div class="col-md-12">
                        <!--  <div class="page-header-title">
                            <h4 class="m-b-10">View Quotation</h4>
                        </div> -->
                        <ul class="ul_filter pl-0 mb-0 nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold mt-0 dash_nav" role="tablist">
                    <li class="nav-item searchfilter_li">
                        <div class="dropdown">
                            <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" style="height: 2.4rem !important;width:100%;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                New
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item create" data-doc="estimate" href="create-quotation.php"> Quotes</a>
                                <a class="dropdown-item create" data-doc="domestic-invoice" href="create-invoice.php"> Invoice</a>
                                <!-- <a class="dropdown-item create" data-doc="international-invoice" href="javascript:;">
                                    International Invoice</a> -->
                                <a class="dropdown-item create" data-doc="bill" href="bill-of-supply.php"> Bill of Supply</a>
                                <a class="dropdown-item create" data-doc="credit" href="create-credit-note.php"> Credit Note</a>
                                <!-- <a class="dropdown-item create" data-doc="receipt" href="javascript:;"> Receipts</a> -->
                                 <a href="#" data-toggle="modal" data-target="#newReceiptsModal"  class="dropdown-item create" data-toggle="tooltip" data-placement="top" title="Open Link">Reciepts</a>
                                <a class="dropdown-item create" data-doc="dc" href="delivery_challan.php"> Delivery Challan</a>
                            </div>
                        </div>
                    </li>
                   <li class="nav-item">
                        <a class="nav-link exp_li quotes <?php echo ($currentPage == 'view-quotation.php') ? 'active' : ''; ?> " data-item="quotes" href="view-quotation.php">Quotations</a>
                    </li> 
                    <li class="nav-item">

                        <a class="nav-link exp_li invoice <?php echo ($currentPage == 'view-invoices.php') ? 'active' : ''; ?> " data-item="invoice" href="view-invoices.php">Invoices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li bos <?php echo ($currentPage == 'manage-billsupply.php') ? 'active' : ''; ?>" data-item="bos" href="manage-billsupply.php">Bill Of Supply</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li cn <?php echo ($currentPage == 'manage-creditnote.php') ? 'active' : ''; ?>" data-item="cn" href="manage-creditnote.php">Credit Note</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li receipts <?php echo ($currentPage == 'manage-receipt.php') ? 'active' : ''; ?>" data-item="receipts" href="manage-receipt.php">Receipts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li receivables <?php echo ($currentPage == 'manage_receivables.php') ? 'active' : ''; ?>" data-item="receivables" href="manage_receivables.php">Receivables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li delivery_challan <?php echo ($currentPage == 'manage_delivery_challan.php') ? 'active' : ''; ?>" data-item="delivery_challan" href="manage_delivery_challan.php">Delivery Challan</a>
                    </li>
                </ul>
                    </div>
                </div>
            </div>