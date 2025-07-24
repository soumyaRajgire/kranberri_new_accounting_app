

    <style>
    .modal-backdrop
    {
          display: none;
            
    }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>


<!-- Modal for Canceling E-Way Bill -->
<div id="cancelEWayBillGstCredentialsModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Enter Credentials</h2>
        <form action="cancelEWayBill.php" method="POST">
            <!-- Hidden input for E-way Bill No -->
            
            <label for="e_way_bill_no">Eway bill no</label>
            <input type="text" id="e_way_bill_no" name="e_way_bill_no" value="">

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Submit</button>
        </form>
    </div>
</div>



<script>

    document.addEventListener('DOMContentLoaded', function() {
        // Modal handling for Cancel E-Way Bill
        var buttons = document.querySelectorAll("[data-toggle='modal']");
        var modalEWayBill = document.getElementById("cancelEWayBillGstCredentialsModal");
        var closeBtnEWayBill = modalEWayBill.querySelector(".close");

        // E-Way Bill Modal Logic
        buttons.forEach(function(btn) {
            btn.addEventListener("click", function(event) {
                event.preventDefault(); // Prevent default anchor behavior
                var eWayBillNo = btn.getAttribute("data-ewaybillno"); // Get E-way Bill No
                var inputField = modalEWayBill.querySelector("#e_way_bill_no");

                if (inputField) {
                    inputField.value = eWayBillNo; // Set E-way Bill No in hidden input field
                }

                modalEWayBill.style.display = "block"; // Show E-Way Bill modal
            });
        });

        // Close E-Way Bill Modal Logic
        if (closeBtnEWayBill) {
            closeBtnEWayBill.addEventListener("click", function() {
                modalEWayBill.style.display = "none"; // Hide E-Way Bill modal
            });
        }

        // Close E-Way Bill Modal if clicking outside
        window.addEventListener("click", function(event) {
            if (event.target === modalEWayBill) {
                modalEWayBill.style.display = "none";
            }
        });

      
    });
</script>

<!-- Modal HTML for Canceling E-way Bill -->
<<!-- div id="cancelEWayBillGstCredentialsModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Enter Credentials</h2>
        <form action="cancelEWayBill.php" method="POST">
            
            <input type="text" id="e_way_bill_no" name="e_way_bill_no" value="">

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Submit</button>
        </form>
    </div>
</div> -->

<script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     // Get all buttons that trigger the modal
    //     var buttons = document.querySelectorAll("[data-toggle='modal']");
    //     var modal = document.getElementById("cancelEWayBillGstCredentialsModal");
    //     var closeBtn = document.getElementsByClassName("close")[0];

    //     // Check if modal exists before adding event listeners
    //     if (modal) {
    //         buttons.forEach(function(btn) {
    //             btn.addEventListener("click", function(event) {
    //                 event.preventDefault(); // Prevent default anchor behavior
    //                var eWayBillNo = btn.getAttribute("data-ewaybillno"); // Get E-way Bill No from the button
    //                 var inputField = document.getElementById("e_way_bill_no");


                 


    //                 if (inputField) {
    //                     inputField.value = eWayBillNo; // Set the E-way Bill No in hidden input field
                     
    //                   //  alert("E-way Bill No after setting: " + eWayBillNo + " | Input Field Value: " + inputField.value);
    //                 }


    //                 modal.style.display = "block"; // Show the modal
    //             });
    //         });

    //         // Close modal when clicking the close button
    //         if (closeBtn) {
    //             closeBtn.addEventListener("click", function() {
    //                 modal.style.display = "none";
    //             });
    //         }

    //         // Close modal if clicking outside the modal content
    //         window.addEventListener("click", function(event) {
    //             if (event.target == modal) {
    //                 modal.style.display = "none";
    //             }
    //         });
    //     }
    // });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function(){
        $('#cancelEInvocieGstCredentialsModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var e_way_bill_no = button.data('ewaybillno'); // Extract info from data-invoice-id attribute
            console.log(e_way_bill_no);
            // Assign invoice ID to the hidden input field
            $('#e_way_bill_no').val(e_way_bill_no);
            
          
        });
    });
</script>