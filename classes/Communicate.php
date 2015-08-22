<?php
/**
 * Uses PDO to connect to and query database.
 * Constructor: Connect to DB.
 * Methods:
 * 1) Query -> Retrieves, inserts and updates information in database.
 * @author ShiboTheCoder <shehabattia96@gmail.com>
 */
class Communicate
{ 
    public $fail = false;
    private static $conn;
    /*
    * @function __construct: connect to database using PDO.
    */
    public function __construct()
    {   
        try {
            Communicate::$conn = new PDO(DB_NAME, DB_USERNAME, DB_PASSWORD);
        } catch (PDOException $error) {
            $this->fail = true;
            echo 'Connection failed: ' . $error->getMessage();
        }
    }
    /*
    * @function query: Executes an SQL query.
     * @param statement string: an sql query.
     * @param parameters array: an array of values for PDO to prepare.
     * @return a PDO object with results.
    */
    public function query($statement = null, $parameters = array())
    {
        if (!$this->fail && !is_null($statement)) {
            $query = Communicate::$conn->prepare($statement);
            $execute = $query->execute($parameters);
            return $execute==true? $query : false; //If query was executed correctly, return the PDOStatement object, else return false.
        }
    }
    /*
    * @function getRows: fetches rows
     * @param query object: returned from query()
     * @return array: an array with results' rows.
    */
    public function getRows($query)
    {
        return $query!=false?$query->fetchAll(PDO::FETCH_ASSOC):false;
    }
    /*
    * @function insert: runs query to insert database rows.
     * @param tablename string: name of table to insert into.
     * @param values: arrays of values to insert.
     * @returns boolean, true -> success, false-> error.
    */
    public function insert($tablename = "", $values = array())
    {
        //Filters specific keywords and puts them in an array to make it an SQL command.
        $arr = array();
        $newValues = [];
        foreach ($values as $value) {
            if ($value == "NOW()") {
                $arr[] = "NOW()";
                
            } elseif (is_null($value)) {
                $arr[] = "NULL";
            } else {
                $arr[] = null;
                $newValues[] = $value;
            }
        }
        
        $fields = $this->makeMeQuestionMarks(count($values), $arr);
        $query = $this->query('INSERT INTO '.$tablename.' VALUES('.$fields.')', $newValues);
        return (($query!=false && $query->rowCount() > 0) ? true : false);
    }
    /*
    * @function update: updates a database entry.
     * @param tablename string: name of table.
     * @param fields array: field names that need to be updated
     * @param fieldValues array: values that need to be placed in the corrosponding fields
     * @param whereFields array: fields names used to identify row.
     * @param whereFieldValues array: values of corrosponding field names to identify row than needs altering.
     * @return boolean: true ->success false->error
    */
    public function update($tablename = "", 
            $fields = array(), 
            $fieldValues = array(), 
            $whereFields = array(), 
            $whereFieldValues = array())
    {
        //Temporary fix: Must filter if they're arrays or not else helper functions will break.
            $fields = is_array($fields)?$fields:array($fields);
            $fieldValues =  is_array($fieldValues)?$fieldValues:array($fieldValues);
            $whereFields =  is_array($whereFields)?$whereFields:array($whereFields);
            $whereFieldValues =  is_array($whereFieldValues)?$whereFieldValues:array($whereFieldValues);
        $set = $this->whatDoISetSQL($fields, $fieldValues);
        $where = $this->tellMeWhereSQL($whereFields, $whereFieldValues);
        $setString = substr($set[0],0,-2);
        $whereString = substr($where[0], 0,-4);
        $values = array_merge($set[1], $where[1]);
        $query = $this->query("UPDATE $tablename SET  $setString "
                       . "WHERE $whereString;", $values);
        return (($query!=false && $query->rowCount() > 0) ? true : false);
    }
    /*
    * @function delete: runs query to delete database rows.
     * @param tablename string: name of table to delete from.
     * @param fieldNames array: of field names to find.
     * @param fieldValues array: of field values to use.
     * @returns boolean, true -> success, false-> error.
    */
    public function delete($tablename = "", $fields = array(), $fieldValues = array())
        {
        //Temporary fix: Must filter if they're arrays or not else helper functions will break.
            $fields = is_array($fields)?$fields:array($fields);
            $fieldValues =  is_array($fieldValues)?$fieldValues:array($fieldValues);
        $where = $this->tellMeWhereSQL($fields,$fieldValues);
        $query = $this->query("DELETE FROM $tablename WHERE $where[0]", $where[1]);
        return (($query!=false && $query->rowCount() > 0) ? true : false);
    }
    /**************************************************************************************/
    /**************************HELPER FUNCTIONS**********************************/
    /**************************************************************************************/
    /*
     * @function makeMeQuestionMarks: generates question marks for a PDO prepare statement
     * @param length int: length of array for which you need that many ?
     * @return string: of question marks!
     */
    function makeMeQuestionMarks($length = 0, $sql = array())
    {
        $fields = "";
        for ($x=0;$x<$length;$x++) {
            if (!is_null($sql[$x])) {
                $fields = $fields . $sql[$x]. ", " ;
            } else {
                $fields = $fields . "?, ";
            }
        }
        return substr($fields, 0, -2);
    }
    /*
     * @function tellMeWhereSQL: generates a string of AND separated fieldnames for Update() and Delete()
     * @return array: of the fieldnames and their values
     */
    function tellMeWhereSQL($whereFields = array(), $whereFieldValues = array())
    {
        $x = 0;
        $where = "";
        foreach ($whereFields as $field) {
            if ($whereFieldValues[0] == "NOW()") {
                $where = $where . $field . " = NOW(), ";
                //Remove the NOW() from array
                $arr = array();
                $i = 0;
                foreach ($whereFieldValues as $value) {
                    if (!($i == $x)) {
                        $arr[] = $value;
                    }
                    ++$i;
                }
            $whereFieldValues = $arr;
            } else {
                $where = $where . $field . " = ? AND ";
            }
            ++$x;
        }
        return array($where,$whereFieldValues);
    }
    /*
     * @function whatDoISetSQL: generates a string of comma separated fieldnames for Update()
     * @return array: of the fieldnames and their values
     */
    function whatDoISetSQL($fields = array(), $fieldValues = array())
    {
        $set = "";
        $x = 0;
        foreach ($fields as $field) {
            if ($fieldValues[$x] == "NOW()") {
                $set = $set . $field. " = NOW(), ";
                    //Remove the NOW() from array
                $arr = array();
                $i = 0;
                foreach ($fieldValues as $value) {
                    if (!($i == $x)) {
                        $arr[] = $value;
                    }
                    ++$i;
                }
            $fieldValues = $arr;
            } else {
                $set = $set . $field. " = ?, ";
            }
            ++$x;
        }
        return array($set, $fieldValues);
    }
}
