<?php
    class Database {
        //db params
        private $host = 'localhost';
        private $db_name = 'quotesdb';
        private $username = 'root';
        private $password = '';
        private $conn;

        //password
        public function __construct(){
            //$this->password = getenv('JAWSDB_PW', false);
            
        }

        //db connect
        public function connect(){
            $this->conn = null;

            try {
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                                        $this->username,
                                        $this->password);
                
                //set errormode
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }   catch(PDOException $e){
                echo 'Connection Error: ' . $e->getMessage();
            }
            return $this->conn;
        }
    }

?>