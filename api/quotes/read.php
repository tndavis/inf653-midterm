<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  include_once '../../models/Author.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog quote object
  $quotes = new Quote($db);
  $url = $_SERVER['REQUEST_URI'];
  $url = explode('/', $url);
  $param = array_pop($url);

 // $quotes->author_id = isset($_GET['author_id']) ? $_GET['author_id'] : NULL;
  //$quotes->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : NULL;
  if(str_contains($param, 'author_id')){
  $quotes->author_id = $_GET['author_id'] ?? NULL;
  }
  if(str_contains($param, 'category_id')){
    $quotes->category_id = $_GET['category_id'] ?? NULL;
  }

  // Blog quote query
  $result = $quotes->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any quotes
  if($num > 0) {
    // quote array
    $quotes_arr = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $quotes_item = array(
        'id' => $id,
        'quote' => html_entity_decode($quote),
        'author' => $author_name,
        'category' => $category_name
      );

      // Push to "data"
      array_push($quotes_arr, $quotes_item);
    }

    // Turn to JSON & output
    echo json_encode($quotes_arr);

  } else {
    if(!empty($quotes->author_id) || !empty($quotes->category_id)){
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
        echo json_encode(
          array('message' => 'No Quotes Found')
        );
      }
      
    }
    else{
    // No quotes
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  } 
  }
