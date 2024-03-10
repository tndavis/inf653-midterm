<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $quotes = new Quote($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));
  if(empty($data->id)){
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  } else {
    // Set ID to update
    $quotes->id = $data->id;
    if($quotes->read_single()){
    // Delete post
    if($quotes->delete()) {
      $quote_arr = array(
        'id' => $data->id
      );
      // Make JSON
      print_r(json_encode($quote_arr));
    } else {
      echo json_encode(
        array('message' => 'No Quotes Found')
      );
    }
  }
  else{
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  }
  }

