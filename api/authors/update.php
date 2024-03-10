<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $authors = new Author($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));
  if(empty($data->author) || empty($data->id)){
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
  else{
    // Set ID to UPDATE
    $authors->id = $data->id;
    if($authors->read_single()){
      $authors->author = $data->author;

      // Update post
      if($authors->update()) {
        $authors->read_single();
        $author_arr = array(
        'id' => $authors->id,
        'author' => $authors->author
      );
      // Make JSON
      print_r(json_encode($author_arr));
      } else {
        echo json_encode(
          array('message' => 'Author not updated')
        );
      }
    } else {
      echo json_encode(
        array('message' => 'author_id Not Found')
      );
    }
  }
