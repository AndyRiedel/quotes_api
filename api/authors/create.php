<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //create db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate author object
    $author = new Author($db);

    //new author data
    $data = json_decode(file_get_contents('php://input'));

    
    //create author
    if (isset($data->author) && $data->author != ''){
        $author->author = $data->author;
        if($author->create()){
            echo json_encode(
                array('message' => 'Author Created',
                    'author' => $author->author,
                    'id' => $author->id)
            );
        } else {
            echo json_encode(
                array('message' => 'Author not created')
            );
        }
    }
    else {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }
?>