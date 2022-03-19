<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate author object
    $author = new Author($db);

    //query db
    $result = $author->read();
    //get rowcount
    $num = $result->rowCount();

    //check if authors
    if($num>0) {
        $authors_arr = array();
        $authors_arr['data'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $authors_item = array(
                'id' => $id,
                'author' => $author
            );

            //push to data
            array_push($authors_arr['data'], $authors_item);
        }

        //jsonify
        echo json_encode($authors_arr);

    } else {
        echo json_encode(
            array('message'=>'no authors found')
        );
    }

?>