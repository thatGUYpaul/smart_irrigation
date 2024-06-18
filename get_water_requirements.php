<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smart_irrigation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['user_id'])) {
    echo json_encode(['error' => 'User ID is required.']);
    exit();
}

$user_id = intval($_GET['user_id']);

$query = "
    SELECT c.water_per_day 
    FROM users u
    JOIN crops c ON u.crop_id = c.id
    WHERE u.id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['water_per_day' => $row['water_per_day']]);
} else {
    echo json_encode(['error' => 'User or crop not found']);
}

$stmt->close();
$conn->close();
?>
