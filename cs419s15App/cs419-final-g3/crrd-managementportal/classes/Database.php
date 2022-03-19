<?php

require_once('inc/config.php');

class Database
{
    public $dbo;
    private $db_name = DB_NAME;
    private $db_host = DB_HOST;
    private $db_user = DB_USER;
    private $db_pass = DB_PASS;

    public function __construct()
    {
        try {
            $this->dbo = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name.';charset=utf8', $this->db_user, $this->db_pass);
            $this->dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    public function insert($table, $fields)
    {
        $cols = implode(', ', array_keys($fields));
        $field_values = array_values($fields);
        $placeholders = array();
        $values = array();

        for ($i = 0; $i < count($field_values); $i++)
        {
            $field = ':field'.$i;
            $placeholders[] = $field;
            $values[$field] = $field_values[$i];
        }
        $placeholders = implode(', ', $placeholders);

        $query = $this->dbo->prepare("
            INSERT INTO ".$table." (".$cols.")
            VALUES (".$placeholders.")
        ");
        $query->execute($values);

        return $this->dbo->lastInsertId();
    }

    public function update($table, $fields, $where_col, $where_value)
    {
        $cols = array_keys($fields);
        $field_values = array_values($fields);
        $updated_fields = array();
        $values = array();

        for ($i = 0; $i < count($field_values); $i++)
        {
            $field = ':field'.$i;
            $updated_fields[] = $cols[$i].' = '.$field;
            $values[$field] = $field_values[$i];
        }
        $updated_fields = implode(', ', $updated_fields);

        $query = $this->dbo->prepare("
            UPDATE ".$table."
            SET ".$updated_fields."
            WHERE ".$where_col." = :value
        ");
        $values['value'] = $where_value;
        return $query->execute($values);
    }

    public function delete($table, $where_col, $where_value)
    {
        $query = $this->dbo->prepare("
            DELETE FROM ".$table."
            WHERE ".$where_col." = :value
        ");
        return $query->execute(array('value' => $where_value));
    }
}

$db = new Database();