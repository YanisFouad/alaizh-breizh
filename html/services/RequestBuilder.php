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
    private $distinct;
    private $whereClausures = array();
    private $fields = array();


    public function __construct($tableName, $method) {
        $this->tableName = $tableName;
        $this->method = $method;
    }

    public function projection(...$fields) {
        $this->fields = $fields;
        return $this;
    }

    public function where($clausure, ...$data) {
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

    public function distinct() {
        $this->distinct = true;
        return $this;
    }

    public function execute() {
        $query = array($this->method);
        $params = array();

        if(isset($this->distinct)) {
            $query[] = "DISTINCT";
        }

        if(isset($this->distinct))
            $query[] = "DISTINCT";
        
        $query[] = join(",", $this->fields);
        
        // avoid "from" for some request type
        if(!in_array($this->method, [RequestType::UPDATE->value]))
            $query[] = "FROM";
        
        $query[] = $this->tableName;

        // jointures
        foreach($this->jointures as $jointure) {
            [$type, $otherTable, $on] = $jointure;
            $query[] = $type . " JOIN " . $otherTable . " ON";
            foreach($on as $cond) 
                $query[] = join(" AND ", $cond);
        }

        // build clausures
        if(count($this->whereClausures) > 0) {
            $query[] = "WHERE";
            $clasures = array();
            foreach($this->whereClausures as $clausure => $data) {
                $clasures[] = $clausure;
                foreach($data as $d)
                    $params[] = $d;
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
        return $result[0];
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