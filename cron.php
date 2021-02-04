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
    $districts[$x] .= ".txt";//concatinating a .txt extension

    $file = fopen($districts[$x], 'r');
    //loading data from file
    while (!feof($file)) {
        $content = fgets($file);
        $content_array = explode(" ", $content);//creating an array for each line in file
        //check if the array is empty
        if ($content_array[0] != "") {
            //set variables to array content
            list($lname,$fname,$date,$gender,$health_condition,$health_officer) = $content_array;
            //create mysql insert query and executes it
            $result = $conn->query("INSERT INTO `patient`(`lname`, `fname`, `date`, `gender`, `health_condition`, `health_officer`) VALUES ('$lname', '$fname', '$date', '$gender', '$health_condition', '$health_officer')");
            if ($result) {
                print_r ("Successfully uploaded");
            }
            else{
                echo ("Didn't upload");
            }
        }
    }
    fclose($file);
    //reset file
    $reset = fopen($districts[$x], 'w');
    fclose($reset);
}
?>
