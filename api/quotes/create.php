<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    //create db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate quote object
    $quote = new Quote($db);

    //new quote data
    $data = json_decode(file_get_contents('php://input'));

    //create quote
    if (isset($data->quote) && $data->quote != '' //check for quote
        && isset($data->authorId) && $data->authorId != ''
        && isset($data->categoryId) && $data->categoryId != '') {

        $quote->quote = $data->quote;
        $quote->authorId = $data->authorId;
        $quote->categoryId = $data->categoryId;

        if ($quote->create()){
            //need to get the newly created quote id
            echo json_encode(
                array(
                    'id' => strval($quote->id),
                    'quote' => $quote->quote,
                    'authorId' => $quote->authorId,
                    'categoryId' => $quote->categoryId)
            );
        } else {
            echo json_encode(
                array('message' => 'Quote not created')
            );
        }
    }
    else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }
?>