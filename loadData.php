<?php
    include('connectionData.txt');
    $conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');

    $query = "SELECT DISTINCT title FROM Book";

    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $titles = array();
    while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
    	array_push($titles, $row[title]);
    }

    echo json_encode($titles);

?>