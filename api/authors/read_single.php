<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //intantiate new author object
    $author = new Author($db);

    //get author id from url
    if (isset($_GET['id'])){ //if an id was passed in the url
        $author->id = $_GET['id'];
     } else {
        print_r(json_encode(array('message' => 'authorId Not Found')));
        die(); //exit
     } 

    //get author
    $author->read_single();


?>