

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




<!-- Modal HTML -->
<!-- <div id="cancelEInvocieGstCredentialsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Enter Credentials</h2>
        <form action="cancelEInvoice.php" method="POST">
           
            <input type="text" id="irn_no" name="irn_no" value="">

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Submit</button>
        </form>
    </div>
</div> -->

<!-- JavaScript to Handle Modal Behavior -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all buttons that trigger the modal
        var buttons = document.querySelectorAll("[data-toggle='modal']");
        var modal = document.getElementById("cancelEInvocieGstCredentialsModal");
        var closeBtn = document.getElementsByClassName("close")[0];

        // Check if modal exists before adding event listeners
        if (modal) {
            buttons.forEach(function(btn) {
                btn.addEventListener("click", function(event) {
                    event.preventDefault(); // Prevent default anchor behavior
                    var irnNo = btn.getAttribute("data-irn"); // Get invoice ID
                    var inputField = document.getElementById("irn_no");
                    
                    if (inputField) {
                        inputField.value = irnNo; // Set invoice ID in hidden input field
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



<!-- Modal for Canceling E-Invoice -->
<div id="cancelEInvocieGstCredentialsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Enter Credentials</h2>
        <form action="cancelEInvoice.php" method="POST">
             <label for="irn_no">IRN no</label>
            <input type="text" id="irn_no" name="irn_no" value="">

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function(){
        $('#cancelEInvocieGstCredentialsModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var irn = button.data('irn'); 
            console.log(irn);
            alert(irn);
            // Assign invoice ID to the hidden input field
            $('#irn_no').val(irn);
            
          
        });
    });
</script>