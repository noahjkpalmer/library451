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
                    $searchvalue = $_POST["search_value"];
                    $query = "";
                    $searchoption = "";

                    #different responses for different form numbers
                    if($formnumber == 1){
                        #get the type of search (Title, Author, Genre), and get then get the user input
                        $searchoption = $_POST["search_option"];

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
                            $query = "SELECT DISTINCT title, CONCAT(fname, ' ', lname) as author_name, name, copies  FROM  Book";
                            $query = $query." JOIN Author USING (author_id)";
                            $query = $query." JOIN Stock USING (book_id)";
                            $query = $query." JOIN Library USING (library_id)";
                            $query = $query." JOIN Address USING (address_id)";
                            $query = $query." WHERE CONCAT(fname, ' ', lname) LIKE '%".$searchvalue."%'";
                            $query = $query." ORDER BY name DESC;";
                        }elseif($searchoption == "Genre"){
                            #building query given the search option is "Genre"
                            $query = "SELECT DISTINCT title, CONCAT(fname, ' ', lname) as author_name, Library.name as name, copies  FROM  Book";
                            $query = $query." JOIN Author USING (author_id)";
                            $query = $query." JOIN Stock USING (book_id)";
                            $query = $query." JOIN Library USING (library_id)";
                            $query = $query." JOIN Address USING (address_id)";
                            $query = $query." JOIN Genre USING (genre_id)";
                            $query = $query." WHERE Genre.name LIKE '%".$searchvalue."%'";
                            $query = $query." ORDER BY name DESC;";
                        }else{
                            #sets query to "Error" if other cases aren't hit.
                            $query = "Error";
                        }
                    }elseif($formnumber == 2){
                        #building query given the form number corresponds to search by Location
                        $query = "SELECT DISTINCT title, CONCAT(fname, ' ', lname) as author_name, Library.name as name, copies  FROM  Book";
                        $query = $query." JOIN Author USING (author_id)";
                        $query = $query." JOIN Stock USING (book_id)";
                        $query = $query." JOIN Library USING (library_id)";
                        $query = $query." JOIN Address USING (address_id)";
                        $query = $query." JOIN Genre USING (genre_id)";
                        $query = $query." WHERE Library.name LIKE '%".$searchvalue."%'";
                        $query = $query." ORDER BY name DESC;";
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
                        print "\n";
                        $counter = 0;

                        if($formnumber == 1){
                            print "<div class='grid-container'>";
                            print "\n<div class='grid-item list-head'>Title</div> <div class='grid-item list-head'>Author Name</div> <div class='grid-item list-head'>Library Name</div> <div class='grid-item list-head'>Copies Available</div>";
                            
                            print "</div><div class='list-bar'><hr></div><div class='grid-container'>";
                            
                            while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
                                $counter++;
                                print "\n<div class='grid-item'>$row[title]</div> <div class='grid-item'>$row[author_name]</div> <div class='grid-item'>$row[name]</div> <div class='grid-item'>$row[copies]</div>";
                            }
                            print "</div>";
                            if($counter == 0){
                                if($searchoption == "Title"){
                                    print "Unfortunately we do not have this book in stock";
                                }elseif($searchoption == "Author"){
                                    print "Unfortunately we do not have books by this author";
                                }elseif($searchoption == "Genre"){
                                    print "Unfortunately we do not have books in this genre";
                                }else{
                                    #errors if previous search options aren't hit
                                    print "ERROR";
                                }
                            }
                        }elseif($formnumber == 2){
                            print "<div class='grid-container'>";
                            print "\n<div class='grid-item list-head'>Title</div> <div class='grid-item list-head'>Author Name</div> <div class='grid-item list-head'>Library Name</div> <div class='grid-item list-head'>Copies Available</div>";
                            
                            print "</div><div class='list-bar'><hr></div><div class='grid-container'>";
                            
                            while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
                                $counter++;
                                print "\n<div class='grid-item'>$row[title]</div> <div class='grid-item'>$row[author_name]</div> <div class='grid-item'>$row[name]</div> <div class='grid-item'>$row[copies]</div>";
                            }
                            print "</div>";
                        }elseif($formnumber == 3){
                            print "HERE IS DATA 3";
                        }else{
                            print "HERE IS DATA 4";
                        }
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