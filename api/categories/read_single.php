<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    //instantiate db and connect
    $database = new Database();
    $db = $database->connect();

    //intantiate new category object
    $category = new Category($db);

    //get category id from url
    if (isset($_GET['id'])){ //if an id was passed in the url
        $category->id = $_GET['id'];
     } else {
        print_r(json_encode(array('message' => 'categoryId Not Found')));
        die(); //exit
     } 

    //get category
    $category->read_single();


?>