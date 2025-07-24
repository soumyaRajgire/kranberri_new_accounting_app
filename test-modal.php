<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dynamic Popup Example</title>
    <link href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css" rel="stylesheet">
    <style>
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 50%; 
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
    </style>
</head>
<body>

<!-- Trigger/Open The Modal -->
<button class="btn btn-sm btn-outline-info" type="button" id="myBtn">
    <i class="fa fa-edit" style="color:red;"></i>
</button>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Edit Item Details</h3>
    <form id="editForm">
        <label for="item">Item</label>
        <input type="text" id="item" name="item" readonly>

        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" required>

        <label for="rate">Rate</label>
        <input type="number" id="rate" name="rate" required>

        <label for="taxable">Taxable</label>
        <input type="number" id="taxable" name="taxable" required>

        <label for="amount_before_tax">Amount Before Tax</label>
        <input type="number" id="amount_before_tax" name="amount_before_tax" required>

        <label for="total">Total</label>
        <input type="number" id="total" name="total" readonly>

        <label for="units">Units</label>
        <select id="units" name="units">
            <option value="pcs">PCS-PIECES</option>
            <option value="box">BOX</option>
        </select>

        <label for="discount">Discount</label>
        <input type="number" id="discount" name="discount" required>

        <button type="submit">Update</button>
    </form>
  </div>

</div>

<!-- Example table -->
<table id="item-list">
    <tbody>
        <!-- Example row with dynamic data -->
        <tr>
            <td>iphone</td>
            <td>
                <button class="btn btn-sm btn-outline-info" type="button" onclick="editItem(this)">
                    <i class="fa fa-edit" style="color:red;"></i>
                </button>
            </td>
        </tr>
    </tbody>
</table>

<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

    function editItem(button) {
        var row = button.parentNode.parentNode;
        var item = row.cells[0].innerText;
        
        // Assuming you have more dynamic data, populate the form fields here
        document.getElementById("item").value = item;
        document.getElementById("quantity").value = 1; // replace with dynamic value
        document.getElementById("rate").value = 59322.03; // replace with dynamic value
        document.getElementById("taxable").value = 59322.03; // replace with dynamic value
        document.getElementById("amount_before_tax").value = 59322.03; // replace with dynamic value
        document.getElementById("total").value = 59322.03; // replace with dynamic value
        document.getElementById("units").value = 'pcs'; // replace with dynamic value
        document.getElementById("discount").value = 10; // replace with dynamic value

        modal.style.display = "block";
    }
</script>

</body>
</html>
