<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            margin: 5px 0;
        }
        .sidebar a:hover {
            background-color: #495057;
            border-radius: 5px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .metric-card {
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
            transition: transform 0.2s;
        }
        .metric-card:hover {
            transform: scale(1.05);
        }
        .chart-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #0d6efd;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn-custom:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h5 class="text-center mb-4">Logo</h5>
        <a href="#revenue" class="active"><i class="fas fa-chart-line me-2"></i> Revenue</a>
        <a href="#expenses"><i class="fas fa-coins me-2"></i> Expenses</a>
        <a href="#compliance"><i class="fas fa-check-circle me-2"></i> Compliance</a>
        <a href="#banking"><i class="fas fa-university me-2"></i> Banking</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <nav class="navbar navbar-light bg-light mb-4">
            <div class="container-fluid">
                <h4 class="mb-0">Dashboard</h4>
                <div class="d-flex align-items-center">
                    <i class="fas fa-bell me-3"></i>
                    <img src="https://via.placeholder.com/40" alt="Profile" class="rounded-circle">
                </div>
            </div>
        </nav>

        <!-- Metrics Section -->
        <div class="row">
            <div class="col-md-3">
                <div class="metric-card">
                    <h5>Revenue</h5>
                    <p class="text-muted">Current Month</p>
                    <h3>$10,000</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <h5>Expenses</h5>
                    <p class="text-muted">Current Month</p>
                    <h3>$2,500</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <h5>Profit</h5>
                    <p class="text-muted">Current Month</p>
                    <h3>$7,500</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <h5>Bank Balance</h5>
                    <p class="text-muted">Current Month</p>
                    <h3>$15,000</h3>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <h5>Revenue Trend</h5>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h5>Expense Breakdown</h5>
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-md-6">
                <button class="btn btn-custom w-100">Add Bank Account</button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-custom w-100">Create Voucher</button>
            </div>
        </div>
    </div>

    <!-- Chart.js for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [5000, 7000, 6000, 8000, 9000, 10000],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Expense Chart
        const expenseCtx = document.getElementById('expenseChart').getContext('2d');
        const expenseChart = new Chart(expenseCtx, {
            type: 'pie',
            data: {
                labels: ['Marketing', 'Operations', 'Salaries'],
                datasets: [{
                    data: [2000, 3000, 1500],
                    backgroundColor: ['#dc3545', '#ffc107', '#28a745']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>