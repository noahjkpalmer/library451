<?php
    include('../connectionData.txt');
    $conn = mysqli_connect($server, $user, $pass, $dbname, $port)
    or die('Error connecting to MySQL server.');
?>

<html>
    <head>
        <title>Library 451</title>
        <link rel="stylesheet" href="../main.css">
    </head>
    <body>
        <div>
            <!-- Header -->
            <div>
                <center>
                    <h1>Library 451</h1>
                </center>
            </div>

            <!-- Break Line -->
            <hr>

        </div>
    </body>
</html>