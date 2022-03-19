<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate quote object
    $quote = new Quote($db);

    //query db
    $result = $quote->read();
    //get rowcount
    $num = $result->rowCount();

    //check if quote
    if($num>0) {
        $quote_arr = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'author' => $author,
                'category' => $category
            );

            //push to data
            array_push($quote_arr, $quote_item);
        }

        //jsonify
        echo json_encode($quote_arr);

    } else {
        echo json_encode(
            array('message'=>'no quotes found')
        );
    }

?>