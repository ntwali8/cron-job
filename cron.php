<?php
$conn = new mysqli('localhost', 'root', '', 'ntwali');

if ($conn->connect_error) {
    die("connection failed");
}
// Getting a list of all districts from database
$sql = "Select * from district";
$districts = [];
if($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        array_push($districts, $row['name']);
    }
}
//Opening files depending on the district lists from database
for ($x = 0; $x < count($districts); $x++) {
    $districts[$x] .= ".txt";

    $file = fopen($districts[$x], 'r');

    while (!feof($file)) {
        $content = fgets($file);
        $content_array = explode(" ", $content);
        if ($content_array[0] != "\n") {
            list($lname,$fname,$date,$gender,$health_condition,$health_officer) = $content_array;
            $dbinsert = "INSERT INTO 'patient' ('lname', 'fname', 'date', 'gender', 'health_condition', 'health_officer') VALUES ('$lname', '$fname', '$date', '$gender', '$health_condition', '$health_officer')";
            $result = $conn->query($dbinsert);
            if ($result) {
                print_r ("Successfully uploaded");
            }
            else{
                echo ("Didn't upload");
            }
        }
        
    }
    fclose($file);
}
?>
