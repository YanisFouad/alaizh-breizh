<?php
require_once(__DIR__."/Database.php");

class SchemaValidationException extends Exception { }

class Model {
    private $tableName;
    private $schema = array();
    private $data = array();

    // is it a new row ?
    private $isNew;

    public function __construct($tableName, $schema, $defaultData = null, $isNew = true) {
        $this->tableName = $tableName;
        $this->schema = $schema;
        if($defaultData)
            $this->data = $defaultData;
        $this->isNew = $isNew;
    }

    /**
     * set a field value to the created model
     */
    public function set($key, $value) {
        if(!in_array($key, array_keys($this->schema)))
            throw new SchemaValidationException("Table column: '" . $key . "' not found in defined schema");
        $this->data[$key] = $value;
    }

    /**
     * verify if a key exists or not
     * @return boolean if the key exists or not
     */
    public function has($key) {
        return array_key_exists($key, $this->data);
    }

    /**
     * retrieve a key from data
     * @return object|null the data associated, or null if not found.
     */
    public function get($key) {
        $value = $this->data[$key] ?? null;
        $props = $this->schema[$key];

        if(array_key_exists("type", $props)) {
            if($props["type"] === "date")
                $value = date_create($value);
        }

        // getters are over than default variables
        if(array_key_exists("get", $props)) {
            $getter = $props["get"];
            return is_callable($getter) ? 
                $getter($this->data) : $getter;
        }
        if(!isset($value)) {
            if(array_key_exists("default", $props))
                return $props["default"];
        }
        return $value;
    }

    /**
     * compare setted data to the schema to see if it match with the defined schema
     */
    private function checkSchema() {
        foreach($this->schema as $field => $props) {
            // first check if we have a required prop and it's true
            if(array_key_exists("required", $props) && $props["required"]) {
                if(!array_key_exists($field, $this->data)) {
                    throw new SchemaValidationException("Missing required field '" . $field . "'.");
                }
            }
            
            if(array_key_exists("type", $props) && array_key_exists($field, $this->data) && !is_a($this->data[$field], $props["type"])) {
                throw new SchemaValidationException("Validation failed: invalid type for field '" . $field . "', type excepted: '" . $props["type"] . "', actual: '" . gettype($this->data[$field]) . "'");
            }
        }
    }

    private function getPrimaryField() {
        $field = null;
        foreach($this->schema as $k => $v) {
            if(array_key_exists("primary", $v) && $v["primary"])
                $field = $k;
        }
        return $field;
    }

    /**
     * insert or update the model data
     * @return $request the request used to save the delegate or throws an error
     */
    public function save() {
        $keys = array_keys($this->data);
        $values = array_values($this->data);
        $primaryField = $this->getPrimaryField();

        // if it's a new row then insert it
        if($primaryField === null || $this->isNew) {
            // before inserting, check if it's correspond to the schema
            $this->checkSchema();

            $fill = array_fill(0, sizeof($keys), "?");
            $request = Database::getConnection()->prepare("INSERT INTO " . $this->tableName . " (".join(",", $keys).") VALUES (".join(",", $fill).")");
        } else {
            // remove all primary keys
            $expr = array_filter($keys, function ($k) use($primaryField) { 
                return $k != $primaryField;
            });

            // add all :key = ? in "set" expr
            $expr = array_map(function ($k) { return $k ."= ?"; }, $expr);
            $expr = join(",", $expr);
            // build the request
            $request = Database::getConnection()->prepare("UPDATE ".$this->tableName." SET " . $expr . " WHERE ".$primaryField." = ?");
        }

        foreach($values as $k => &$v)
            $request->bindParam($k+1, $v);

        if(!$this->isNew && array_key_exists($primaryField, $this->data))
            $request->bindParam(sizeof($values), $this->data[$primaryField]);
        
        $request->execute();

        return $request;
    }

    public function asJSON() {
        return $this->data;
    }

    public function __toString() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }

}