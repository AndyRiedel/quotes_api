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

    //query based on passed params
        //authorId is set and not empty AND categoryId is not set or is empty 
    if ((isset($_GET['authorId']) && $_GET['authorId'] != '') 
        && (!isset($_GET['categoryId']) || $_GET['categoryId']=='')){ //if only id was passed in the url
        //set authorId var
        $quote->authorId = $_GET['authorId'];
        $result = $quote->author_query();
     } //categoryId is set and not empty AND authorId is not set or is empty
     elseif ((!isset($_GET['authorId']) || $_GET['authorId']=='')
                && (isset($_GET['categoryId']) && isset($_GET['categoryId']))){//if only category id was passed
        $quote->categoryId = $_GET['categoryId'];
        $result = $quote->category_query();
     }
     elseif (isset($_GET['authorId']) && $_GET['authorId'] != '' 
                && isset($_GET['categoryId']) && $_GET['categoryId'] != '' ){//if authorid and categoryid passed
        $quote->categoryId = $_GET['categoryId'];
        $quote->authorId = $_GET['authorId'];
        $result = $quote->author_category_query();
     }
     else {
        $result = $quote->read();
     } 

     //assume here we have returned with a result
    //get result record count
     $num = $result->rowCount();
     //if we have results
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
            array('message'=>'No Quotes Found')
        );
    }

     


?>