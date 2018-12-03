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
                    #instatiate variables
                    $formnumber = $_POST["form_number"];
                    $query = "";
                    $searchoption = "";

                    #different responses for different form numbers
                    if($formnumber == 1){
                        #get the type of search (Title, Author, Genre), and get then get the user input
                        $searchoption = $_POST["search_option"];
                        $searchvalue = $_POST["search_value"];

                        if($searchoption == "Title"){
                            #building query given the search option is "Title"
                            $query = "SELECT DISTINCT title, CONCAT(fname, ' ', lname) as author_name, name, copies  FROM  Book"; 
                            $query = $query." JOIN Author USING (author_id)";
                            $query = $query." JOIN Stock USING (book_id)";
                            $query = $query." JOIN Library USING (library_id)";
                            $query = $query." JOIN Address USING (address_id)";
                            $query = $query." WHERE title LIKE '%".$searchvalue."'";
                            $query = $query." ORDER BY name DESC;";
                        }elseif($searchoption == "Author"){
                            #building query given the search option is "Author"
                            $query = "Author";
                        }elseif($searchoption == "Genre"){
                            #building query given the search option is "Genre"
                            $query = "Genre";
                        }else{
                            #sets query to "Error" if other cases aren't hit.
                            $query = "Error";
                        }
                    }elseif($formnumber == 2){
                        $query = "Location";
                    }elseif($formnumber == 3){
                        $searchoption = $_POST["search_option"];
                    }else{
                        $query = "Employee ID";
                    }
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
                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                ?>

                <!-- Display Results -->
                <center>
                    <?php
                        #sets up for print layout
                        print "<pre>";
                        print "\n";
                        $counter = 0;

                        if($formnumber == 1){
                            if($searchoption == "Title"){
                                #displaying results given the searchoption was "Title"
                                while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
                                    $counter++;
                                    print "\n$row[title] \t\t $row[author_name] \t\t $row[name] \t\t $row[copies]";
                                }
                                if($counter == 0){
                                    print "Unfortunately we do not have this book in stock";
                                }
                            }elseif($searchoption == "Author"){
                                #displaying results given the searchoption was "Author"

                            }elseif($searchoption == "Genre"){
                                #displaying results given the searchoption was "Genre"

                            }else{
                                #errors if previous search options aren't hit
                            }
                        }elseif($formnumber == 2){
                            print "HERE IS DATA 2";
                        }elseif($formnumber == 3){
                            print "HERE IS DATA 3";
                        }else{
                            print "HERE IS DATA 4";
                        }

                        #ends print layout
                        print "</pre>";
                    ?>
                </center>

                <!-- Clean Up -->
                <?php
                    mysqli_free_result($result);
                    mysqli_close($conn);
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