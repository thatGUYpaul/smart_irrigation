<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Irrigation Statistics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        .sidebar { width: 250px; background: #2ecc71; color: white; position: fixed; height: 100%; padding-top: 20px; }
        .sidebar a { display: block; color: white; padding: 16px; text-decoration: none; }
        .sidebar a:hover { background: #27ae60; }
        .sidebar a i { margin-right: 10px; }
        .main { margin-left: 260px; padding: 20px; }
        .dropdown { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="statistics.php" id="checkMoistureBtn"><i class="fas fa-chart-bar"></i> Statistics</a>
        <a href="profile.php" id="updateDetailsBtn"><i class="fas fa-user"></i> Profile</a>
        <a href="logout.php" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="main">
        <h1>Smart Irrigation</h1>
        <h2>Irrigation History</h2>
        <div class="dropdown">
            <label for="timeframe">Select Timeframe:</label>
            <select id="timeframe">
                <option value="week">Past Week</option>
                <option value="month">Past Month</option>
                <option value="2months">Past 2 Months</option>
            </select>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Transaction Type</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be populated here from the database -->
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function fetchLogs(timeframe) {
                fetch(`fetch_logs.php?timeframe=${timeframe}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('table tbody');
                    tableBody.innerHTML = ''; 
                    data.forEach(log => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${log.date}</td>
                            <td>${log.transaction_type}</td>
                            <td>${log.description}</td>
                            <td>${log.amount} liters</td>
                            <td>${log.status}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
            }
            fetchLogs('week');

            document.getElementById('timeframe').addEventListener('change', function() {
                const selectedTimeframe = this.value;
                fetchLogs(selectedTimeframe);
            });
        });
    </script>
</body>
</html>
