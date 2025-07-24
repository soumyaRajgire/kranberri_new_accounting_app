<?php
// Include your database connection
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data from POST
    $invoice_id = $_POST['invoice_id'];
    $vehicle_number = $_POST['vehicle_number'];
    $distance = $_POST['distance'];
    $driver_name = $_POST['driver_name'];
    $license_number = $_POST['license_number'];
    $insurance_details = $_POST['insurance_details'];
    $permit_number = $_POST['permit_number'];
    $driver_contact = $_POST['driver_contact'];

    // Additional fields for other transport modes (if provided)
    $train_number = isset($_POST['train_number']) ? $_POST['train_number'] : null;
    $departure_station = isset($_POST['departure_station']) ? $_POST['departure_station'] : null;
    $arrival_station = isset($_POST['arrival_station']) ? $_POST['arrival_station'] : null;
    $booking_reference = isset($_POST['booking_reference']) ? $_POST['booking_reference'] : null;
    $coach_number = isset($_POST['coach_number']) ? $_POST['coach_number'] : null;
    $seat_number = isset($_POST['seat_number']) ? $_POST['seat_number'] : null;
    $departure_time = isset($_POST['departure_time']) ? $_POST['departure_time'] : null;
    $flight_number = isset($_POST['flight_number']) ? $_POST['flight_number'] : null;
    $departure_airport = isset($_POST['departure_airport']) ? $_POST['departure_airport'] : null;
    $arrival_airport = isset($_POST['arrival_airport']) ? $_POST['arrival_airport'] : null;
    $airway_bill = isset($_POST['airway_bill']) ? $_POST['airway_bill'] : null;
    $cargo_type = isset($_POST['cargo_type']) ? $_POST['cargo_type'] : null;
    $airline_name = isset($_POST['airline_name']) ? $_POST['airline_name'] : null;
    $estimated_arrival = isset($_POST['estimated_arrival']) ? $_POST['estimated_arrival'] : null;
    $vessel_name = isset($_POST['vessel_name']) ? $_POST['vessel_name'] : null;
    $voyage_number = isset($_POST['voyage_number']) ? $_POST['voyage_number'] : null;
    $container_number = isset($_POST['container_number']) ? $_POST['container_number'] : null;
    $bill_of_lading = isset($_POST['bill_of_lading']) ? $_POST['bill_of_lading'] : null;
    $port_of_loading = isset($_POST['port_of_loading']) ? $_POST['port_of_loading'] : null;
    $port_of_discharge = isset($_POST['port_of_discharge']) ? $_POST['port_of_discharge'] : null;

    // Validate required fields
    if (empty($vehicle_number) || empty($distance) || empty($driver_name) || empty($license_number)) {
        // If required fields are empty, return error
        echo "Error: Required transportation details are missing!";
        exit;
    }

    // Insert data into transportation_details table
    $query = "INSERT INTO transportation_details (
                    invoice_id, vehicle_number, distance, driver_name, license_number, 
                    insurance_details, permit_number, driver_contact, train_number, 
                    departure_station, arrival_station, booking_reference, coach_number, 
                    seat_number, departure_time, flight_number, departure_airport, 
                    arrival_airport, airway_bill, cargo_type, airline_name, estimated_arrival, 
                    vessel_name, voyage_number, container_number, bill_of_lading, port_of_loading, port_of_discharge
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        'issssssssssssssssssssssss',
        $invoice_id, $vehicle_number, $distance, $driver_name, $license_number,
        $insurance_details, $permit_number, $driver_contact, $train_number,
        $departure_station, $arrival_station, $booking_reference, $coach_number,
        $seat_number, $departure_time, $flight_number, $departure_airport,
        $arrival_airport, $airway_bill, $cargo_type, $airline_name, $estimated_arrival,
        $vessel_name, $voyage_number, $container_number, $bill_of_lading, $port_of_loading, $port_of_discharge
    );

    if ($stmt->execute()) {
        // Success response
        echo "success";
    } else {
        // Error in saving details
        echo "Error: Unable to save transportation details.";
    }

    // Close statement
    $stmt->close();
}
?>
