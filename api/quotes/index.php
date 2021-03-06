<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST, PUT, DELETE, GET');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Origin,Content-Type, Access-Control-Allow-Methods,Authorization, X-Requested-With');


    $request_method = $_SERVER['REQUEST_METHOD'];

    switch ($request_method){
        case 'GET':
            if (isset($_GET['id'])){
                require 'read_single.php';
            }
            elseif (isset($_GET['authorId']) || isset($_GET['categoryId'])){
                require 'auth_cat_query.php';
            }
            else {
                require 'read.php';
            }
            break;
        case 'POST':
            require 'create.php';
            break;
        case 'PUT':
            require 'update.php';
            break;
        case 'DELETE':
            require 'delete.php';
            break;

    }






?>