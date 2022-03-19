<?php
    class Database {
        //db params
        private $host = 'acw2033ndw0at1t7.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
        private $db_name = 'gajoltsx73w8pkr8';
        private $username = 'f33o65ql7jm9mzit';
        private $password = '';
        private $conn;

        //password
        public function __construct(){
            $this->password = getenv('JAWSDB_PW', false);
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