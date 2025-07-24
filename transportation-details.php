  <div class="row border border-dark mt-3">
    <!-- Transportation Details -->
    <div class="col-md-6 col-12 p-0">
        <div class="p-2 invoice-compliance-header" style="background-color: #efefef;border-right: 1px solid black;" onclick="toggleSection('transportDetails', this)">
            <span>TRANSPORTATION DETAILS</span>
            <i class="fas fa-chevron-down rotate-icon"></i>
        </div>
        <div id="transportDetails" class="collapse-content">
            <div class="p-3">
                <!-- Transport Mode Selection -->
                <div class="mb-3">
                    <label class="form-label">Select Transport Mode:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="transportMode" id="none" value="None" checked onchange="showTransportDetails(this.value)">
                        <label class="form-check-label" for="none">None</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="transportMode" id="road" value="Road" onchange="showTransportDetails(this.value)">
                        <label class="form-check-label" for="road">Road</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="transportMode" id="rail" value="Rail" onchange="showTransportDetails(this.value)">
                        <label class="form-check-label" for="rail">Rail</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="transportMode" id="air" value="Air" onchange="showTransportDetails(this.value)">
                        <label class="form-check-label" for="air">Air</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="transportMode" id="ship" value="Ship" onchange="showTransportDetails(this.value)">
                        <label class="form-check-label" for="ship">Ship/Road cum Ship</label>
                    </div>
                </div>

                <!-- Dynamic Content Based on Transport Mode -->
                <div id="transportData">
                    <!-- None Selected -->
                    <div id="noneData" class="transport-mode-data d-none">
                    
                    </div>

                    <!-- Road Selected -->
                    <div id="roadData" class="transport-mode-data d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="roadVehicleNumber">Vehicle Number</label>
                                <input type="text" class="form-control" id="roadVehicleNumber"  name="roadVehicleNumber" placeholder="Enter Vehicle Number">
                            </div>
                            <div class="col-md-6">
                                <label for="driverName">Driver Name</label>
                                <input type="text" class="form-control" id="driverName" name="driverName"  placeholder="Enter Driver Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="licenseNumber">Driver License Number</label>
                                <input type="text" class="form-control" id="licenseNumber" name="licenseNumber" placeholder="Enter License Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadFreightCharges">Freight Charges (USD)</label>
                                   <input type="number" class="form-control" id="roadFreightCharges" name="roadFreightCharges" placeholder="Enter Charges" oninput="calculate_totals()">

                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadInsurance">Insurance Details</label>
                                <input type="text" class="form-control" id="roadInsurance" name="roadInsurance" placeholder="Enter Insurance Details">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadPermit">Permit Number</label>
                                <input type="text" class="form-control" id="roadPermit" name="roadPermit" placeholder="Enter Permit Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadContact">Driver Contact</label>
                                <input type="text" class="form-control" id="roadContact" name="driver_contact" placeholder="Enter Contact Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadDistance">Distance (km)</label>
                                <input type="number" class="form-control" id="roadDistance" name="roadDistance"  placeholder="Enter Distance">
                            </div>
                            <!-- Optional Fields (add this block below each transport-specific div) -->
<!-- Transporter Header with Toggle Button -->
<!-- Centered Transporter Header with Toggle Button -->
<!-- Centered Transporter Header with Toggle Button -->
<!-- <div class="d-flex justify-content-center mt-3">
    <div class="border p-2 d-flex align-items-center" id="transporterHeader">
        <span class="mr-2">TRANSPORTER (OPTIONAL FIELD)</span>
        <button id="toggleButton" class="btn btn-sm">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>
 -->
<!-- Optional Fields Section -->
<!-- <div id="optionalFields" class="mt-3 d-none text-center">
    <div class="container-invoice-new">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <label for="optionalField1">Optional Field 1</label>
                <input type="text" class="form-control" id="optionalField1" name="optionalField1" placeholder="Enter Optional Field 1">
            </div>
            <div class="col-md-12">
                <label for="optionalValue1">Optional Value 1</label>
                <input type="text" class="form-control" id="optionalValue1" name="optionalValue1" placeholder="Enter Optional Value 1">
            </div>
        </div>
    </div>
</div> -->

                        </div>
                    </div>

                    <!-- Rail Selected -->
                    <div id="railData" class="transport-mode-data d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="trainNumber">Train Number</label>
                                <input type="text" class="form-control" id="trainNumber" name="trainNumber" placeholder="Enter Train Number">
                            </div>
                            <div class="col-md-6">
                                <label for="railwayStation">Departure Station</label>
                                <input type="text" class="form-control" id="railwayStation" name="railwayStation" placeholder="Enter Station">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="arrivalStation">Arrival Station</label>
                                <input type="text" class="form-control" id="arrivalStation" name="arrivalStation" placeholder="Enter Arrival Station">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railwayBooking">Booking Reference</label>
                                <input type="text" class="form-control" id="railwayBooking" name="railwayBooking" placeholder="Enter Booking Reference">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railFreightCharges">Freight Charges </label>
                                 <input type="number" class="form-control" id="railFreightCharges" name="railFreightCharges" placeholder="Enter Charges" oninput="calculate_totals()">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railwayCoach">Coach Number</label>
                                <input type="text" class="form-control" id="railwayCoach" name="railwayCoach" placeholder="Enter Coach Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railwaySeat">Seat Number</label>
                                <input type="text" class="form-control" id="railwaySeat" name="railwaySeat"  placeholder="Enter Seat Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railDepartureTime">Departure Time</label>
                                <input type="time" class="form-control" id="railDepartureTime" name="railDepartureTime">
                            </div>
                           <!--  <div class="d-flex justify-content-center mt-3">
    <div class="border p-2 d-flex align-items-center" id="transporterHeader">
        <span class="mr-2">TRANSPORTER (OPTIONAL FIELD)</span>
        <button id="toggleButton" class="btn btn-sm">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div> -->

<!-- Optional Fields Section -->
<!-- <div id="optionalFields" class="mt-3 d-none text-center">
    <div class="container-invoice-new">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <label for="optionalField1">Optional Field 1</label>
                <input type="text" class="form-control" id="optionalField2" name="optionalField2" placeholder="Enter Optional Field 1">
            </div>
            <div class="col-md-12">
                <label for="optionalValue1">Optional Value 1</label>
                <input type="text" class="form-control" id="optionalValue2" name="optionalValue2" placeholder="Enter Optional Value 1">
            </div>
        </div>
    </div>
</div>
 -->                        </div>
                    </div>

                    <!-- Air Selected -->
                    <div id="airData" class="transport-mode-data d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="flightNumber">Flight Number</label>
                                <input type="text" class="form-control" id="flightNumber"  name="flightNumber" placeholder="Enter Flight Number">
                            </div>
                            <div class="col-md-6">
                                <label for="departureAirport">Departure Airport</label>
                                <input type="text" class="form-control" id="departureAirport" name="departureAirport" placeholder="Enter Airport Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="arrivalAirport">Arrival Airport</label>
                                <input type="text" class="form-control" id="arrivalAirport" name="arrivalAirport" placeholder="Enter Airport Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airwayBill">Airway Bill Number</label>
                                <input type="text" class="form-control" id="airwayBill" name="airwayBill" placeholder="Enter Bill Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airFreightCharges">Freight Charges </label>
                                  <input type="number" class="form-control" id="airFreightCharges" name="airFreightCharges" placeholder="Enter Charges" oninput="calculate_totals()">

                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airCargoType">Cargo Type</label>
                                <input type="text" class="form-control" id="airCargoType" name="airCargoType" placeholder="Enter Cargo Type">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airlineName">Airline Name</label>
                                <input type="text" class="form-control" id="airlineName" name="airlineName" placeholder="Enter Airline Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airETA">Estimated Time of Arrival</label>
                                <input type="time" class="form-control" id="airETA" name="airETA">
                            </div>
                            <!-- <div class="d-flex justify-content-center mt-3">
    <div class="border p-2 d-flex align-items-center" id="transporterHeader">
        <span class="mr-2">TRANSPORTER (OPTIONAL FIELD)</span>
        <button id="toggleButton" class="btn btn-sm">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div> -->

<!-- Optional Fields Section -->
<!-- <div id="optionalFields" class="mt-3 d-none text-center">
    <div class="container-invoice-new">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <label for="optionalField1">Optional Field 1</label>
                <input type="text" class="form-control" id="optionalField1" placeholder="Enter Optional Field 1">
            </div>
            <div class="col-md-12">
                <label for="optionalValue1">Optional Value 1</label>
                <input type="text" class="form-control" id="optionalValue1" placeholder="Enter Optional Value 1">
            </div>
        </div>
    </div>
</div> -->
                        </div>
                    </div>
                </div>
                <!-- Ship Selected -->
<div id="shipData" class="transport-mode-data d-none">
    <div class="row">
        <div class="col-md-6">
            <label for="shipVesselName">Vessel Name</label>
            <input type="text" class="form-control" id="shipVesselName" name="shipVesselName" placeholder="Enter Vessel Name">
        </div>
        <div class="col-md-6">
            <label for="shipVoyageNumber">Voyage Number</label>
            <input type="text" class="form-control" id="shipVoyageNumber" name="shipVoyageNumber" placeholder="Enter Voyage Number">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipContainerNumber">Container Number</label>
            <input type="text" class="form-control" id="shipContainerNumber" name="shipContainerNumber" placeholder="Enter Container Number">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipBillOfLading">Bill of Lading Number</label>
            <input type="text" class="form-control" id="shipBillOfLading"  name="shipBillOfLading"  placeholder="Enter Bill of Lading Number">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipPortOfLoading">Port of Loading</label>
            <input type="text" class="form-control" id="shipPortOfLoading" name="shipPortOfLoading" placeholder="Enter Port of Loading">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipPortOfDischarge">Port of Discharge</label>
            <input type="text" class="form-control" id="shipPortOfDischarge" name="shipPortOfDischarge" placeholder="Enter Port of Discharge">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipFreightCharges">Freight Charges </label>
              <input type="number" class="form-control" id="shipFreightCharges" name="shipFreightCharges" placeholder="Enter Charges" oninput="calculate_totals()">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipEstimatedArrival">Estimated Time of Arrival (ETA)</label>
            <input type="date" class="form-control" id="shipEstimatedArrival" name="shipEstimatedArrival">
        </div>
      <!--   <div class="d-flex justify-content-center mt-3">
    <div class="border p-2 d-flex align-items-center" id="transporterHeader">
        <span class="mr-2">TRANSPORTER (OPTIONAL FIELD)</span>
        <button id="toggleButton" class="btn btn-sm" onclick="toggleOptionalFields()">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div> -->

<!-- Optional Fields Section -->
<!-- <div id="optionalFields" class="mt-3 d-none text-center">
    <div class="container-invoice-new">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <label for="optionalField1">Optional Field 1</label>
                <input type="text" class="form-control" id="optionalField1" placeholder="Enter Optional Field 1">
            </div>
            <div class="col-md-12">
                <label for="optionalValue1">Optional Value 1</label>
                <input type="text" class="form-control" id="optionalValue1" placeholder="Enter Optional Value 1">
            </div>
        </div>
    </div>
</div>
 -->

<!-- Optional Fields Section -->
<!-- <div id="optionalFields" class="mt-3 d-none text-center">
    <div class="container-invoice-new">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <label for="optionalField1">Optional Field 1</label>
                <input type="text" class="form-control" id="optionalField1" placeholder="Enter Optional Field 1">
            </div>
            <div class="col-md-12">
                <label for="optionalValue1">Optional Value 1</label>
                <input type="text" class="form-control" id="optionalValue1" placeholder="Enter Optional Value 1">
            </div>
        </div>
    </div>
</div> -->
    </div>
</div>

            </div>
        </div>
    </div>
   <!-- Other Details Section -->
<div class="col-md-6 col-12 p-0">
    <div class="p-2 invoice-compliance-header" style="background-color: #efefef;" onclick="toggleSection('otherDetails', this)">
        <span>OTHER DETAILS</span>
        <i class="fas fa-chevron-down rotate-icon"></i>
    </div>
    <div id="otherDetails" class="collapse-content">
        <!-- Input Fields for Other Details -->
        <div class="p-3">
            <div class="row">
                <!-- PO Number and PO Date -->
                <div class="col-md-6 mb-3">
                    <label for="poNumber">PO Number</label>
                    <input type="text" id="other_poNumber" name="other_poNumber" class="form-control" placeholder="Enter PO Number">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="poDate">PO Date</label>
                    <input type="date" id="other_poDate" name="other_poDate" class="form-control" placeholder="dd-mm-yyyy">
                </div>
                <!-- Challan Number and Due Date -->
                <div class="col-md-6 mb-3">
                    <label for="challanNumber">Challan Number</label>
                    <input type="text" id="challanNumber" name="challanNumber" class="form-control" placeholder="Enter Challan Number">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="dueDate">Due Date</label>
                    <input type="date" id="other_dueDate" name="other_dueDate" class="form-control" placeholder="dd-mm-yyyy">
                </div>
                <!-- EwayBill No and Sales Person -->
                <div class="col-md-6 mb-3">
                    <label for="ewayBill">EwayBill No.</label>
                    <input type="text" id="ewayBill" name="ewayBill" class="form-control" placeholder="Enter EwayBill No">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="salesPerson">Sales Person</label>
                    <input type="text" id="salesPerson" name="salesPerson" class="form-control" placeholder="Sales Person">
                </div>
                <!-- Reverse Charge Checkbox -->
                <div class="col-12 mb-3">
                    <input type="checkbox" id="reverseCharge" name="reverseCharge" value="1">
                    <label for="reverseCharge">Is transaction applicable for Reverse Charge?</label>
                </div>
                <!-- TCS Value and TCS Tax -->
                <div class="col-md-6 mb-3">
                    <label for="tcsValue">TCS Value</label>
                    <input type="text" id="tcsValue" name="tcsValue" class="form-control" placeholder="Enter TCS Value">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tcsTax">Enter TCS Tax</label>
                    <select id="tcsTax" name="tcsTax" class="form-control">
                        <option value="">Percent Wise on taxable...</option>
                        <option value="5">5%</option>
                        <option value="10">10%</option>
                    </select>
                </div>
                <!-- Charges Header -->
<!-- Charges Section -->



            </div>
        </div>
    </div>
</div>


</div>