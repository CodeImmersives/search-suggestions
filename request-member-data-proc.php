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
    $IDmbr = $_GET['IDmbr'];
    $query = "SELECT * FROM members, images WHERE members.IDmbr = images.foreignID AND isMainPic=1 AND catID=3 AND IDmbr='$IDmbr'";
    $result = mysqli_query($conn, $query);

    $members = array();
    $row = mysqli_fetch_array($result);
    array_push($members, $row);

    echo json_encode($row); // echo a JSON obj (or obj array) back to AJAX

?>