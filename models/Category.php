<?php
  class Category {
    // DB Stuff
    private $conn;
    private $table = 'categories';

    // Properties
    public $id;
    public $category;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get categories
    public function read() {
      // Create query
      $query = 'SELECT
        id,
        category
      FROM
        ' . $this->table . '
      ORDER BY
        id DESC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Category
  public function read_single(){
    // Create query
    $query = 'SELECT
          id,
          category
        FROM
          ' . $this->table . '
      WHERE id = ?
      LIMIT 1';
      //0,1

      //Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->id);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // set properties and check if success
      if($row == false){
        return false;
      }
      else{
      $this->id = $row['id'];
      $this->category = $row['category'];
      return true;
      }
  }

  // Create Category
  public function create() {
    // Create Query
    $query = 'INSERT INTO ' .
      $this->table . 
      ' (category)' .
      ' VALUES (:category) RETURNING id';
      // $query = 'INSERT INTO ' .
      //$this->table . '
      //VALUES
        //category = :category';

  // Prepare Statement
  $stmt = $this->conn->prepare($query);

  // Clean data
  $this->category = htmlspecialchars(strip_tags($this->category));

  // Bind data
  $stmt-> bindParam(':category', $this->category);

  // Execute query
  if($stmt->execute()) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->id = $row['id'];
    //$this->category = $row['category'];
    return true;
  }

  // Print error if something goes wrong
  printf("Error: $s.\n", $stmt->error);

  return false;
  }

  // Update Category
  public function update() {
    // Create Query
    $query = 'UPDATE ' .
      $this->table . '
    SET
      category = :category
      WHERE
      id = :id';

  // Prepare Statement
  $stmt = $this->conn->prepare($query);

  // Clean data
  $this->category = htmlspecialchars(strip_tags($this->category));
  $this->id = htmlspecialchars(strip_tags($this->id));

  // Bind data
  $stmt-> bindParam(':category', $this->category);
  $stmt-> bindParam(':id', $this->id);

  // Execute query
  if($stmt->execute()) {
    return true;
  }

  // Print error if something goes wrong
  printf("Error: $s.\n", $stmt->error);

  return false;
  }

  // Delete Category
  public function delete() {
    // Create query
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // clean data
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind Data
    $stmt-> bindParam(':id', $this->id);

    // Execute query
    if($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: $s.\n", $stmt->error);

    return false;
    }
  }
