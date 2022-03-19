<?php
    Class Category {
        //db stuff
        private $conn;
        private $table = 'categories';

        //properties
        public $id;
        public $category;

        //constructor
        public function __construct($db){
            $this->conn = $db;
        }

        //get all categories
        public function read(){
            //sql query
            $query = 'SELECT
                        c.id,
                        c.category
                      FROM ' . $this->table . ' c
                      ORDER BY c.id ASC;';
            
            //prepare statement
            $stmt = $this->conn->prepare($query);

            //execute
            $stmt->execute();
            return $stmt;
        }

        public function read_single(){
            //query
            $query = 'SELECT
                        c.id,
                        c.category
                      FROM ' . $this->table . ' c
                      WHERE c.id = ?
                      LIMIT 0, 1;';

            //prepare statement
            $stmt = $this->conn->prepare($query);
            //bind id
            $stmt->bindParam(1, $this->id);
            //execute
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row){
                $this->category = $row['category'];
                $cat_arr = array (
                    'id' => $this->id,
                    'category' => $this->category
                );
                    //result json
                print_r(json_encode($cat_arr));
            }
            else {
                //create a result array
                print_r(json_encode(array('message' => 'categoryId Not Found')));
            }
            
        }



        //create category
        public function create(){
            //create query
            $query = 'INSERT INTO ' . $this->table . ' SET category = :category';
            
            //prepare statement
            $stmt = $this->conn->prepare($query);

            //clean up data
            $this->category = htmlspecialchars(strip_tags($this->category));

            //bind data
            $stmt->bindParam(':category', $this->category);

            //execute
            if ($stmt->execute()){
                //get the category id for the response value
                $newIdQuery = 'SELECT MAX(id) "newCategoryId" from ' . $this->table . ' where category = :category';
                $stmtId = $this->conn->prepare($newIdQuery);
                $stmtId->bindParam(':category', $this->category);
                if ($stmtId->execute()){
                    $row = $stmtId->fetch(PDO::FETCH_ASSOC);
                    extract($row);
                    $this->id = $newCategoryId;
                } else {
                    printf('Error retrieving newly created Category Id: %s.\n', $stmtId->error);
                }
                return true;
            }
            else {
                //print error
                printf('Error: %s.\n', $stmt->error);
                return false;
            }
        }


        //update category
        public function update(){
            //create query
            $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id;';

            //prepare statement
            $stmt = $this->conn->prepare($query);

            //clean up data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));
            //bind data
            $stmt->bindParam(':category', $this->category);
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
                    print_r(json_encode(array('message' => 'categoryId Not Found')));
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

    