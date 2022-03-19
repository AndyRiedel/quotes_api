<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //intantiate new quote object
    $quote = new Quote($db);

    //get quote id from url
    if (isset($_GET['id'])){ //if an id was passed in the url
        $quote->id = $_GET['id'];
     } else {
        print_r(json_encode(array('message' => 'No Quotes Found')));
        die(); //exit
     } 

    //get quote
    $quote->read_single();


?>