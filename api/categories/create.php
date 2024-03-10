<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $categories = new Category($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));
  if(empty($data->category)){
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
else{
  $categories->category = $data->category;

  // Create Categories
  if($categories->create()) {
    $categories->read_single();
      $category_arr = array(
        'id' => $categories->id,
        'category' => $categories->category
      );
      // Make JSON
      print_r(json_encode($category_arr));
  } else {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
}
