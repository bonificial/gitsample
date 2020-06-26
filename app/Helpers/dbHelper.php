<?php
include_once 'config/dbh.php';
 /*
  * Any function that interacts with the DB and is generic to many APIs can be placed in this file.
  */
class dbHelper  extends Dbh
{
    /*
 * This function below can be reused to retrieve a table's whole row or several columns
 * in a row. The arguments required in order are the  column to check, unique value in key column
 * the fields required, and the table name.If fields are not provided, it will retrieve all fields.
 * Its advisable to provide the names of the fields you need to optimise speed.
 */

    public function retrieve_fields_by_col_val($column, $columnValue, $table, $fields = '*')
    {
        $sql = "SELECT $fields FROM $table WHERE $table.$column=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$columnValue]);
        return $stmt->fetch();
    }


    public function does_value_exist($column, $table,$value,$returnFetched = true)
    {
        $fieldstofetch = $returnFetched ? '*' : $column;
        $sql = "SELECT $fieldstofetch FROM $table WHERE $column=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$value]);
        $result = $stmt->fetch();
        if (!$result) {
            $stmt = null;
            return false;

        } else {
            $stmt = null;
            if($returnFetched){
                return $result;
            }else{
                return true;
            }



        }
    }



}