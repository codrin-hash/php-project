<?php

    class Database{
        public $conn;

        public function __construct($config){

            $dsn = 'mysql:' . http_build_query($config, '',';');

            $this->conn = new PDO($dsn, 'root', 'root', [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }

        public function query($query, $params = []){
            $stmt = $this->conn->prepare($query);

            foreach ($params as $key => $value) {
                $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindValue($key, $value, $type);
            }

            $stmt->execute();

            return $stmt;

        }
    }
?>