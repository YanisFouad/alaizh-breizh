<?php

require_once(__DIR__."/Database.php");

enum RequestType: string {
    case UPDATE = "UPDATE";
    case SELECT = "SELECT";
    case INSERT = "INSERT";
    case DELETE = "DELETE";
}

class RequestBuilder {

    private $tableName;
    private $method;
    private $result;
    private $limit;
    private $offset;
    private $sort;
    private $fields2update = array();
    private $whereClausures = array();
    private $fields = array();
    private $jointures = array();

    public function __construct($tableName, $method) {
        $this->tableName = $tableName;
        $this->method = $method;
    }

    public function projection(...$fields) {
        $this->fields = $fields;
        return $this;
    }

    public function where($clausure, $data) {
        $this->whereClausures[$clausure] = $data;
        return $this;
    }

    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }
    
    public function sortBy($field, $dir) {
        $this->sort = [$field, $dir];
        return $this;
    }

    public function innerJoin($otherTable, ...$on) {
        return $this->join("INNER", $otherTable, $on);
    }

    public function naturalJoin($otherTable, $on) {
        return $this->join("NATURAL", $otherTable, $on);
    }

    public function crossJoin($otherTable, ...$on) {
        return $this->join("CROSS", $otherTable, $on);
    }

    public function leftJoin($otherTable, ...$on) {
        return $this->join("LEFT", $otherTable, $on);
    }
    
    public function join($type, $otherTable, ...$on) {
        $this->jointures[] = [$type, $otherTable, $on];
        return $this;
    }

    public function set($field, $value) {
        if($this->method !== RequestType::UPDATE)
            throw new Exception("Invalid request type, only works with 'UPDATE' requests.");
        $this->fields2update[$field] = $value;
        return $this;
    }

    public function execute() {
        $query = array($this->method);
        $params = array();
        
        $query[] = join(",", $this->fields);
        
        // avoid "from" for some request type
        if(!in_array($this->method, [RequestType::UPDATE->value]))
            $query[] = "FROM";

        // if we have fields to update then add it to the query
        foreach($this->fields2update as $field => &$value) {
            $query[] = "SET " . $field . " = ?";
            $params[] = $value;
        }
        
        $query[] = $this->tableName;

        // jointures
        foreach($this->jointures as $jointure) {
            [$type, $otherTable, $on] = $jointure;
            $query[] = $type . " JOIN " . $otherTable . " ON";
            $query[] = join($on, " AND ");
        }

        // build clausures
        if(count($this->whereClausures) > 0) {
            $query[] = "WHERE";
            $clasures = array();
            foreach($this->whereClausures as $clausure => &$data) {
                $clasures[] = $clausure;
                $params[] = $data;
            }
            $query[] = join(" AND ", $clasures);
        }

        if($this->sort) {
            [$field, $dir] = $this->sort;
            $query[] = " ORDER BY " . $field . " " . $dir;
        }
        if($this->offset)
            $query[] = "OFFSET " . $this->offset;
        if($this->limit)
            $query[] = "LIMIT " . $this->limit;

        $request = Database::getConnection()->prepare(join(" ", $query));
        
        foreach($params as $k => &$v)
            $request->bindParam($k+1, $v);
        
        $request->execute();
        $result = $request->fetchAll(PDO::FETCH_ASSOC);

        $this->result = $result;
        return $this;
    }

    public function fetchOne() {
        $this->checkResults();
        $result = $this->result;
        if(count($result) === 0)
            return null;
        return $this->result[0];
    }

    public function fetchMany() {
        $this->checkResults();
        return $this->result;
    }

    private function checkResults() {
        if(!isset($this->result))
            throw new Exception("Please use execute() function before trying to fetch results");
    }

    public static function select($tableName) {
        return new RequestBuilder($tableName, RequestType::SELECT->value);
    }

    public static function update($tableName) {
        return new RequestBuilder($tableName, RequestType::UPDATE->value);
    }

    public static function insert($tableName) {
        return new RequestBuilder($tableName, RequestType::INSERT->value);
    }

    public static function delete($tableName) {
        return new RequestBuilder($tableName, RequestType::DELETE->value);
    }

}