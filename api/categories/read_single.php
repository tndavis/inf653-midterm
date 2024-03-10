<?php

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();
  // Instantiate blog category object
  $categories = new Category($db);

  // Get ID
  $categories->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Get post
  //$categories->read_single();
  if($categories->read_single()){
  // Create array
  $category_arr = array(
    'id' => $categories->id,
    'category' => $categories->category
  );

  // Make JSON
  print_r(json_encode($category_arr));
}
else{
  echo json_encode(
    array('message' => 'category_id Not Found')
  );
}
