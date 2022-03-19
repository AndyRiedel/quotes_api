<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate category object
    $category = new Category($db);

    //query db
    $result = $category->read();
    //get rowcount
    $num = $result->rowCount();

    //check if category
    if($num>0) {
        $cat_arr = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $cat_item = array(
                'id' => $id,
                'category' => $category
            );

            //push to data
            array_push($cat_arr, $cat_item);
        }

        //jsonify
        echo json_encode($cat_arr);

    } else {
        echo json_encode(
            array('message'=>'no categories found')
        );
    }

?>