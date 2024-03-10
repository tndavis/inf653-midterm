<?php
  class Author {
    // DB Stuff
    private $conn;
    private $table = 'authors';

    // Properties
    public $id;
    public $author;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get authors
    public function read() {
      // Create query
      $query = 'SELECT
        id,
        author
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

    // Get Single author
  public function read_single(){
    // Create query
    $query = 'SELECT
          id,
          author
        FROM
          ' . $this->table . '
      WHERE id = ?
      LIMIT 1';

      //Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->id);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if($row == false){
        return false;
      }

      // set properties
      $this->id = $row['id'];
      $this->author = $row['author'];
      return true;
  }

  // Create author
  public function create() {
    // Create Query
    $query = 'INSERT INTO ' .
      $this->table . 
      ' (author)' .
    ' VALUES (:author) RETURNING id';

  // Prepare Statement
  $stmt = $this->conn->prepare($query);

  // Clean data
  $this->author = htmlspecialchars(strip_tags($this->author));

  // Bind data
  $stmt-> bindParam(':author', $this->author);

  // Execute query
  if($stmt->execute()) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->id = $row['id'];
    return true;
  }

  // Print error if something goes wrong
  printf("Error: $s.\n", $stmt->error);

  return false;
  }

  // Update author
  public function update() {
    // Create Query
    $query = 'UPDATE ' .
      $this->table . '
    SET
      author = :author
      WHERE
      id = :id';

  // Prepare Statement
  $stmt = $this->conn->prepare($query);

  // Clean data
  $this->author = htmlspecialchars(strip_tags($this->author));
  $this->id = htmlspecialchars(strip_tags($this->id));

  // Bind data
  $stmt-> bindParam(':author', $this->author);
  $stmt-> bindParam(':id', $this->id);

  // Execute query
  if($stmt->execute()) {
    return true;
  }

  // Print error if something goes wrong
  printf("Error: $s.\n", $stmt->error);

  return false;
  }

  // Delete author
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
