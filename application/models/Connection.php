<?php

namespace application\models;
use ItForFree\SimpleMVC\MVC\Model;

class Connection extends \ItForFree\SimpleMVC\MVC\Model
{
    public string $tableName = 'connections';
	
    public string $orderBy = 'article_id';
	
    public $user_id = '';
	
    public $article_id = '';
    
    public function getById($id, $tableName = '') : ?Model{
    
        $tableName = !empty($tableName) ? $tableName : $this->tableName;
        
        $sql = "SELECT * FROM $tableName where article_id = :id";      
        $modelClassName = static::class;
        
        $st = $this->pdo->prepare($sql); 
        
        $st->bindValue(":id", $id, \PDO::PARAM_INT);
        $st->execute();
		$row = null;
		while ($row = $st->fetch()) {
			$example = new $modelClassName($row);
			$list[] = $example;
		}
		return $list;
    }
    public function insert(){
      $sql = "INSERT INTO connections ( article_id, user_id) VALUES ( :articleId, :userId )";
      $st = $this->pdo->prepare( $sql );
      $st->bindValue( ":articleId", $this->article_id, \PDO::PARAM_INT );
      $st->bindValue( ":userId", $this->user_id, \PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }
}
