<?php
        //headers
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: DELETE');
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Origin,Authorization,X-Requested-With');

        include_once '../../config/Database.php';
        include_once '../../models/Author.php';

        //instantiate db and connect
        $database = new Database();
        $db = $database->connect();

        //instantiate author object
        $author = new Author($db);

        //get input from client
        $data = json_decode(file_get_contents('php://input'));
        if (isset($data->id)){ //check if id was passed
            $author->id = $data->id;    
            //delete author
            $author->delete();
        }
        else {
            print_r(json_encode(array('message' => 'Missing Required Parameters')));
        }

?>