<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate quote object
    $quote = new Quote($db);

    //get data from client
    $data = json_decode(file_get_contents('php://input'));
    
    if (isset($data->quote) && $data->quote != '' //check for quote
    && isset($data->authorId) && $data->authorId != ''
    && isset($data->categoryId) && $data->categoryId != ''
    && isset($data->id) && $data->id != '' ) {
        $quote->id = $data->id;
        $quote->quote = $data->quote;
        $quote->authorId = $data->authorId;
        $quote->categoryId = $data->categoryId;

        //update quote
        if($quote->update()) {
            echo json_encode(
                array(
                    'id' => $quote->id,
                    'quote' => $quote->quote,
                    'authorId' => $quote->authorId,
                    'categoryId' => $quote->categoryId)
            );
        } else {
            print_r(json_encode(array('message' => 'Quote Not Found')));
        }
    }
    else {
        print_r(json_encode(array('message' => 'Missing Required Parameters')));
    }

?>