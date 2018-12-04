<!-- 
    Noah Palmer and Justin Robles
    CIS 451 Databases
    Main PHP

    Description:

    Resources:

 -->
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
                        if($searchoption == "Late Fees"){
                            // Getting the date in the correct format
                            $thisdate = getdate(date("U"));
                            $currDate = $thisdate[year]."-".$thisdate[mon]."-".$thisdate[mday];

                            #building query given the search option is "Late Fees"
                            $query = "SELECT DISTINCT DATEDIFF('".$currDate."', due_date) as days_late, (DATEDIFF('".$currDate."', due_date)*.25) as fee_due  FROM  Customer";
                            $query = $query." JOIN Reservation USING (customer_id)";
                            $query = $query." WHERE customer_id = ".$searchvalue." AND return_date IS NULL";
                            $query = $query." AND '".$currDate."' > due_date;";
                        }elseif($searchoption == "Checked Out Books"){
                            #building query given the search option is "Checked Out Books"
                            $query = "SELECT DISTINCT CONCAT(Customer.fname, ' ' , Customer.lname) as customer_name, Library.name as library_name, title, due_date FROM Customer";
                            $query = $query." JOIN Reservation USING (customer_id)";
                            $query = $query." JOIN Employee USING (employee_id, library_id)";
                            $query = $query." JOIN Library USING (library_id)";
                            $query = $query." JOIN Book USING (book_id)";
                            $query = $query." WHERE customer_id = ".$searchvalue." AND return_date IS NULL;";
                        }

                    }else{
                        #building query given the form number corresponds to search by Customer ID
                        $query = "SELECT DISTINCT CONCAT(Customer.fname, ' ' , Customer.lname) as customer_name, CONCAT(Employee.fname, ' ' , Employee.lname) as employee_name, Library.name as library_name, title, rental_date FROM Employee";
                        $query = $query." JOIN Reservation USING (employee_id, library_id)";
                        $query = $query." JOIN Customer USING (customer_id)";
                        $query = $query." JOIN Library USING (library_id)";
                        $query = $query." JOIN Book USING (book_id)";
                        $query = $query." WHERE employee_id = ".$searchvalue.";";
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
                            print "<div class='grid-container'>\n<div class='grid-item list-head'>Title</div> <div class='grid-item list-head'>Author Name</div> <div class='grid-item list-head'>Library Name</div> <div class='grid-item list-head'>Copies Available</div>";
                            
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
                            print "<div class='grid-container'>\n<div class='grid-item list-head'>Title</div> <div class='grid-item list-head'>Author Name</div> <div class='grid-item list-head'>Library Name</div> <div class='grid-item list-head'>Copies Available</div>";
                            
                            print "</div><div class='list-bar'><hr></div><div class='grid-container'>";
                            
                            while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
                                $counter++;
                                print "\n<div class='grid-item'>$row[title]</div> <div class='grid-item'>$row[author_name]</div> <div class='grid-item'>$row[name]</div> <div class='grid-item'>$row[copies]</div>";
                            }
                            print "</div>";
                            if($counter == 0){
                                print "We don't have a library in our database under that name";
                            }
                        }elseif($formnumber == 3){
                            if($searchoption == "Late Fees"){
                                print "<div class='grid-container2'>\n<div class='grid-item list-head'>Days Late</div> <div class='grid-item list-head'>Fee Due</div>";
                                
                                print "</div><div class='list-bar'><hr></div><div class='grid-container2'>";
                                
                                while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
                                    $counter++;
                                    if($row[days_late] <= 0){
                                        print "\n<div class='grid-item'>0</div> <div class='grid-item'>\$0</div>";
                                    }else{
                                        print "\n<div class='grid-item'>$row[days_late]</div> <div class='grid-item'>\$$row[fee_due]</div>";
                                    }
                                }
                                print "</div>";
                                if($counter == 0){
                                    print "We don't have you in our database!";
                                }
                            }elseif($searchoption == "Checked Out Books"){
                                print "<div class='grid-container'>\n<div class='grid-item list-head'>Customer Name</div> <div class='grid-item list-head'>Library Name</div> <div class='grid-item list-head'>Title</div> <div class='grid-item list-head'>Due Date</div>";
                            
                                print "</div><div class='list-bar'><hr></div><div class='grid-container'>";
                                
                                while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
                                    $counter++;
                                    print "\n<div class='grid-item'>$row[customer_name]</div> <div class='grid-item'>$row[library_name]</div> <div class='grid-item'>$row[title]</div> <div class='grid-item'>$row[due_date]</div>";
                                }
                                print "</div>";
                                if($counter == 0){
                                    print "We don't have you in our database!";
                                }
                            }else{
                                print "Error";
                            }
                        }else{
                            print "<div class='grid-container3'>\n<div class='grid-item list-head'>Customer Name</div> <div class='grid-item list-head'>Employee Name</div> <div class='grid-item list-head'>Library Name</div> <div class='grid-item list-head'>Title</div> <div class='grid-item list-head'>Checkout Date</div>";
                            
                            print "</div><div class='list-bar'><hr></div><div class='grid-container3'>";
                            
                            while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
                                $counter++;
                                print "\n<div class='grid-item'>$row[customer_name]</div> <div class='grid-item'>$row[employee_name]</div> <div class='grid-item'>$row[library_name]</div> <div class='grid-item'>$row[title]</div> <div class='grid-item'>$row[rental_date]</div>";
                            }
                            print "</div>";
                            if($counter == 0){
                                print "We don't have you in our database!";
                            }
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