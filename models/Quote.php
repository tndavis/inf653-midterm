<?php 
  class Quote {
    // DB stuff
    private $conn;
    private $table = 'quotes';

    // Quote Properties
    public $id;
    public $quote;
    public $category_id;
    public $author_id;


    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Quotes
    public function read() {
      // Create query - removed ,a.author_id, c.category_id
      if(!empty($this->author_id) && !empty($this->category_id)){
        $query = 'SELECT a.author AS author_name, c.category AS category_name, p.id, p.quote 
                                FROM ' . $this->table . ' p
                                JOIN authors a ON a.id = p.author_id 
                                JOIN categories c ON c.id = p.category_id 
                                WHERE
                                  p.author_id = ' . $this->author_id .
                                ' AND p.category_id = ' . $this->category_id .
                                ' ORDER BY
                                  p.id DESC';
      
      }
      else if (!empty($this->author_id)){
        $query = 'SELECT a.author AS author_name, c.category AS category_name, p.id, p.quote 
                                FROM ' . $this->table . ' p
                                JOIN authors a ON a.id = p.author_id 
                                JOIN categories c ON c.id = p.category_id 
                                WHERE
                                  p.author_id = ' . $this->author_id .
                                ' ORDER BY
                                  p.id DESC';
      }
      else if (!empty($this->category_id)){
        $query = 'SELECT a.author AS author_name, c.category AS category_name, p.id, p.quote 
                                FROM ' . $this->table . ' p
                                JOIN authors a ON a.id = p.author_id 
                                JOIN categories c ON c.id = p.category_id 
                                WHERE
                                  p.category_id = ' . $this->category_id .
                                ' ORDER BY
                                  p.id DESC';
      } else{
      $query = 'SELECT a.author AS author_name, c.category AS category_name, p.id, p.quote 
                                FROM ' . $this->table . ' p
                                JOIN authors a ON a.id = p.author_id 
                                JOIN categories c ON c.id = p.category_id 
                                ORDER BY
                                  p.id DESC';
      }
      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Post
    public function read_single() {
          // Create query - took out LIMIT 1; added if statement below
          $query = 'SELECT a.author AS author_name, c.category AS category_name, p.id, p.quote 
                                FROM ' . $this->table . ' p
                                JOIN authors a ON a.id = p.author_id 
                                JOIN categories c ON c.id = p.category_id 
                                    WHERE
                                      p.id = ?';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Bind ID
          $stmt->bindParam(1, $this->id);

          // Execute query
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          if($row == false){
            return false;
          }
          // Set properties
          $this->id = $row['id'];
          $this->quote = $row['quote'];
          $this->author_name = $row['author_name'];
          $this->category_name = $row['category_name'];
          return true;
    }

    // Create Post
    public function create() {
          // Create query
          $query = 'INSERT INTO ' .
          $this->table . 
          ' (quote, author_id, category_id)' .
          ' VALUES (:quote, :author_id, :category_id) RETURNING id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->quote = htmlspecialchars(strip_tags($this->quote));
          $this->author_id = htmlspecialchars(strip_tags($this->author_id));
          $this->category_id = htmlspecialchars(strip_tags($this->category_id));

          // Bind data
          $stmt->bindParam(':quote', $this->quote);
          $stmt->bindParam(':author_id', $this->author_id);
          $stmt->bindParam(':category_id', $this->category_id);

          // Execute query
          if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
    }

    // Update Post
    public function update() {
          // Create query
          $query = 'UPDATE ' . $this->table . '
                                SET quote = :quote, author_id = :author_id, category_id = :category_id
                                WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->quote = htmlspecialchars(strip_tags($this->quote));
          $this->author_id = htmlspecialchars(strip_tags($this->author_id));
          $this->category_id = htmlspecialchars(strip_tags($this->category_id));
          $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data
          $stmt->bindParam(':quote', $this->quote);
          $stmt->bindParam(':author_id', $this->author_id);
          $stmt->bindParam(':category_id', $this->category_id);
          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }

    // Delete Post
    public function delete() {
          // Create query
          $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

          // Prepare statement
          $stmt = $this->conn->prepare($query);

          // Clean data
          $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data
          $stmt->bindParam(':id', $this->id);

          // Execute query
          if($stmt->execute()) {
            return true;
          }

          // Print error if something goes wrong
          printf("Error: %s.\n", $stmt->error);

          return false;
    }
    
  }