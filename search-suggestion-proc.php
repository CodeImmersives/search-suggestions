<?php

    /*
    *** // ==== // *** // AUTOCOMPLETE SEARCH SUGGESTION // ==== // **** // ====
    Creating Autocomplete or Search Suggestion Functionality with PHP and MySQL a.k.a. 
    Friendly Search : predict or help compose a user's search query. 

    This application uses Twitter’s Typeahead.js -- a Javascript Library / jQuery plugin which adds autocomplete functionality to search boxes. You still have to make a MySQL database containing the data being searched. The Typeahead plugin does not come with a database!
    */

    $conn = mysqli_connect("localhost", "root", "mysql") or die("Couldn't Connect to MySQL!");
    mysqli_select_db($conn, "socnet2") or die("Couldn't Connect to Database!");

    //CREATE QUERY TO DB AND PUT RECEIVED DATA INTO ASSOCIATIVE ARRAY
    $search = $_GET['search'];
    $query = "SELECT * FROM members WHERE lastName LIKE '%{$search}%' OR firstName LIKE '%{$search}%' OR user LIKE '%{$search}%'";
    $result = mysqli_query($conn, $query);
//    $array = array();
//    while($row = mysqli_fetch_array($result)) {
//        $array[] = array (
//            'label' => $row['city']. ', ' . $row['zip'],
//            'value' => $row['city'],
//        );
//    }
//    //RETURN JSON ARRAY
//    echo json_encode ($array);

    $suggested_members = array();

    while($row = mysqli_fetch_array($result)) {
        
        array_push($suggested_members, $row);
        
    }

    // echo a JSON obj (or obj array) back to AJAX
    echo json_encode($suggested_members);

?>