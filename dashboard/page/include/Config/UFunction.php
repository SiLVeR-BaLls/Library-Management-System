<?php

error_reporting(0);

class UFunction{

    private $DBHOST = 'localhost';
    private $DBUSER = 'root';
    private $DBPASS = '';
    private $DBNAME = 'lms';
    public $conn;

    public function __construct(){
        try{
            $this->conn = mysqli_connect($this->DBHOST, $this->DBUSER, $this->DBPASS, $this->DBNAME);
            if(!$this->conn){  
                throw new Exception('Connection was Not Extablish');
            }
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
            
        }

    }

    public function validate($string){
        $string_vali = mysqli_real_escape_string($this->conn, $string);
        return $string_vali;
    }

    public function insert($tb_name, $tb_field){
       
        $q_data = "";

        foreach($tb_field as $q_key => $q_value){
            $q_data = $q_data."$q_key='$q_value',";
        }
        $q_data = rtrim($q_data,",");

        $query = "INSERT INTO $tb_name SET $q_data";
        $insert_fire = mysqli_query($this->conn, $query);
        if($insert_fire){
            return $insert_fire;
        }
        else{
            return false;
        }

    }

    public function select_order_limit($tbl_name, $field_name, $set_limit, $order = "DESC", $conditions = []) {
        $query = "SELECT * FROM $tbl_name";
        $where_clauses = [];
        $params = [];
        $param_types = '';

        // Add conditions dynamically
        foreach ($conditions as $field => $value) {
            $where_clauses[] = "$field = ?";
            $params[] = $value;
            $param_types .= 's';
        }

        if (!empty($where_clauses)) {
            $query .= " WHERE " . implode(" AND ", $where_clauses);
        }

        $query .= " ORDER BY $field_name $order LIMIT ?";
        $params[] = $set_limit;
        $param_types .= 'i';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($param_types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function select_messages($conditions = [], $order_by = "timestamp DESC", $limit = null) {
        $query = "SELECT * FROM messages";
        $where_clauses = [];
        $params = [];

        // Add conditions dynamically
        foreach ($conditions as $field => $value) {
            $where_clauses[] = "$field = ?";
            $params[] = $value;
        }

        if (!empty($where_clauses)) {
            $query .= " WHERE " . implode(" AND ", $where_clauses);
        }

        $query .= " ORDER BY $order_by";

        if ($limit) {
            $query .= " LIMIT ?";
            $params[] = $limit;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

}




?>