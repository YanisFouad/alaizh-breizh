<?php
require_once(__DIR__."/Database.php");

class SchemaValidationException extends Exception { }

class Model {
    private $tableName;
    private $schema = array();
    private $data = array();

    public function __construct($tableName, $schema, $defaultData = null) {
        $this->tableName = $tableName;
        $this->schema = $schema;
        if($defaultData)
            $this->data = $defaultData;
    }

    /**
     * set a field value to the created model
     */
    public function set($key, $value) {
        if(!in_array($key, array_keys($this->schema)))
            throw new Exception("Table column: '" . $key . "' not found in defined schema");
        $this->data[$key] = $value;
    }

    /**
     * retrieve a key from data
     * @return object|null the data associated, or null if not found.
     */
    public function get($key) {
        return $this->data[$key] ?? null;
    }

    // /**
    //  * find one row by cretirias
    //  * @param $fields a single or a list of field needed to be returned
    //  * @param $conditon a list of conditions "equivalent of a WHERE condition" 
    //  */
    // public function findOne($fields, $condition = []) {
    //     global $db;
        
    //     // build a WHERE expression (if needed)
    //     $expr = "";
    //     if(sizeof($condition) > 0) {
    //         $expr = " WHERE ";
    //         $expressions = array();
    //         foreach($condition as $v) { 
    //             if(sizeof($v) !== 3)
    //                 throw new Exception("Invalid amount of parameters, required 3");
    //             [$field, $symbol] = $v;
    //             $expressions[] = $field." ".$symbol." ?";
    //         }
    //         $expr .= " " . join(" AND ", $expressions);
    //     }

    //     // if project is an instance of array then just join all elements into a single string
    //     if(is_array($fields))
    //         $fields = join(",", $fields);

    //     // then do the request on the table
    //     $request = $db->prepare("SELECT ".$fields." FROM ".$this->tableName . $expr);
    //     foreach(array_values($condition) as $index => $v)
    //         $request->bindParam($index+1, $v[2]);
    //     $request->execute();

    //     $row = $request->fetch(PDO::FETCH_ASSOC);
    //     if(sizeof($row) < 1)
    //         return null;
    //     return $row;
    // }

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

            // we can imagine having more validation later if needed
        }
    }

    /**
     * insert or update the model data
     * @return $request the request used to save the delegate or throws an error
     */
    public function save() {
        // before saving, check if it's correspond to the schema
        $this->checkSchema();

        // @todo gérer l'upsert
        $keys = array_keys($this->data);
        $fill = array_fill(0, sizeof($keys), "?");
        $request = Database::getConnection()->prepare("INSERT INTO " . $this->tableName . " (".join(",", $keys).") VALUES (".join(",", $fill).")");
        // bind all variables
        foreach(array_values($this->data) as $k => $v)
            $request->bindParam($k+1, $v);
        $request->execute();

        return $request;
    }

}