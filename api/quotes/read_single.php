<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog quote object
  $quotes = new Quote($db);

  // Get ID
  $quotes->id = isset($_GET['id']) ? $_GET['id'] : NULL;

  // Get quote
  if($quotes->read_single()){

  // Create array
  $quotes_arr = array(
    'id' => $quotes->id,
    'quote' => $quotes->quote,
    'author' => $quotes->author_name,
    'category' => $quotes->category_name
  );
 
  // Make JSON
  print_r(json_encode($quotes_arr));
  }
  else{
    // No quotes
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  }