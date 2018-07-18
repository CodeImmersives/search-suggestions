<?php

    /*
    *** // ==== // *** // AUTOCOMPLETE SEARCH SUGGESTION // ==== // **** // ====
    Creating Autocomplete or Search Suggestion Functionality with PHP and MySQL a.k.a. 
    Friendly Search : predict or help compose a user's search query. 

    This application uses Twitter’s Typeahead.js -- a Javascript Library / jQuery plugin which adds autocomplete functionality to search boxes. You still have to make a MySQL database containing the data being searched. The Typeahead plugin does not come with a database!
    */

    //CREDENTIALS FOR DB
    //define ('DBSERVER', 'localhost');
    //define ('DBUSER', 'user');
    //define ('DBPASS','password');
    //define ('DBNAME','dbname');
    $conn = mysqli_connect('localhost', 'root', 'mysql') or die("Couldn't Connect to MySQL!");
    mysqli_select_db($conn, 'zip-gps') or die("Couldn't Connect to Database!");

    ////LET'S INITIATE CONNECT TO DB
    //$connection = mysql_connect(DBSERVER, DBUSER, DBPASS) or die("Can't connect to server. Please check credentials and try again");
    //$result = mysql_select_db(DBNAME) or die("Can't select database. Please check DB name and try again");

    //CREATE QUERY TO DB AND PUT RECEIVED DATA INTO ASSOCIATIVE ARRAY
    // if(isset($_REQUEST['query'])) {
    $search = $_REQUEST['query'];
    $query = "SELECT * FROM zips WHERE city LIKE '%{$search}%' OR zip LIKE '%{$search}%'";
    $result = mysqli_query($conn, $query);
    $array = array();
    while($row = mysqli_fetch_array($result)) {
        $array[] = array (
            'label' => $row['city']. ', ' . $row['zip'],
            'value' => $row['city'],
        );
    }
    //RETURN JSON ARRAY
    echo json_encode ($array);
//    } else 
//        echo "It ain't workin'";
//    }

?>