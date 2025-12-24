<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btl_php"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_GET['city'])) {
    $city = $conn->real_escape_string($_GET['city']);
    $sql_select = "SELECT district FROM db_diachidistrict WHERE city = '$city'";
    $result = $conn->query($sql_select);
    
    $districts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $districts[] = $row['district'];
        }
    }
    echo json_encode($districts);
}
$conn->close();
?>