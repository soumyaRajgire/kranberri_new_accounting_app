<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITC Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .download-link {
            text-decoration: none;
            color: blue;
            font-weight: bold;
        }
    </style>
    <?php
    include("config.php");
$start_date = '2023-04-01'; // Start of the financial year
$end_date = '2024-03-31';   // End of the financial year

$query = "SELECT 
            MONTHNAME(invoice_date) AS month, 
            YEAR(invoice_date) AS year,
            SUM(ledgers_itc) AS itc_recorded,
            SUM(received_itc) AS itc_received,
            SUM(reconciled_itc) AS reconciled,
            SUM(mismatched_itc) AS mismatch,
            SUM(default_itc) AS defaulted,
            SUM(not_recorded_itc) AS not_recorded
          FROM itc_data
          WHERE invoice_date BETWEEN '$start_date' AND '$end_date'
          GROUP BY YEAR(invoice_date), MONTH(invoice_date)
          ORDER BY invoice_date";

$result = $conn->query($query);
$itc_data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $itc_data[] = $row;
    }
}
?>

</head>
<body>
    <h2 style="text-align: center;">ITC Dashboard (FY: 2023-2024)</h2>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>ITC Recorded (LEDGERS)</th>
                <th>ITC Received</th>
                <th>Reconciled</th>
                <th>Mismatch</th>
                <th>Default</th>
                <th>Purchase Not Recorded</th>
                <th>Excel</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($itc_data as $data): ?>
                <tr>
                    <td><?php echo $data['month'] . ' ' . $data['year']; ?></td>
                    <td><?php echo number_format($data['itc_recorded'], 2); ?></td>
                    <td><?php echo number_format($data['itc_received'], 2); ?></td>
                    <td><?php echo number_format($data['reconciled'], 2); ?></td>
                    <td><?php echo number_format($data['mismatch'], 2); ?></td>
                    <td><?php echo number_format($data['defaulted'], 2); ?></td>
                    <td><?php echo number_format($data['not_recorded'], 2); ?></td>
                    <td><a href="export_excel.php?month=<?php echo $data['month']; ?>" class="download-link">Download</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
