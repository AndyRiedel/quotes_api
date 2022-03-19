<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate category object
    $category = new Category($db);

    //get data from client
    $data = json_decode(file_get_contents('php://input'));

    if (isset($data->category) && $data->category != '' && isset($data->id)){
        $category->id = $data->id;
        $category->category = $data->category;

        //update category
        if($category->update()) {
            echo json_encode(
                array('message' => 'Category Updated',
                    'id' => $category->id,
                    'category' => $category->category)
            );
        } else {
            print_r(json_encode(array('message' => 'categoryId Not Found')));
        }
    }
    else {
        print_r(json_encode(array('message' => 'Missing Required Parameters')));
    }

?>