<div class="modal-header">
  <h5>Create Work Order</h5>
</div>

<div class="modal-body">
  <div class="row">
    <div class="col-md-6">
      <label>Work Order No</label>
      <input type="text" class="form-control" readonly value="WO-2025-001">
    </div>
    <div class="col-md-6">
      <label>Work Order Date</label>
      <input type="date" class="form-control" value="2025-07-13">
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <label>Supplier</label>
      <select class="form-control">
        <option>Select Supplier</option>
        <option>Navkar Exports</option>
        <option>ABC Stitching</option>
      </select>
    </div>
    <div class="col-md-6">
      <label>Batch No</label>
      <input type="text" class="form-control" readonly value="K2484">
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <label>Fabric Issued (Mtrs)</label>
      <input type="number" class="form-control">
    </div>
  </div>

  <hr>

  <h6>Size-wise Breakdown</h6>
  <table class="table">
    <thead>
      <tr>
        <th>Size</th>
        <th>Quantity</th>
        <th>Consumption per Piece (Mtr)</th>
        <th>Total Consumption (Mtr)</th>
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>S</td>
        <td><input type="number" class="form-control"></td>
        <td><input type="number" class="form-control"></td>
        <td><input type="text" class="form-control" readonly></td>
        <td><input type="text" class="form-control"></td>
      </tr>
      <!-- Repeat for M, L, XL, XXL, XXXL -->
    </tbody>
  </table>

  <div class="row">
    <div class="col-md-6">
      <label>Total Shirts Qty</label>
      <input type="text" class="form-control" readonly>
    </div>
    <div class="col-md-6">
      <label>Total Fabric Consumption (Mtr)</label>
      <input type="text" class="form-control" readonly>
    </div>
  </div>

  <div class="row mt-2">
    <div class="col-md-12">
      <label>Remarks</label>
      <textarea class="form-control"></textarea>
    </div>
  </div>

  <div class="row mt-2">
    <div class="col-md-6">
      <label>Status</label>
      <select class="form-control">
        <option>Open</option>
        <option>In Progress</option>
        <option>Completed</option>
      </select>
    </div>
  </div>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-primary">Save Work Order</button>
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
</div>
