<?php
    Class Author {
        //db stuff
        private $conn;
        private $table = 'authors';

        //properties
        public $id;
        public $author;

        //constructor
        public function __construct($db){
            $this->conn = $db;
        }

        //get all authors
        public function read(){
            //sql query
            $query = 'SELECT
                        a.id,
                        a.author
                      FROM ' . $this->table . ' a
                      ORDER BY a.id ASC;';
            
            //prepare statement
            $stmt = $this->conn->prepare($query);

            //execute
            $stmt->execute();
            return $stmt;
        }

        public function read_single(){
            //query
            $query = 'SELECT
                        a.id,
                        a.author
                      FROM ' . $this->table . ' a
                      WHERE a.id = ?
                      LIMIT 0, 1;';

            //prepare statement
            $stmt = $this->conn->prepare($query);
            //bind id
            $stmt->bindParam(1, $this->id);
            //execute
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row){
                $this->author = $row['author'];
                $author_arr = array (
                    'id' => $this->id,
                    'author' => $this->author
                );
                    //result json
                print_r(json_encode($author_arr));
            }
            else {
                //create a result array
                print_r(json_encode(array('message' => 'authorId Not Found')));
            }
            
        }



        //create author
        public function create(){
            //create query
            $query = 'INSERT INTO ' . $this->table . ' SET author = :author';
            
            //prepare statement
            $stmt = $this->conn->prepare($query);

            //clean up data
            $this->author = htmlspecialchars(strip_tags($this->author));

            //bind data
            $stmt->bindParam(':author', $this->author);

            //execute
            if ($stmt->execute()){
                //get the author id for the response value
                $newIdQuery = 'SELECT MAX(id) "newAuthorId" from ' . $this->table . ' where author = :author';
                $stmtId = $this->conn->prepare($newIdQuery);
                $stmtId->bindParam(':author', $this->author);
                if ($stmtId->execute()){
                    $row = $stmtId->fetch(PDO::FETCH_ASSOC);
                    extract($row);
                    $this->id = $newAuthorId;
                } else {
                    printf('Error retrieving newly created Author Id: %s.\n', $stmtId->error);
                }
                return true;
            }
            else {
                //print error
                printf('Error: %s.\n', $stmt->error);
                return false;
            }
        }


        //update author
        public function update(){
            //create query
            $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id;';

            //prepare statement
            $stmt = $this->conn->prepare($query);

            //clean up data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));
            //bind data
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);

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
                    print_r(json_encode(array('message' => 'authorId Not Found')));
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

    