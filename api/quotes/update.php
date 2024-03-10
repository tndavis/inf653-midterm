<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  include_once '../../models/Author.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog quote object
  $quotes = new Quote($db);

  // Get raw quote data
  $data = json_decode(file_get_contents("php://input"));
  if(empty($data->author_id) || empty($data->category_id) || empty($data->quote) || empty($data->id)){
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
  }
  else{
    // Set ID to update
    $quotes->id = $data->id;
    if($quotes->read_single()){
      $quotes->quote = $data->quote;
      $quotes->author_id = $data->author_id;
      $quotes->category_id = $data->category_id;

      $authors = new Author($db);
      $authors->id = $quotes->author_id;
      $categories = new Category($db);
      $categories->id = $quotes->category_id;
      if(!$authors->read_single()){
        echo json_encode(
          array('message' => 'author_id Not Found')
        );
      }
      else if(!$categories->read_single()){
        echo json_encode(
          array('message' => 'category_id Not Found')
        );
      }
      else{
      // Update quote
      if($quotes->update()) {
        $quotes->read_single();
        $quote_arr = array(
          'id' => $quotes->id,
          'quote' => $quotes->quote,
          'author_id' => $quotes->author_id,
          'category_id' => $quotes->category_id
        );
        // Make JSON
        print_r(json_encode($quote_arr));
      } else {
        echo json_encode(
          array('message' => 'No Quotes Found')
        );
      }
    }
    }
    else{
      echo json_encode(
        array('message' => 'No Quotes Found')
      );
    }
  }

