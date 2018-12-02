<?php
    include('connectionData.txt');
    $conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');
?>

<html>
    <head>
        <title>Library 451</title>
        <link rel="stylesheet" href="main.css">
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

            <div id="query">
                <!-- Build Query -->
                <?php
                    $formnumber = $_POST["form_number"];
                    if($formnumber == 1){
                        $query = "thequery";
                    }elseif($formnumber == 2){
                        $query = "aquery";
                    }elseif($formnumber == 3){
                        $query = "myquery";
                    }else{
                        $query = "yourquery";
                    }
                    print $_POST["t_a_g"];
                ?>

                <!-- Print the Query -->
                <center>
                    <p>
                        The Query:
                    </p>
                    <?php
                        print $query;
                    ?>
                </center>
                
                <!-- Break Line -->
                <br><hr>

                <!-- Run Query -->

                <?php
                    // $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                ?>

                <!-- Display Results -->
                <center>
                    <?php
                        if($formnumber == 1){
                            print "HERE IS DATA 1";
                        }elseif($formnumber == 2){
                            print "HERE IS DATA 2";
                        }elseif($formnumber == 3){
                            print "HERE IS DATA 3";
                        }else{
                            print "HERE IS DATA 4";
                        }
                    ?>
                </center>

                <!-- Clean Up -->
                <?php
                    // mysqli_free_result($result);
                    // mysqli_close($conn);
                ?>
            </div>


            <!-- Break Line -->
            <hr>

            <center>
                <button class="return">Home</button>
                <button class="viewsource">Contents of this Page</button>
            </center>
        </div>
    </body>
    <footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript">
            $(".return").click(function(){
                window.location.href = "index.html";
            });
            $(".viewsource").click(function(){
                window.location.href = "main_php.txt";
            });
        </script>
    </footer>
</html>