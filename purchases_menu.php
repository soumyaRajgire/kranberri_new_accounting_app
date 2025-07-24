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
                                <a class="dropdown-item create" data-doc="purchase_invoice" href="create-purchase-invoice.php"> Purchase Invoice</a>
                                <a class="dropdown-item create" data-doc="purchase_order" href="create-purchase-order.php"> Purchase Order</a>
                                <!-- <a class="dropdown-item create" data-doc="view_voucher" href="view-voucher.php"> -->
                                    <!-- Voucher Payment</a> -->

                                    <a href="#" data-toggle="modal" data-target="#newVoucherModal"  class="dropdown-item create" data-toggle="tooltip" data-placement="top" title="Open Link">Voucher</a>

                                <a class="dropdown-item create" data-doc="debit_note" href="create-debit-note.php">Debit Note</a>
                               <!--  <a class="dropdown-item create" data-doc="credit" href="create-credit-note.php"> Credit Note</a>
                                <a class="dropdown-item create" data-doc="receipt" href="javascript:;"> Receipts</a>
                                <a class="dropdown-item create" data-doc="dc" href="delivery_challan.php"> Delivery Challan</a> -->
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li purchase_invoice <?php echo ($currentPage == 'view-purchase-invoices.php') ? 'active' : ''; ?>" data-item="purchase_invoice" href="view-purchase-invoices.php">Purchase Invoice</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li purchase_order <?php echo ($currentPage == 'view-purchase-order.php') ? 'active' : ''; ?>" data-item="purchase_order" href="view-purchase-order.php">Purchase Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li voucher <?php echo ($currentPage == 'manage-voucher.php') ? 'active' : ''; ?>" data-item="voucher" href="manage-voucher.php">Voucher</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li dn <?php echo ($currentPage == 'manage-debitnote.php') ? 'active' : ''; ?>"  data-item="dn" href="manage-debitnote.php">Debit Note</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li payables <?php echo ($currentPage == 'manage_payables.php') ? 'active' : ''; ?>" data-item="payables" href="manage_payables.php">Payables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li wordoreder <?php echo ($currentPage == 'view_work_orders.php') ? 'active' : ''; ?>" data-item="wordoreder" href="view_work_orders.php">Work Orders</a>
                    </li>
                   <!--  <li class="nav-item">
                        <a class="nav-link exp_li receivables" data-item="receivables" href="/m/app/invoice/manage-receivable">Receivables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li delivery_challan" data-item="delivery_challan" href="manage_delivery_challan.php">Delivery Challan</a>
                    </li> -->
                </ul>
                    </div>
                </div>
            </div>