<?php
    Class Quote {
        //db stuff
        private $conn;
        private $table = 'quotes';

        //properties
        public $id;
        public $quote;
        public $categoryId;
        public $authorId;
        public $author;
        public $category;

        //constructor
        public function __construct($db){
            $this->conn = $db;
        }

        //get all 
        public function read(){
            //sql query
            $query = 'SELECT
                        q.id,
                        q.quote,
                        q.authorId,
                        q.categoryId,
                        a.author,
                        c.category
                      FROM ' . $this->table . ' q 
                        INNER JOIN authors a
                            on a.id = q.authorId
                        INNER JOIN categories c
                            on c.id = q.categoryId
                      ORDER BY q.id ASC;';
            
            //prepare statement
            $stmt = $this->conn->prepare($query);

            //execute
            $stmt->execute();
            return $stmt;
        }

        public function read_single(){
            //query
            $query = 'SELECT
                        q.id,
                        q.quote,
                        q.authorId,
                        q.categoryId,
                        a.author,
                        c.category
                      FROM ' . $this->table . ' q
                        INNER JOIN authors a
                            on a.id = q.authorId
                        INNER JOIN categories c
                            on c.id = q.categoryId
                      WHERE q.id = ?
                      LIMIT 0, 1;';

            //prepare statement
            $stmt = $this->conn->prepare($query);
            //bind id
            $stmt->bindParam(1, $this->id);
            //execute
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row){
                $this->quote = $row['quote'];
                $this->authorId = $row['authorId'];
                $this->categoryId = $row['categoryId'];
                $this->author = $row['author'];
                $this->category = $row['category'];
                $quote_arr = array (
                    'id' => $this->id,
                    'quote' => $this->quote,
                    'author' => $this->author,
                    'category' => $this->category

                );
                    //result json
                print_r(json_encode($quote_arr));
            }
            else {
                //create a result array
                print_r(json_encode(array('message' => 'No Quotes Found')));
            }
            
        }

        public function author_query(){
            //query
            $query = 'SELECT
                        q.id,
                        q.quote,
                        q.authorId,
                        q.categoryId,
                        a.author,
                        c.category
                      FROM ' . $this->table . ' q
                        INNER JOIN authors a
                            on a.id = q.authorId
                        INNER JOIN categories c
                            on c.id = q.categoryId
                      WHERE q.authorId = ?;';

            //prepare statement
            $stmt = $this->conn->prepare($query);
            //bind id
            $stmt->bindParam(1, $this->authorId);
            //execute
            $stmt->execute();
            return $stmt;
            
        }  

        public function category_query(){
            //query
            $query = 'SELECT
                        q.id,
                        q.quote,
                        q.authorId,
                        q.categoryId,
                        a.author,
                        c.category
                      FROM ' . $this->table . ' q
                        INNER JOIN authors a
                            on a.id = q.authorId
                        INNER JOIN categories c
                            on c.id = q.categoryId
                      WHERE q.categoryId = ?;';

            //prepare statement
            $stmt = $this->conn->prepare($query);
            //bind id
            $stmt->bindParam(1, $this->categoryId);
            //execute
            $stmt->execute();
            return $stmt;
            
        }  

        public function author_category_query(){
            //query
            $query = 'SELECT
                        q.id,
                        q.quote,
                        q.authorId,
                        q.categoryId,
                        a.author,
                        c.category
                      FROM ' . $this->table . ' q
                        INNER JOIN authors a
                            on a.id = q.authorId
                        INNER JOIN categories c
                            on c.id = q.categoryId
                      WHERE q.authorId = ?
                        AND q.categoryId = ?;';

            //prepare statement
            $stmt = $this->conn->prepare($query);
            //bind id
            $stmt->bindParam(1, $this->authorId);
            $stmt->bindParam(2, $this->categoryId);
            //execute
            $stmt->execute();
            return $stmt;
            
        }  


        //authorId check
        public function authCheck($authId){
            //given an authorId, confirm it exists
            //returns true or false
            $this->authorId = htmlspecialchars(strip_tags($this->authorId));
            $stmt = 'SELECT COUNT(*) "authIdCount"
                    FROM authors 
                    WHERE id = :authorId';
            $stmt->bindParam(':authorId', $this->authorId);
            print_r(var_dump($stmt));
            if ($stmt->execute()){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                print_r(var_dump($row));
                extract($row);
                return $authIdCount > 0;
            }
            else {
                printf('ERROR: %s.\n', $stmt->error);
                return false;
            }

        }

        //create quote
        public function create(){
            //create query
            $query = 'INSERT INTO ' . $this->table . ' 
                SET quote = :quote,
                    authorId = :authorId,
                    categoryId = :categoryId';
            
            //prepare statement
            $stmt = $this->conn->prepare($query);

            //clean up data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->authorId = htmlspecialchars(strip_tags($this->authorId));
            $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));
            //bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':authorId', $this->authorId);
            $stmt->bindParam(':categoryId', $this->categoryId);

            //execute
            if ($stmt->execute()){
                //get the quote id for the response value
                $newIdQuery = 'SELECT MAX(id) "newQuoteId" from ' . $this->table . ' where quote = :quote';
                $stmtId = $this->conn->prepare($newIdQuery);
                $stmtId->bindParam(':quote', $this->quote);
                if ($stmtId->execute()){
                    $row = $stmtId->fetch(PDO::FETCH_ASSOC);
                    extract($row);
                    $this->id = $newQuoteId;
                } else {
                    printf('Error retrieving newly created Quote Id: %s.\n', $stmtId->error);
                }
                return true;
            }
            else {
                //print error
                printf('Error: %s.\n', $stmt->error);
                return false;
            }
        }


        //update quote
        public function update(){
            //create query
            $query = 'UPDATE ' . $this->table . ' 
                        SET quote = :quote,
                            authorId = :authorId,
                            categoryId = :categoryId
                        WHERE id = :id;';

            //prepare statement
            $stmt = $this->conn->prepare($query);
            
            //clean up data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->authorId = htmlspecialchars(strip_tags($this->authorId));
            $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));
            //bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':authorId', $this->authorId);
            $stmt->bindParam(':categoryId', $this->categoryId);

            //execute query
            if ($stmt->execute()){
                $affectedRows = $stmt->rowCount();
                if ($affectedRows == 0) {
                    return false;
                }
                else {
                    return true;
                }
            }
            else {
                printf('Error: %s.\n', $stmt->error);
                return false;
            }

        }

        //delete
        public function delete(){
            //create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id;';
            $stmt = $this->conn->prepare($query);
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(':id', $this->id);
            
            if ($stmt->execute()){
                $affectedRows = $stmt->rowCount();
                if ($affectedRows == 0){
                    print_r(json_encode(array('message' => '‘No Quotes Found')));
                }
                else {
                    print_r(json_encode(array('id' => $this->id)));
                }
            } else {
                printf('Error: %s.\n', $stmt->error);
            }

        }


    }
?>    

    