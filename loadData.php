<?php
	function getData(){
		// Connect to Database
		include('connectionData.txt');
	    $conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');

	    // Getting Set of Titles
	    $query = "SELECT DISTINCT title FROM Book";
	    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
	    $titles = array();
	    while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
	    	array_push($titles, $row[title]);
	    }
	    $data1 = array('titles' => $titles);

	    // Getting Set of Authors
	    $query = "SELECT DISTINCT CONCAT(fname, ' ', lname) as name FROM Author";
	    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
	    $authors = array();
	    while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
	    	array_push($authors, $row[name]);
	    }
	    $data2 = array('authors' => $authors);

	    // Getting Set of Genres
	    $query = "SELECT DISTINCT name FROM Genre";
	    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
	    $genres = array();
	    while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
	    	array_push($genres, $row[name]);
	    }
	    $data3 = array('genres' => $genres);

	    // Getting Set of Library Names
	    $query = "SELECT DISTINCT name FROM Library";
	    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
	    $locations = array();
	    while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
	    	array_push($locations, $row[name]);
	    }
	    $data4 = array('locations' => $locations);

	    // Close Connection
	    mysqli_free_result($result);
        mysqli_close($conn);

	    return array($data1, $data2, $data3, $data4);
	}

	echo json_encode(getData());
?>
