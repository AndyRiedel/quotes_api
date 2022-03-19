<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //instantiate author object
    $author = new Author($db);

    //get data from client
    $data = json_decode(file_get_contents('php://input'));

    if (isset($data->author) && $data->author != '' && isset($data->id)){
        $author->id = $data->id;
        $author->author = $data->author;

        //update author
        if($author->update()) {
            echo json_encode(
                array('message' => 'Author Updated',
                    'id' => $author->id,
                    'author' => $author->author)
            );
        } else {
            print_r(json_encode(array('message' => 'authorId Not Found')));
        }
    }
    else {
        print_r(json_encode(array('message' => 'Missing Required Parameters')));
    }

?>