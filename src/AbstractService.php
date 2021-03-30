<?php

abstract class AbstractService {
    protected $tableName;
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
        $stm->bindParam('id', $id);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stm = $this->dbConnection->prepare("SELECT * FROM {$this->tableName} WHERE id = :id;");
        $stm->bindParam('id', $id);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($data) {
        $stm = $this->dbConnection->prepare("INSERT INTO {$this->tableName}(`name`) VALUES (:name)");
        $stm->bindParam('name', $data['name']);
        $stm->execute();

        $data['id'] = $this->dbConnection->lastInsertId();

        return $data;
    }
    public function update($data) {
        $stm = $this->dbConnection->prepare("UPDATE {$this->tableName} set `name` = :name WHERE id = :id");
        $stm->bindParam('name', $data['name']);
        $stm->bindParam('id', $data['id']);
        $stm->execute();

        return $data;
    }
    public function delete($id) {
        $stm = $this->dbConnection->prepare("DELETE FROM {$this->tableName} WHERE id = :id");
        $stm->bindParam('id', $id);
        $stm->execute();

        return true;
    }
}