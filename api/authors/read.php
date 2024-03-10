<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author object
  $authors = new Author($db);

  // Author read query
  $result = $authors->read();
  
  // Get row count
  $num = $result->rowCount();

  // Check if any authors
  if($num > 0) {
        // Cat array
        $cat_arr = array();
        //$cat_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $cat_item = array(
            'id' => $id,
            'author' => $author
            //'name' => $name
          );

          // Push to "data"
          array_push($cat_arr, $cat_item);
        }

        // Turn to JSON & output
        echo json_encode($cat_arr);

  } else {
        // No authors
        echo json_encode(
          array('message' => 'No Authors Found')
        );
  }
