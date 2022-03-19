<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //create db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate category object
    $category = new Category($db);

    //new category data
    $data = json_decode(file_get_contents('php://input'));

    
    //create category
    if (isset($data->category) && $data->category != ''){
        $category->category = $data->category;
        if($category->create()){
            echo json_encode(
                array(
                    'category' => $category->category,
                    'id' => strval($category->id))
            );
        } else {
            echo json_encode(
                array('message' => 'Category not created')
            );
        }
    }
    else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }
?>