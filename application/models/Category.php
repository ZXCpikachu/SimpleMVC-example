<?php

namespace application\models;
use ItForFree\SimpleMVC\Config as Config;
use ItForFree\SimpleMVC\MVC\Model;
use ItForFree\SimpleMVC\Application;
use ItForFree\rusphp\Log\SimpleEchoLog;

class Category extends \ItForFree\SimpleMVC\MVC\Model
{
    public string $tableName = "categories";
    public ?int $id = null;
    public  $name = null;
    public  $description = null;
    public string $orderBy = 'name ASC';
    public function storeFormValues ($param){
         $this->__construct( $params );
    }
   public function getById(int $id, string $tableName = ''): ?Model
    {  
        $tableName = !empty($tableName) ? $tableName : $this->tableName;
        
        $sql = "SELECT * FROM $tableName where id = :id";      
        $modelClassName = static::class;
        
        $st = $this->pdo->prepare($sql); 
        
        $st->bindValue(":id", $id, \PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        
        if ($row) { 
            return new $modelClassName( $row );
        } else {
            return null;
        }
    }
    public function getList($numRows=100000,$order="name ASC"):array {
        $sql = "SELECT * FROM categories ORDER BY $order LIMIT :numRows";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":numRows", $numRows, \PDO::PARAM_INT);
        $st->execute();
        $list = array();
        while ($row= $st->fetch()){
            $category = new Category($row);
            $list[] = $category;
        }
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $this->pdo->query($sql)->fetch();
        return [
            "results" => $list,
            "totalRows" => $totalRows['totalRows']
        ];
    }
    public function insert($tableName = ''){
        $tableName = !empty($tableName) ? $tableName : $this->tableName;
        $sql = "INSERT INTO $tableName (name,description) VALUES (:name,:description)";
        $st= $this->pdo->prepare($sql);
        $st->bindValue(":name", $this->name, \PDO::PARAM_STR);
        $st->bindValue(":description",$this->description, \PDO::PARAM_STR);
        $st->execute();
        $this->id = $this->pdo->lastInsertId();
    }
    public function update($tableName = ''){
        $tableName = !empty($tableName) ? $tableName : $this->tableName;
        $sql = "UPDATE $tableName SET name = :name, description=:description WHERE id = :id";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":name", $this->name, \PDO::PARAM_STR);
        $st->bindValue(":description", $this->description, \PDO::PARAM_STR);
        $st->bindValue(":id", $this->id, \PDO::PARAM_INT);
        $st->execute();
    }
    public function delete($tableName = ''):void{
        $tableName = !empty($tableName) ? $tableName : $this->tableName;
        $st = $this->pdo->prepare("DELETE FROM $tableName WHERE id = :id LIMIT 1");
        $st->bindValue(":id", $this->id, \PDO::PARAM_INT);
        $st->execute();
    }
}
