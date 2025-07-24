

<!-- Modal HTML -->


<div id="gstCredentialsModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="col-md-8 modal-title"> Enter Credentials
</h4>
                <div class="col-md-3 btn-group btn-group-sm btn_filter pull-right tab_shift" role="group" aria-label="Large button group">
                   
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <form action="generateIRN.php" method="POST">
            <div class="modal-body">
                <div class="col-md-12">
             <!-- <label for="gspappid">GSP App ID</label> -->
            <!-- <input type="hidden" id="gspappid" name="gspappid" value="79536E39F216449883720CCD53643D8F" > -->
            
            <!-- <input type="hidden" id="gspappid" name="gspappid" value="771CB8E5C27049A48B38426439175284" > -->
            
            
            
            <!-- <label for="gspappsecret">GSP App Secret</label> -->
            <!-- <input type="hidden" id="gspappsecret" name="gspappsecret" value="EE5EFAACG8434G43E8GA90EG9660E98C3D71" > -->
            
             <!-- <input type="hidden" id="gspappsecret" name="gspappsecret" value="818DBFA5GC86CG4542G8F72GF9F93DD0D49F" > -->
            
            
            <input type="hidden" id="inv_id"  name="inv_id" value="<?php echo $inv_id?>">

            <label for="username">Username</label>
            <input class= "form-control" class="form-control" type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input class="form-control" type="password" id="password" name="password" required>
            </div>
            
        </div>
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="" class="btn btn-primary">Submit</button>
        </div> 
        </form>
            </div>
    
        </div>
    </div>
</div>
        
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all buttons that trigger the modal
        var buttons = document.querySelectorAll("[data-toggle='modal']");
        var modal = document.getElementById("gstCredentialsModal");
        var closeBtn = document.getElementsByClassName("close")[0];

        // Check if modal exists before adding event listeners
        if (modal) {
            buttons.forEach(function(btn) {
                btn.addEventListener("click", function(event) {
                    event.preventDefault(); // Prevent default anchor behavior
                    var invoiceId = btn.getAttribute("data-invoice-id"); // Get invoice ID
                    var inputField = document.getElementById("inv_id");
                    
                    if (inputField) {
                        inputField.value = invoiceId; // Set invoice ID in hidden input field
                    }

                    modal.style.display = "block"; // Show the modal
                });
            });

            // Close modal when clicking the close button
            if (closeBtn) {
                closeBtn.addEventListener("click", function() {
                    modal.style.display = "none";
                });
            }

            // Close modal if clicking outside the modal content
            window.addEventListener("click", function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        }
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function(){
        $('#gstCredentialsModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var invoiceId = button.data('invoice-id'); // Extract info from data-invoice-id attribute
            console.log(invoiceId);
            // Assign invoice ID to the hidden input field
            $('#inv_id').val(invoiceId);
            
            // Also display invoice ID in the modal for user reference
            // $('#display_inv_id').text(invoiceId);
        });
    });
</script>