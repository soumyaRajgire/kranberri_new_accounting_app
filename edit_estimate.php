<!DOCTYPE html>
<?php
session_start();
// Check if the user is logged in
if(!isset($_SESSION['LOG_IN'])){
    header("Location:login.php");
    exit();
}

// Check if a business is selected
if(!isset($_SESSION['business_id'])){
    header("Location:dashboard.php");
    exit();
} else {
 // Set up variables for selected business and branch
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
        // Branch-specific code or logic here
    } 
}
include("config.php");
?>

<html lang="en">

<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <style type="text/css">
        .table th,
        .table td {
            padding: 0.45rem !important;
        }
    </style>

    <style>
        .vertical_line {
            border-left: 1px solid black;
            height: 300px;
            position: absolute;
            left: 70%;
            margin-left: -3px;
            top: 0;
        }
    </style>

</head>

<body class="">
    <?php include("customersModal.php"); ?>
    <?php include("servicesModalPopup.php"); ?>
    <?php include("productsModalPopUp.php"); ?>
    <?php include("menu.php"); ?>

    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Create Quotation</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Quotation</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header"></div>
                    <?php
                    function getQuotationDetails($conn, $qid)
                    {
                        $quotationId = $conn->real_escape_string($qid);

                        $query = "SELECT q.*, c.*, a.*, qi.*, im.in_ex_gst, im.net_price, im.gst_rate, im.price 
                                  FROM quotation q
                                  JOIN customer_master c ON q.customer_id = c.id
                                  JOIN address_master a ON c.id = a.customer_master_id
                                  JOIN quotation_items qi ON q.id = qi.quotation_id
                                  JOIN inventory_master im ON qi.product_id = im.id
                                  WHERE q.id = '$quotationId'";

                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            $quotationData = $result->fetch_assoc();
                            $quotationItems = [];
                            foreach ($result as $row) {
                                $netPriceArray = explode('|', $row['net_price']);

                                $quotationItems[] = [
                                    'itemnum' => $row['itemno'],
                                    'product' => $row['product'],
                                    'prod_desc' => $row['prod_desc'],
                                    'price' => $row['price'],
                                    'qty' => $row['qty'],
                                    'line_total' => $row['line_total'],
                                    'total' => $row['total'],
                                    'gst_amt' => $row['gst_amt'],
                                    'gst' => $row['gst'],
                                    'in_ex_gst' => $row['in_ex_gst'],
                                    'net_price' => $netPriceArray[0],
                                    'product_id' => $row['product_id'],
                                    'quotation_items_id' => $row['id'],
                                ];
                            }

                            $quotationData['quotation_items'] = $quotationItems;
                            return $quotationData;
                        } else {
                            return false;
                        }
                    }

                    $qid = isset($_GET['qid']) ? $_GET['qid'] : null;
                    $quotationDetails = $qid ? getQuotationDetails($conn, $qid) : false;
                    ?>

                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="">
                                        <div class="card-body">
                                            <form action="edit_quotationdb.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="qid" id="qid" value="<?php echo $qid; ?>">
                                                <div class="row border border-dark">
                                                    <div class="col-md-8 border-right border-dark">
                                                        <h6 style="float:left;" class="pt-2">KRIKA MKB CORPORATION PRIVATE LIMITED<br />120 Newport Center Dr, Newport Beach, CA 92660<br />
                                                            Email: abhijith.mavatoor@gmail.com<br />Phone: 9481024700<br />GSTIN: 29AAICK7493G1ZX<br />
                                                        </h6>
                                                    </div>
                                                    <div class="col-md-4 pt-1">
                                                        <div class="py-1 input-group">
                                                            <input class="form-control" type="text" id="purchaseNo" value="<?php echo $quotationDetails['invoice_code'] ?>" name="purchaseNo" />
                                                            <label class="form-control col-sm-5" for="purchaseNo">Purchase No</label>
                                                        </div>
                                                        <div class="py-1 input-group">
                                                            <input class="form-control" type="date" id="purchaseDate" name="purchaseDate" value="<?php echo $quotationDetails['quotation_date'] ?>" required />
                                                            <label class="form-control col-sm-5" for="purchaseDate">Purchase Date</label>
                                                        </div>
                                                        <div class="py-1 input-group">
                                                            <input class="form-control" type="date" id="dueDate" name="dueDate" value="<?php echo $quotationDetails['due_date'] ?>" required>
                                                            <label class="form-control col-sm-5" for="dueDate">Due Date</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" id="customer_data">
                                                    <div class="col-md-4 border-left border-bottom border-dark p-3">
                                                        <div>
                                                            <h6>Customer Info</h6>
                                                            <span><?php echo $quotationDetails['customerName']; ?></span><br />
                                                            <span>Business Name: <?php echo $quotationDetails['business_name'] === "" ? "business name" : $quotationDetails['business_name']; ?></span><br />
                                                            <span><?php echo $quotationDetails['s_state'] ?></span><br />
                                                            <span>GSTIN: <?php echo $quotationDetails['gstin'] === "" ? "" : $quotationDetails['gstin']; ?></span>
                                                        </div>
                                                    </div>
                                                    <input class="form-control" name="customer_name_choice" id="customer_name_choice" value="<?php echo $quotationDetails['customerName']; ?>" hidden />
                                                    <input class="form-control" name="customer_email" id="customer_email" value="<?php echo $quotationDetails['email']; ?>" hidden />
                                                    <input class="form-control" name="cst_mstr_id" id="cst_mstr_id" value="<?php echo $quotationDetails['customer_id']; ?>" hidden />

                                                    <div class="col-md-4 border-left border-bottom border-dark p-3">
                                                        <div>
                                                            <h6>Billing Address</h6>
                                                            <span><?php echo $quotationDetails['b_address_line1'] === "" ? '<span style="color:red;">Address Line1</span>' : $quotationDetails['b_address_line1']; ?></span><br />
                                                            <span><?php echo $quotationDetails['b_address_line2'] === "" ? '<span style="color:red;">Address Line2</span>' : $quotationDetails['b_address_line2']; ?></span><br />
                                                            <span><?php echo ($quotationDetails['b_city'] === "" ? '<span style="color:red;">City</span>' : $quotationDetails['b_city']) . "-" . ($quotationDetails['b_Pincode'] === "" ? '<span style="color:red;">Pincode</span>' : $quotationDetails['b_Pincode']); ?></span><br />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 border-left border-bottom border-right border-dark p-3">
                                                        <h6>Shipping Address</h6>
                                                        <span><?php echo $quotationDetails['s_address_line1'] === "" ? '<span style="color:red;">Address Line1</span>' : $quotationDetails['s_address_line1']; ?></span><br />
                                                        <span><?php echo $quotationDetails['s_address_line2'] === "" ? '<span style="color:red;">Address Line2</span>' : $quotationDetails['s_address_line2']; ?></span><br />
                                                        <span><?php echo ($quotationDetails['s_city'] === "" ? '<span style="color:red;">City</span>' : $quotationDetails['s_city']) . "-" . ($quotationDetails['s_Pincode'] === "" ? '<span style="color:red;">Pincode</span>' : $quotationDetails['s_Pincode']); ?></span><br />
                                                    </div>
                                                </div>

                                                <div class="row border-dark border-right border-left" id="box_loop_1">
                                                    <div class="col-md-3 p-3 border-right border-left border-bottom">
                                                        <button type="button" class="btn btn-sm dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0px;  margin-top: -17px; margin-bottom: 2px; margin-right: -12px; font-size: 11px;font-weight: 900; color: blue;"><i class="fa fa-plus"></i> New Item</button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#" data-value="products">Products</a>
                                                            <a class="dropdown-item" href="#" data-value="services">Services</a>
                                                        </div>
                                                        <input type="number" name="itemno" id="itemno" select-group="" data-count=<?php echo (int)$quotationDetails['itemno'] + 1 ?> hidden />
                                                        <input class="form-control" list="product" name="product_choice" id="product_choice" placeholder="Product" />
                                                        <datalist name="product" id="product">
                                                            <option value="">Select Items</option>
                                                            <?php
                                                            $sql = "select * from inventory_master";
                                                            $result = $conn->query($sql);
                                                            if ($result->num_rows > 0) {
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                            ?>
                                                                    <option value="<?php echo $row["name"] ?>" data-productid="<?php echo $row["id"] ?>">
                                                                    <?php
                                                                }
                                                            }
                                                                    ?>
                                                        </datalist>
                                                        <input type="text" name="productid" id="productid" value="" hidden />
                                                    </div>
                                                    <div class="col-md-4 p-3 border-right border-bottom">
                                                        <textarea name="prod_desc" id="prod_desc" rows="1" class="form-control" cols="20" placeholder="Product description"></textarea>
                                                    </div>
                                                    <div class="col-md-2 p-3 border-right border-bottom">
                                                        <input class="form-control" type="number" min="1" name="qty" id="qty" value="1" placeholder="quantity">
                                                    </div>
                                                    <div class="col-md-2 p-3 border-right border-bottom" id="pricevalbox">
                                                        <input type="text" class="form-control" name="price" id="price" value="">
                                                        <input type="text" class="form-control" name="netprice" id="netprice" value="" style="display: none;">
                                                        <input type="text" name="gst" id="gst" value="" style="display: none;">
                                                        <input type="text" name="in_ex_gst" id="in_ex_gst" value="" style="display: none;">
                                                    </div>
                                                    <div class="col-md-1 p-3 border-right border-bottom">
                                                        <button type="button" class="btn btn-success btn-sm" name="Addmore" id="addmore" onclick="add_more()">Add</button>
                                                    </div>
                                                </div>

                                                <div class="row border border-dark">
                                                    <table class="table table-bordered" id="item-list">
                                                        <colgroup>
                                                            <col width="18%">
                                                            <col width="37%">
                                                            <col width="13%">
                                                            <col width="14%">
                                                            <col width="18%">
                                                        </colgroup>
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Product</th>
                                                                <th class="text-center">Product Desc</th>
                                                                <th class="text-center">Price</th>
                                                                <th class="text-center">Quantity</th>
                                                                <th class="text-center">Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $c = 1;
                                                            $tot_amt = 0;
                                                            $index = 0;
                                                            foreach ($quotationDetails['quotation_items'] as $item) {
                                                                $gst = floatval($item['gst']);
                                                                $line_total = floatval($item['line_total']);

                                                                $cgst = ($gst / 2) * ($line_total / 100);
                                                                $sgst = ($gst / 2) * ($line_total / 100);

                                                                $cgst = number_format((float)$cgst, 2, '.', '');
                                                                $sgst = number_format((float)$sgst, 2, '.', '');
                                                            ?>
                                                                <tr>
                                                                    <td>
                                                                        <select class="form-control product" name="products[<?php echo $index; ?>][pname]">
                                                                            <option value="<?php echo $item['product']; ?>"><?php echo $item['product']; ?> </option>
                                                                            <?php
                                                                            $sql2 = "select * from inventory_master where inventory_type='Sales Catalog'";
                                                                            $result2 = $conn->query($sql2);
                                                                            if ($result2->num_rows > 0) {
                                                                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                                                            ?>
                                                                                    <option value="<?php echo $row2["name"] ?>"><?php echo $row2["name"] ?></option>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                    <td><textarea class="form-control" name="products[<?php echo $index; ?>][pdesc]"><?php echo $item['prod_desc']; ?></textarea></td>
                                                                    <td><input type="text" class="form-control" name="products[<?php echo $index; ?>][pprice]" value="<?php echo $item['price']; ?>"></td>
                                                                    <td><input type="number" class="form-control" name="products[<?php echo $index; ?>][pqty]" value="<?php echo $item['qty']; ?>"></td>
                                                                    <td><input class="form-control" type="number" name="products[<?php echo $index; ?>][ptotal]" value="<?php echo $item['line_total']; ?>" readonly></td>
                                                                    <input type="hidden" name="products[<?php echo $index; ?>][pitemno]" value="<?php echo $item['itemnum']; ?>">
                                                                    <input type="hidden" name="products[<?php echo $index; ?>][pgst]" value="<?php echo $item['gst']; ?>">
                                                                    <input type="hidden" name="products[<?php echo $index; ?>][pproductid]" value="<?php echo $item['product_id']; ?>">
                                                                    <input type="hidden" name="products[<?php echo $index; ?>][pcgst]" value="<?php echo $cgst; ?>">
                                                                    <input type="hidden" name="products[<?php echo $index; ?>][psgst]" value="<?php echo $sgst; ?>">
                                                                    <input type="hidden" name="products[<?php echo $index; ?>][pin_ex_gst]" value="<?php echo $item['in_ex_gst']; ?>">
                                                                    <input name="products[<?php echo $index; ?>][attr_id]" value="<?php echo $item['quotation_items_id'] ?>" hidden />
                                                                    <td><button class="btn btn-sm btn-outline-danger" type="button" onclick="rem_item($(this))"><i class="fa fa-trash" style="color:red;"></i></button></td>
                                                                </tr>
                                                            <?php
                                                                $tot_amt += floatval($item['line_total']);  // Convert line_total to float
                                                                $index++;
                                                                $c++;
                                                            }
                                                            ?>
                                                            <input name="i_id" id="i_id" value="<?php echo ($c); ?>" hidden />
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="2" rowspan="3"><textarea class="form-control" placeholder="Note" name="note" id="note" cols="20" style="width: -webkit-fill-available;height: 112px;"><?php echo isset($note) ? $note : ''; ?></textarea></th>
                                                                <th class="text-right" colspan="2">Sub Total</th>
                                                                <th class="text-right" id="sub_total"><?php echo $tot_amt; ?>
                                                                    <input type="text" name="sub_total" value="<?php echo $tot_amt; ?>" hidden>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-right" colspan="2">Additional Payable</th>
                                                                <th><input type="number" class="form-control" name="pack_price" id="pack_price" value="0" onchange="calc_total();"></th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-right" colspan="2">Grand Total</th>
                                                                <th class="text-right" id="gtotal"><?php echo $tot_amt; ?></th>
                                                                <input type="hidden" name="total_amount" value="<?php echo $tot_amt; ?>">
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 border-left border-right border-bottom border-dark p-3">
                                                        <textarea class="form-control" placeholder="Terms and Condition" name="terms_condition" id="terms_condition" cols="20" style="width: -webkit-fill-available;height: 112px;"></textarea>
                                                    </div>
                                                    <div class="col-md-6 border-right border-bottom border-dark p-3">
                                                        <div>
                                                            <h6>For KRIKA MKB CORPORATION PRIVATE LIMITED</h6><br />
                                                            <h6>Authorized Signatory</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row col-md-12 text-center pt-3">
                                                    <div class="col-md-2"><input type="submit" class="btn btn-primary" name="update" value="Submit" /></div>
                                                    <div class="col-md-2"><input type="reset" class="btn btn-danger" name="cancel" value="Cancel" /></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

    </section>

    <script type="text/javascript">
       function add_more() {
    var count = <?php echo count($quotationDetails['quotation_items']); ?>;
    var itemno = count;
    var prod_desc = $('#prod_desc').val();
    var product = $('#product_choice').val();
    var productid = $('#productid').val();
    var qty = $('#qty').val();
    var price = $('#price').val();
    var gst = $('#gst').val();
    var netprice = $('#netprice').val();
    var in_ex_gst = $('#in_ex_gst').val();
    var total = parseFloat(price) * parseFloat(qty);
    var cgst = ((parseFloat(gst) / 2) * parseFloat(total) / 100).toFixed(2);
    var sgst = ((gst / 2) * total / 100).toFixed(2);
    
    var rowHtml = '<tr>' +
        '<td>' + product + '<input type="hidden" name="products[' + count + '][pname]" value="' + product + '"></td>' +
        '<td>' + prod_desc + '<input type="hidden" name="products[' + count + '][pdesc]" value="' + prod_desc + '"></td>' +
        '<td>' + price + '<input type="hidden" name="products[' + count + '][pprice]" value="' + price + '"></td>' +
        '<td>' + qty + '<input type="hidden" name="products[' + count + '][pqty]" value="' + qty + '"></td>' +
        '<td>' + total + '<input type="hidden" name="products[' + count + '][ptotal]" value="' + total + '"></td>' +
        '<input type="hidden" name="products[' + count + '][pgst]" value="' + gst + '">' +
        '<input type="hidden" name="products[' + count + '][pnetprice]" value="' + netprice + '">' +
        '<input type="hidden" name="products[' + count + '][pproductid]" value="' + productid + '">' +
        '<input type="hidden" name="products[' + count + '][pcgst]" value="' + cgst + '">' +
        '<input type="hidden" name="products[' + count + '][psgst]" value="' + sgst + '">' +
        '<input type="hidden" name="products[' + count + '][pin_ex_gst]" value="' + in_ex_gst + '">' +
        '<input type="hidden" name="products[' + count + '][pitemno]" value="' + itemno + '">' +
        '<td><button class="btn btn-sm btn-outline-danger" type="button" onclick="rem_item($(this))"><i class="fa fa-trash" style="color:red;"></i></button></td>' +
        '</tr>';

    $('#item-list tbody').append(rowHtml);

    $('#prod_desc').val('').trigger('change');
    $('#product_choice').val('').trigger('change');
    $('#qty').val(1).trigger('change');
    $('#price').val('').trigger('change');

    itemno++;
    calc_total();
}

function calc_total() {
    var total = 0;
    var pack_price = parseFloat($('#pack_price').val()) || 0;

    $('#item-list tbody tr').each(function() {
        var lineTotal = parseFloat($(this).find('input[name^="products["][name$="[ptotal]"]').val());
        if (!isNaN(lineTotal)) {
            total += lineTotal;
        }
    });

    total += pack_price;

    $('[name="sub_total"]').val(total);
    $('#sub_total').text(parseFloat(total).toLocaleString('en-US'));
    var gtotal = parseFloat(total);
    var gt_round = Math.round(gtotal);
    $('[name="total_amount"]').val(gt_round);
    $('#gtotal').text(parseFloat(gt_round).toLocaleString('en-US'));
}

function rem_item(_this) {
    _this.closest('tr').remove();
    calc_total();
}

$(document).ready(function() {
    console.log("Script loaded and running");

    function updateLineTotal(tr) {
        var qty = (tr.find('input[name*="[pqty]"]').val()) || 0;
        var price = (tr.find('input[name*="[pnetprice]"]').val()) || 0;
        var lineTotal = qty * price;

        lineTotal = parseFloat(lineTotal.toFixed(2));
        tr.find('input[name*="[ptotal]"]').val(lineTotal);
        calc_total();
    }

    $('#item-list').on('input', 'input[name*="[pqty]"], input[name*="[pprice]"]', function() {
        var tr = $(this).closest('tr');
        updateLineTotal(tr);
    });
});

        $(document).ready(function() {
            $("#product_choice").change(function() {
                var productname = $(this).val();
                var dataListOptions = document.getElementById('product').querySelectorAll('option');
                for (var i = 0; i < dataListOptions.length; i++) {
                    if (dataListOptions[i].value === productname) {
                        var productId = dataListOptions[i].getAttribute('data-productid');
                        break;
                    }
                }
                $("#productid").val(productId);
                $.ajax({
                    url: 'getprice.php',
                    Type: "GET",
                    data: {
                        "productname": productname,
                        "productid": productId
                    },
                    success: function(data) {
                        console.log(data);
                        var jsonData = JSON.parse(data);
                        $("#gst").val(jsonData.gst);
                        if (jsonData.in_ex_gst === "inclusive of GST") {
                            $("#price").val(jsonData.netprice);
                        } else if (jsonData.in_ex_gst === "exclusive of GST") {
                            $("#price").val(jsonData.price);
                        }
                        $("#netprice").val(jsonData.netprice);
                        $("#in_ex_gst").val(jsonData.in_ex_gst);
                    }
                });
            })
        });
    </script>

    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
</body>

</html>