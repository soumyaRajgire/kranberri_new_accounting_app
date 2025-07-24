<!--bgt-convert-invoice-to-quotation.php-->
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
                                <h4 class="m-b-10">convert to Invoice</h4>
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

                        $query = "SELECT 
    q.*, 
    c.*, 
    a.*, 
    qi.*, 
    qi.id AS quotation_items_id,
    (SELECT qac.charge_price 
     FROM quotation_additional_charges qac 
     WHERE qac.invoice_id = '$quotationId'
     LIMIT 1) AS charge_price,
    (SELECT qac.charge_type 
     FROM quotation_additional_charges qac 
     WHERE qac.invoice_id = '$quotationId'
     LIMIT 1) AS charge_type
FROM 
    quotation q
JOIN 
    customer_master c ON q.customer_id = c.id
JOIN 
    address_master a ON c.id = a.customer_master_id
JOIN 
    quotation_items qi ON q.id = qi.invoice_id
WHERE 
    q.id = '$quotationId'

                                  ";

                        
//echo "<pre>SQL Query: " . htmlspecialchars($query) . "</pre>";


//echo "<script>console.log('SQL Query: " . addslashes($query) . "');</script>";


                        $result = $conn->query($query);

//                        if ($result->num_rows > 0) {
    if ($result && $result->num_rows > 0) {
                            $quotationData = $result->fetch_assoc();
                           
                           
                            $quotationItems = [];
                            foreach ($result as $row) {
                                $netPriceArray = explode('|', $row['net_price']);

                                $quotationItems[] = [
                                    'quotation_items_id' => $row['quotation_items_id'],
                                    'itemnum' => $row['itemno'],
                                    'product' => $row['product'],
                                    'prod_desc' => $row['prod_desc'],
                                    'price' => $row['price'],
                                    'qty' => $row['qty'],
                                    'line_total' => $row['price'] * $row['qty'],
                                    'total' => $row['total'],
                                    'gst_amt' => $row['total_gst'],
                                    'gst' => $row['gst'],
                                    'in_ex_gst' => $row['in_ex_gst'],
                                    'net_price' => $netPriceArray[0],
                                    'product_id' => $row['product_id'],
                                    'quotation_items_id' => $row['id'],
                                    'terms_condition' => $row['terms_condition'],
                                    'note' => $row['note'],
                                      'customer_state' => $row['s_state'],
                            'business_state' => $row['b_state'],
                                                                        
                                 'discount' => $row['discount'] ?? 0,  // Assign discount value
                               
                                'cgst' => $row['cgst'] ?? 0,          // Assign CGST value
                                'sgst' => $row['sgst'] ?? 0,          // Assign SGST value
                                'igst' => $row['igst'] ?? 0,          // Assign IGST value
                                'cess_rate' => $row['cess_rate'] ?? 0, // Assign Cess Rate
                                'cess_amount' => $row['cess_amount'] ?? 0,
                                 'charge_type' => $row['charge_type'] ?? 0,
                                  'charge_price' => $row['charge_price'] ?? 0,
                                
                                



                                ];
                               
                            }
                            $quotationData['quotation_items'] = $quotationItems;
                            return $quotationData;
                            echo '<script>';
                            //echo 'alert(JSON.stringify(' . json_encode($quotationItems) . ', null, 2));';
                            echo '</script>';
                            
                            
                        }
                         else {
                            
                                echo "<script>alert('No results found');</script>";
                            
                            return false;
                        }
                    }

                    $qid = isset($_GET['id']) ? $_GET['id'] : null;
                    $quotationDetails = $qid ? getQuotationDetails($conn, $qid) : false;
                    ?>

                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="">
                                        <div class="card-body">
                                           <!-- <form action="edit_quotationdb.php" method="POST" enctype="multipart/form-data"> -->
                                            <form action="bgt_si.php" method="POST" enctype="application/x-www-form-urlencoded">
                                                <input type="hidden" name="qid" id="qid" value="<?php echo $qid; ?>">
                                                <div class="row border border-dark">
                                                    <div class="col-md-8 border-right border-dark">
                                                        <h6 style="float:left;" class="pt-2">KRIKA MKB CORPORATION PRIVATE LIMITED<br />120 Newport Center Dr, Newport Beach, CA 92660<br />
                                                            Email: abhijith.mavatoor@gmail.com<br />Phone: 9481024700<br />GSTIN: 29AAICK7493G1ZX<br />
                                                        </h6>
                                                    </div>
                                                     <input type="hidden"
                                                      name="business_state" id="business_state" value="<?php echo $quotationDetails['b_state'] ?>" >
                                                     <input type="hidden"
                                                      name="customer_state" id="customer_state" value="<?php echo $quotationDetails['s_state'] ?>" >
                                                    <div class="col-md-4 pt-1">
                                                        <div class="py-1 input-group">
                                                            <input class="form-control" type="text" id="purchaseNo" value="<?php echo $quotationDetails['invoice_code'] ?>" name="purchaseNo" />
                                                            <label class="form-control col-sm-5" for="purchaseNo">Purchase No</label>
                                                        </div>
                                                        <div class="py-1 input-group">
                                                            <input class="form-control" type="date" id="purchaseDate" name="purchaseDate" value="<?php echo $quotationDetails['invoice_date'] ?>" required readonly/>
                                                            <label class="form-control col-sm-5" for="purchaseDate">Purchase Date</label>
                                                        </div>
                                                        <div class="py-1 input-group">
                                                            <input class="form-control" type="date" id="dueDate" name="dueDate" value="<?php echo $quotationDetails['due_date'] ?>" required readonly>
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

                                               

                                        <div class="row border border-dark">
                                            <table class="table table-bordered" id="item-list">
                                                    <colgroup>
                                                        <col width="12%">
                                                        <col width="15%">
                                                        <col width="6%">
                                                        <col width="6%">
                                                        <col width="6%">
                                                        <col width="6%">
                                                    </colgroup>
                                                    <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Product Desc</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                        <th>Discount</th>
                                                        <th>GST</th>
                                                        <th>CGST</th>
                                                        <th>SGST</th>
                                                        <th>IGST</th>
                                                        <th>Cess</th>
                                                        <th>Total</th>
                                                        
                                                    </tr>

                                                </thead>

                                                        <tbody>
                                                            <?php
                                                            $c = 100;
                                                            $tot_amt = 0;
                                                            $index = 0;
                                                            $total_cess = 0;
                                                            $total_gst = 0;
                                                            $total_cgst = 0;
                                                            $total_sgst = 0;
                                                            $total_igst = 0;
                                                            $total_amount = 0;
                                                            $total_additional_charges = 0; // Initialize total charges variable

         
                                                            
                                                            foreach ($quotationDetails['quotation_items'] as $item) {
                                                                $note = $item['note'];
                                                                $termsCondition = $item['terms_condition'];
                                                                $gst = floatval($item['gst']);
                                                                $line_total = floatval($item['price'] * $item['qty']) ;

                                                                $cgst = ($gst / 2) * ($line_total / 100);
                                                                $sgst = ($gst / 2) * ($line_total / 100);

                                                                $cgst = number_format((float)$cgst, 2, '.', '');
                                                                $sgst = number_format((float)$sgst, 2, '.', '');
                                                                
                                                                
                                                                                                                                 
                                                                    $total_cess += floatval($item['cess_rate']);
                                                                    // $total_gst += $cgst;
                                                                    //  $total_gst += $sgst;
                                                                    $total_cgst += floatval($cgst);
                                                                    $total_sgst += floatval($sgst);
                                                                    $total_igst += floatval($item['igst']);
                                                                    
                                                                    $total_gst += floatval($cgst) + floatval($sgst) + floatval($item['igst']);
                                                                    
                                                                    
                                                                    $total_amount += $line_total ;
                                                                    
                                                                    
                                                                    
                                                                    $charge_type = htmlspecialchars($item['charge_type'], ENT_QUOTES, 'UTF-8');
                                                                    $charge_price = floatval($item['charge_price']);
                                                    
                                                                    // Add the current charge to the total
                                                                    $total_additional_charges += $charge_price;
                                                            ?>
                                                                <tr>
                                                                    <td>
                                                                         <!-- <input type="text" class="form-control" name="products[<?php echo $index; ?>][pname]" value="<?php echo $item['pname']; ?>"> -->
                                                                       <select class="form-control product" name="products[<?php echo $index; ?>][pname]" readonly>
                                                                            <option value="<?php echo $item['product']; ?>"><?php echo $item['product']; ?> </option>
                                                                            <?php
                                                                            $sql2  = "select * from inventory_master where inventory_type='Sales Catalog'";
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
                                                                    
                                                                    <td>
                                                                        <textarea class="form-control" name="products[<?php echo $index; ?>][pdesc]" readonly>
                                                                            <?php echo $item['prod_desc']; ?>
                                                                    </textarea></td>
                                                                    <td><input type="number" class="form-control" name="products[<?php echo $index; ?>][pqty]" value="<?php echo $item['qty']; ?>" readonly></td>
                                                                    <td><input type="text" class="form-control" name="products[<?php echo $index; ?>][pprice]" value="<?php echo $item['price']; ?>" readonly></td>
                                                          
                                                                    <td><input type="number" class="form-control" name="products[<?php echo $index; ?>][pdiscount]" value="<?php echo $item['discount']; ?>" readonly></td>
                                                                    <td><input type="number" class="form-control" name="products[<?php echo $index; ?>][pgst]" value="<?php echo $item['gst']; ?>" readonly></td>
                                                                    <td><input type="number" class="form-control" name="products[<?php echo $index; ?>][pcgst]" value="<?php echo $item['cgst']; ?>" readonly> </td>
                                                                    <td><input type="number" class="form-control" name="products[<?php echo $index; ?>][psgst]" value="<?php echo $item['sgst']; ?>" readonly> </td>
                                                                    <td><input type="number" class="form-control" name="products[<?php echo $index; ?>][pigst]" value="<?php echo $item['igst']; ?>" readonly> </td>
                                                                    <td><input type="number" class="form-control" name="products[<?php echo $index; ?>][pcess]" value="<?php echo $item['cess_rate']; ?>" readonly>
                                                                    <input type="hidden" class="form-control" name="products[<?php echo $index; ?>][charge_price]" value="<?php echo $item['charge_price']; ?>" >
                                                                    </td>
                                                                    <td><input class="form-control" type="number" name="products[<?php echo $index; ?>][ptotal]" value="<?php echo $item['total']; ?>" readonly></td>

                                                                    <input type="hidden" name="products[<?php echo $index; ?>][pitemno]" value="<?php echo $item['itemnum']; ?>">
                                                                    <input type="hidden" name="products[<?php echo $index; ?>][pgst]" value="<?php echo $item['gst']; ?>">
                                                                    <input type="hidden" name="products[<?php echo $index; ?>][pproductid]" value="<?php echo $item['product_id']; ?>">
                                                                   
                                                                    <input type="hidden" name="products[<?php echo $index; ?>][pin_ex_gst]" value="<?php echo $item['in_ex_gst']; ?>">
                                                                    <input name="products[<?php echo $index; ?>][attr_id]" value="<?php echo $item['quotation_items_id'] ?>" hidden />
                                                                    

                                                                

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
                                                                <th colspan="6" rowspan="3">
                                                                    <textarea class="form-control"  placeholder="Note" name="note" id="note" cols="20" style="width: -webkit-fill-available;height: 112px;">
                                                                        <?php echo $note; ?>
                                                                       
                                                                    </textarea></th>
                                                                

                                                                   
                                                                </tr>

                                                                <tr>

                                                                <th  colspan="6" >
                                                                   
                
                  <table style="width:100%;">
                 <tr>       
                     <td class="" id="taxable_amt_text" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;">Taxable Amount</td>
                     <td style="text-align:right;" id="final_taxable_amt" name="td_final_taxable_amt"
                    > 
                     <?php echo $item['total']; ?>
                     </td>
                                                            
                </tr> 
        
                <tr>
                    <td class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;">Total GST</td>
                    <td style="text-align:right;" id="final_gst_amount" name="td_final_gst_amt"> 
                        <?php echo $item['sgst'] + $item['cgst'] + $item['igst'] 
                     
                     
                     ?>
                    </td>
                     
                </tr>

                <tr>
                    <td class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;">Total Cess</td>
                    <td style="text-align:right;" id="final_cess_amount" name="td_final_cess_amt">
                        <?php echo  $item['cess_amount'] 
                     
                     
                     ?>

                    </td>
                    
                </tr>
            <tr>
                     <td class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;">Total Additional Charges:</td>
                     
                            <?php
                            
                
                                // Display each charge
                                // echo "<div class='charge-item'>";
                                // echo "<span class='charge-type'>{$charge_type}:</span>";
                                // echo "<span class='charge-price'>" . number_format($charge_price, 2) . "</span>";
                                // echo "</div>";
                            
                            ?>
                            </td>
                            <td style="text-align:right;" >
                            
                                <span  id="additional_charges_id" ><?php echo number_format($total_additional_charges, 2); ?></span>
                        
                    </td>
            </tr>

             
                 <tr>
                      <th class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;" >Grand Total</th>
                     <th class="text-right">
                <span id="gtotal">0.00</span>
               <input type="hidden" name="final_cess_amount" id="final_cess_amount_field" value="" placeholder="Enter Final Cess Amount">
<input type="hidden" name="final_taxable_amt" id="final_taxable_amt_field" value="" placeholder="Enter Final Taxable Amount">
<input type="hidden" name="final_gst_amount" id="final_gst_amount_field" value="" placeholder="Enter Final GST Amount">
<input type="hidden" name="total_amount" id="total_amount" value="" placeholder="Enter Total Amount">
<input type="hidden" name="final_additional_charges" id="final_additional_charges_id" value="" placeholder="Enter Total Amount">

            </th>
                    </tr>
               
            </table>
               
               
                                                                </th>
                                                                
                                                                


                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 border-left border-right border-bottom border-dark p-3">
                                                        <textarea class="form-control" placeholder="Terms and Condition" name="terms_condition" id="terms_condition" cols="20" style="width: -webkit-fill-available;height: 112px;">
                                                      <?php echo  $termsCondition ?>
                                                        
                                                        </textarea>
                                                    </div>
                                                    <div class="col-md-6 border-right border-bottom border-dark p-3">
                                                        <div>
                                                            <h6>For KRIKA MKB CORPORATION PRIVATE LIMITED</h6><br />
                                                            <h6>Authorized Signatory</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row col-md-12 text-center pt-3">
                                                    <div class="col-md-2"><input type="submit" class="btn btn-primary" name="submit" value="Submit" /></div>
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

    
<script> 
function remove_item(button) {
    $(button).closest('tr').remove(); 
    calculate_totals(); // Recalculate totals
}
    function confirmDelete(button) {
        if (confirm("Do you want to delete this product?")) {
            // Retrieve the `data-id` directly from the button
            const itemId = button.getAttribute('data-id');

            // Perform an AJAX request to delete the item
            $.ajax({
                url: 'delete-quotation-item-product.php', // URL for deletion
                method: 'POST',
                data: { id: itemId },
                success: function(response) {
                    // Parse the JSON response if necessary
                    try {
                        const result = JSON.parse(response);
                        alert(result.message || 'Item deleted successfully!');
                    } catch (e) {
                        alert('Item deleted successfully!');
                    }

                 
                    $(button).closest('tr').remove();
                },
                error: function(xhr, status, error) {
                    alert('Error deleting the item: ' + (xhr.responseJSON?.message || error));
                }
            });
        }
    }
</script>

    
<script>
      $(document).ready(function() {
    // Set the final values for the fields
    $('#final_cess_amount_field').val(parseFloat('<?php echo $total_cess; ?>').toFixed(2));  // Set cess total
    $('#final_taxable_amt_field').val(parseFloat('<?php echo $total_amount; ?>').toFixed(2));  // Set taxable amount
    $('#final_gst_amount_field').val(parseFloat('<?php echo $total_gst; ?>').toFixed(2));  // Set total GST
   $('#final_additional_charges_id').val(parseFloat('<?php echo $total_additional_charges; ?>').toFixed(2));
     $('#final_taxable_amt').text(
        (parseFloat('<?php echo $total_amount; ?>')).toFixed(2)
    );

    $('#final_gst_amount').text(
        (parseFloat('<?php echo $total_cgst; ?>') + parseFloat('<?php echo $total_sgst; ?>') + parseFloat('<?php echo $total_igst; ?>')).toFixed(2)
    );

    $('#final_cess_amount').text(
        (parseFloat('<?php echo $total_cess; ?>')).toFixed(2)
    );
  
});
$(document).ready(function() {
    document.addEventListener('change', function (event) {
    // Check if the target is one of the input fields for qty, price, or percentage fields
    if (event.target.matches(`[name^='products'][name$='[pqty]'], 
                             [name^='products'][name$='[pprice]'], 
                             [name^='products'][name$='[pdiscount]'], 
                             [name^='products'][name$='[pgst]'], 
                             [name^='products'][name$='[pcgst]'], 
                             [name^='products'][name$='[psgst]'], 
                             [name^='products'][name$='[pigst]'], 
                             [name^='products'][name$='[pcess]']`)) {
        // Extract the index from the name attributeSingh Bahadur
        const nameAttr = event.target.name;
        const index = nameAttr.match(/\[(\d+)\]/)[1]; // Extract index from name, e.g., "products[0][pqty]"

        updateRowTotal(index);
    }
});
function updateRowTotal(index) {
    // Get values from the row
    const qty = document.querySelector(`[name='products[${index}][pqty]']`).value || 0;
    const price = parseFloat(document.querySelector(`[name='products[${index}][pprice]']`).value) || 0;
    const base = qty * price;

    const discountPercent = parseFloat(document.querySelector(`[name='products[${index}][pdiscount]']`).value) || 0;
    const gstPercent = parseFloat(document.querySelector(`[name='products[${index}][pgst]']`).value) || 0;
    const cgstvalue = parseFloat(document.querySelector(`[name='products[${index}][pcgst]']`).value) || 0;
    const sgstvalue = parseFloat(document.querySelector(`[name='products[${index}][psgst]']`).value) || 0;
    const igstPercent = parseFloat(document.querySelector(`[name='products[${index}][pigst]']`).value) || 0;
    const cessPercent = parseFloat(document.querySelector(`[name='products[${index}][pcess]']`).value) || 0;

    // Calculate percentages
    const discount = (base * discountPercent) / 100;
    const gst = (base * gstPercent) / 100;
   
    const igst = (base * igstPercent) / 100;
    const cess = (base * cessPercent) / 100;

  const cgst = Math.round(gst / 2) + '.00';
const sgst = Math.round(gst / 2) + '.00';


let total1;
    // Calculate the total for the row
     total1= base - discount +  cgst + sgst + igst + cess;
     console.log(total1);
     
let total;
    // Calculate the total for the row
     total = base - discount +  cgst + sgst + igst + cess;
 document.querySelector(`[name='products[${index}][ptotal]']`).value = total1;

    
    // Add Freight Charges (Transportation Details)
    const freightCharges = parseFloat($('#roadFreightCharges').val() || 0) +
                           parseFloat($('#railFreightCharges').val() || 0) +
                           parseFloat($('#airFreightCharges').val() || 0) +
                           parseFloat($('#shipFreightCharges').val() || 0);
    total = total+freightCharges;

    // Add Other Charges (from additional charges and TCS)
    const additionalCharges = Array.from(document.querySelectorAll('.charge-input'))
        .reduce((acc, input) => acc + (parseFloat(input.value) || 0), 0);
    total = total+additionalCharges;

   
    const tcsValue = total * (gstPercent / 100);
    total = total+tcsValue;

    // Update footer fields
    $('#final_taxable_amt').text(
    (parseFloat(base)).toFixed(2)
);
    $('#final_gst_amount').text(
    (parseFloat(cgst) + parseFloat(sgst) + parseFloat(igst)).toFixed(2)
);

    $('#final_cess_amount').text(cess.toFixed(2));


     //$('#final_taxable_amt_field').val(total1);
      //$('#final_gst_amount_field').val((cgst + sgst + igst));
      // $('#final_cess_amount_field').val(cess.toFixed(2));
      

//$total_additional_charges
 

// Direct assignment with parseFloat for numeric conversion
$('#final_cess_amount_field').val(parseFloat(cess).toFixed(2));  
$('#final_taxable_amt_field').val(parseFloat(total1).toFixed(2)); 
$('#final_gst_amount_field').val(parseFloat(cgst) + parseFloat(sgst) + parseFloat(igst));  


// Fetch values from input fields by ID and convert them to numbers
var taxableAmount = parseFloat(document.getElementById('final_taxable_amt_field').value) || 0;
var gstAmount = parseFloat(document.getElementById('final_gst_amount_field').value) || 0;
var cessAmount = parseFloat(document.getElementById('final_cess_amount_field').value) || 0;
// Fetch value from span and convert to number
var spanadditionalCharges = parseFloat(document.getElementById('additional_charges_id').textContent) || 0;

// Log the result to verify
console.log(typeof additionalCharges, spanadditionalCharges); // Output: "number", 50


// Calculate the grand total
var grandTotal = taxableAmount + gstAmount + cessAmount + spanadditionalCharges;

// Update the grand total field
var gtotalElement = document.getElementById('gtotal');
if (gtotalElement.tagName === 'INPUT') {
    gtotalElement.value = grandTotal.toFixed(2); //  input field
} else {
    gtotalElement.textContent = grandTotal.toFixed(2); //  <span> or <div>
}

$('#total_amount').val(parseFloat(total1).toFixed(2));
    // Debugging (Optional)
    console.log(`Freight Charges: ${freightCharges}, Additional Charges: ${additionalCharges}, TCS: ${tcsValue}`);


    // document.querySelector(`#pgst-${index}`).value = gst.toFixed(2);
      document.querySelector(`[name='products[${index}][pcgst]']`).value = cgst;
        document.querySelector(`[name='products[${index}][psgst]']`).value = sgst;
    const resultMessage = `
Base: ${base.toFixed(2)}
Discount: ${discount.toFixed(2)}
GST: ${gst.toFixed(2)}
CGST: ${cgst}
SGST: ${sgst} 
IGST: ${igst.toFixed(2)}
Cess: ${cess.toFixed(2)}
Total: ${total}
`;

// Log to the console
console.log(resultMessage);

// Show an alert
//alert(resultMessage);
    // Recalculate subtotal and grand total
    calcSubTotal();
}

function calcSubTotal() {
    let subTotal = 0;
    document.querySelectorAll(`[name^='products'][name$='[ptotal]']`).forEach(input => {
        subTotal += parseFloat(input.value) || 0;
    });
    // document.getElementById('sub_total').textContent = subTotal.toFixed(2);
    // document.querySelector(`input[name='sub_total']`).value = subTotal.toFixed(2);

    calcGrandTotal();
}

function calcGrandTotal() {
    
 // const subTotal = parseFloat(document.querySelector(`input[name='sub_total']`).value) || 0;
    // const packPrice = parseFloat(document.getElementById('pack_price').value) || 0;
    // const grandTotal = subTotal + packPrice;

    // document.getElementById('gtotal').textContent = grandTotal.toFixed(2);
    // document.querySelector(`input[name='total_amount']`).value = grandTotal.toFixed(2);
}
});
</script>

    <script type="text/javascript">
function add_more() {
    const prod_desc = $('#prod_desc').val();
    const product = $('#product_choice').val();
    const productid = $('#productid').val();
    const qty = parseFloat($('#qty').val()) || 0;
    const price = parseFloat($('#price').val()) || 0; // Price
      const netprice = parseFloat($('#netprice').val()) || 0; // Price
    const gst = parseFloat($('#gst').val()) || 0; // GST rate
    const discount = parseFloat($('#discount').val()) || 0; // Discount %
    const cess_rate = parseFloat($('#cess_rate').val()) || 0; // Cess rate %
    const cess_amount = parseFloat($('#cess_amount').val()) || 0; // Cess amount (from hidden input)
    const in_ex_gst = $('#in_ex_gst').val(); // GST type (inclusive or exclusive)

    const customer_s_state = $('#customer_s_state').val(); // Customer State
    const business_state = $('#business_state').val(); // Business State

    if (!product || qty <= 0 || price <= 0) {
        alert("Please fill in all required fields (Product, Quantity, Price).");
        return;
    }

    let basePrice = 0;
    let taxableAmount = 0;
    let gstAmount = 0;
    let cgst = 0, sgst = 0, igst = 0;
    let totalAmount = 0;

    // Calculate taxable amount and GST based on inclusive/exclusive GST
    if (in_ex_gst === "inclusive of GST") {
        // basePrice = price / (1 + gst / 100); // Extract base price
        taxableAmount = netprice * qty;
        gstAmount = taxableAmount * (gst / 100); // GST amount
    } else if (in_ex_gst === "exclusive of GST") {
        taxableAmount = price * qty; // Price is already exclusive of GST
        gstAmount = taxableAmount * (gst / 100); // GST amount
    }
console.log("from add more taxable amount"+taxableAmount);
console.log("gst Amount before discount"+gstAmount);
    // Apply Discount
    const discountedTaxableAmount = taxableAmount - (taxableAmount * discount) / 100;

    // Recalculate GST based on discounted taxable amount
    gstAmount = discountedTaxableAmount * (gst / 100);
console.log("gst Amount after discount"+gstAmount);
    // Determine CGST, SGST, IGST based on state
    if (customer_s_state === business_state) {
        // Intrastate: Split GST equally into CGST and SGST
        cgst = (gstAmount / 2);
        sgst = gstAmount / 2;
    } else {
        // Interstate: Entire GST is treated as IGST
        igst = gstAmount;
    }
    

    // Use the retrieved cess amount
    // const finalCessAmount = cess_amount * qty;
const finalCessAmount = discountedTaxableAmount * (cess_rate / 100); 

    // Calculate Total Amount
    totalAmount = discountedTaxableAmount + gstAmount + finalCessAmount;

    // Generate Table Row with Hidden Inputs
    const itemno = $('#item-list tbody tr').length + 1;

    const rowHtml = `
        <tr>
            <td>${product}</td>
            <td>${prod_desc}</td>
            <td>${qty}</td>
            <td>${price.toFixed(2)}</td>
            <td>${discount}%</td>
            <td>${gst}%</td>
            <td>${cgst > 0 ? cgst.toFixed(2) : '-'}</td>
            <td>${sgst > 0 ? sgst.toFixed(2) : '-'}</td>
            <td>${igst > 0 ? igst.toFixed(2) : '-'}</td>
            <td>${finalCessAmount > 0 ? finalCessAmount.toFixed(2) + ' (' + cess_rate + '%)' : '-'}</td>
            <td>${totalAmount.toFixed(2)}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="remove_item(this)">Remove</button>
            </td>
            <input type="hidden" id="proddesc_${itemno}" name="proddesc[]" value="${prod_desc}">
            <input type="hidden" id="product_${itemno}" name="products[]" value="${product}">
            <input type="hidden" id="productid_${itemno}" name="productids[]" value="${productid}">
            <input type="hidden" id="qty_${itemno}" name="qtyvalue[]" value="${qty}">
            <input type="hidden" id="price_${itemno}" name="priceval[]" value="${price}">
            <input type="hidden" id="gst_${itemno}" name="gstval[]" value="${gst}">
            <input type="hidden" id="gstamount_${itemno}" name="gstamountval[]" value="${gstAmount.toFixed(2)}">
            <input type="hidden" id="cgst_${itemno}" name="cgstval[]" value="${cgst.toFixed(2)}">
            <input type="hidden" id="sgst_${itemno}" name="sgstval[]" value="${sgst.toFixed(2)}">
            <input type="hidden" id="igst_${itemno}" name="igstval[]" value="${igst.toFixed(2)}">
            <input type="hidden" id="discount_${itemno}" name="discountval[]" value="${discount}">
            <input type="hidden" id="cessrate_${itemno}" name="cessrateval[]" value="${cess_rate}">
            <input type="hidden" id="cessamount_${itemno}" name="cessamountval[]" value="${finalCessAmount.toFixed(2)}">
            <input type="hidden" id="total_${itemno}" name="totalval[]" value="${totalAmount.toFixed(2)}">
            <input type="hidden" id="in_ex_gst_${itemno}" name="in_ex_gst_val[]" value="${in_ex_gst}">
        </tr>
    `;

    // Append Row to Table
    $('#item-list tbody').append(rowHtml);

    // Clear Input Fields
    $('#prod_desc').val('');
    $('#product_choice').val('');
    $('#qty').val(1);
    $('#price').val('');
    $('#discount').val('');
    $('#gst').val('');
    $('#cess_rate').val('');
    $('#cess_amount').val('');

    // Recalculate Totals
    calculate_totals();
}
function calculate_totals() {
    let subtotal = 0;
    let pack_price = parseFloat($('#pack_price').val()) || 0;

    // Loop through each row in the table
    $('#item-list tbody tr').each(function() {
        // Try to get total from both new items and DB items
        const newItemTotal = parseFloat($(this).find('input[name^="totalval"]').val()) || 0;
        const dbItemTotal = parseFloat($(this).find('input[name^="products["][name$="[ptotal]"]').val()) || 0;
        
        // Add whichever total is non-zero
        subtotal += (newItemTotal || dbItemTotal);
    });

    // Update subtotal display and hidden input
    // $('#sub_total').text(subtotal.toFixed(2));
    // $('[name="sub_total"]').val(subtotal.toFixed(2));

   const additionalCharges = parseFloat($('#additional_charges_id').text()) || 0;


  // Get the final cess and GST values
const finalCess = parseFloat($('#final_cess_amount_field').val());
const finalGST = parseFloat($('#final_gst_amount_field').val());

// Calculate the grand total (ensure subtotal, pack_price, and additionalCharges are defined somewhere in your code)
const grandTotal = subtotal + pack_price + additionalCharges + finalCess + finalGST;
    
    
    const roundedGrandTotal = Math.round(grandTotal);

    $('#gtotal').text(roundedGrandTotal.toFixed(2));
    $('[name="total_amount"]').val(roundedGrandTotal);
}

// Update the calc_total function to use the same logic
function calc_total() {
    calculate_totals(); // Use the same calculation logic for consistency
}



function rem_item(_this) {
    _this.closest('tr').remove();
    calculate_totals(); // Use calculate_totals instead of calc_total
}

$(document).ready(function() {
    // Existing pack_price event listener
    $('#pack_price').on('input', function() {
        calculate_totals();
    });

    // Calculate initial totals when page loads
    calculate_totals();
});


$(document).ready(function() {
    console.log("Script loaded and running");

    function updateLineTotal(tr) {
        // var qty = (tr.find('input[name*="[pqty]"]').val()) || 0;
        // var price = (tr.find('input[name*="[pnetprice]"]').val()) || 0;
        // var lineTotal = qty * price;

        // lineTotal = parseFloat(lineTotal.toFixed(2));
        // tr.find('input[name*="[ptotal]"]').val(lineTotal);
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