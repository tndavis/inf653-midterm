<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
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
  if(empty($data->id)){
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
  else{
    // Set ID to UPDATE
    $categories->id = $data->id;
    if($categories->read_single()){
    // Delete post
    if($categories->delete()) {
      $category_arr = array(
        'id' => $data->id
      );
      // Make JSON
      print_r(json_encode($category_arr));
      /*echo json_encode(
        array('message' => 'Category deleted')
      );*/
    } else {
      echo json_encode(
        array('message' => 'Category not deleted')
      );
    }
  } else{
    echo json_encode(
      array('message' => 'category_id Not Found')
    );
  }
  }
