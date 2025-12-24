<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "btl_php"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_GET['district'])) {
    $district = $conn->real_escape_string($_GET['district']);
    $sql_select = "SELECT ward FROM db_diachiward WHERE district = '$district'";
    $result = $conn->query($sql_select);
    
    $wards = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $wards[] = $row['ward'];
        }
    }
    echo json_encode($wards);
}
$conn->close();
?>