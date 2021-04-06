<?php

abstract class AbstractService {
    protected $tableName;
    protected $columns;
    protected $primaryKey = 'id';
    private $dbConnection;

    public function __construct() {
        $dsn = "mysql:host=db;dbname=iris-api";
        $user = "iris-api";
        $passwd = "R3pWwt2nKYF7cEjNUY4CV2EY94x5gT";

        $this->dbConnection = new PDO($dsn, $user, $passwd);
    }

    public final function getConnection() {
        return $this->dbConnection;
    }

    public function getAll() {
        $stm = $this->dbConnection->prepare("SELECT * FROM {$this->tableName};");
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stm = $this->dbConnection->prepare("SELECT * FROM {$this->tableName} WHERE {$this->primaryKey} = :id;");
        $stm->bindValue(':id', $id);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    private function getColumnsWithoutPrimaryKey() {
        $columns = $this->columns;
        if (false !== $key = array_search($this->primaryKey, $columns)) {
            unset($columns[$key]);
        }
        return $columns;
    }
    public function create($data) {
        $columns = $this->getColumnsWithoutPrimaryKey();
        $sql = "INSERT INTO {$this->tableName}(";
        $sql .= implode(', ', array_map(function($item) { return "`$item`"; }, $columns));
        $sql .= ") VALUES (";
        $sql .= implode(', ', array_map(function($item) { return ":$item"; }, $columns));
        $sql .= ")";

        $stm = $this->dbConnection->prepare($sql);
        foreach($columns as $col) {
            $stm->bindValue(":$col", $data[$col]);
        }

        $stm->execute();

        $data['id'] = $this->dbConnection->lastInsertId();

        return $data;
    }
    public function update($data) {
        $columns = $this->getColumnsWithoutPrimaryKey();
        $sql = "UPDATE {$this->tableName} SET ";
        $i = 0;
        $len = count($columns);
        foreach ($columns as $col) {
            $sql .= "`$col` = :$col";
            if ($i !== $len - 1) {
                $sql .= ', ';
            }
            $i++;
        }
        $sql .= " WHERE {$this->primaryKey} = :id";

        $stm = $this->dbConnection->prepare($sql);

        foreach($columns as $col) {
            $stm->bindValue($col, $data[$col]);
        }
        $stm->bindValue($this->primaryKey, $data[$this->primaryKey]);
        $stm->execute();

        return $data;
    }
    public function delete($id) {
        $stm = $this->dbConnection->prepare("DELETE FROM {$this->tableName} WHERE {$this->primaryKey} = :id");
        $stm->bindValue('id', $id);
        $stm->execute();

        return true;
    }
}