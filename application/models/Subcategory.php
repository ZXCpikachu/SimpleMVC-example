<?php
namespace application\models;
use ItForFree\SimpleMVC\Config as Config;
use ItForFree\SimpleMVC\Application;
use ItForFree\SimpleMVC\MVC\Model;
use ItForFree\rusphp\Log\SimpleEchoLog;
class Subcategory extends \ItForFree\SimpleMVC\MVC\Model
{
    public string $tableName = 'subcategories';
    public ?int $id = null;
    public $name = null;
    public $description = null;
    public $categoryId = null;
    public function storeFormValues($params){
		$this->__construct( $params );
	}
        public function getList($numRows = 1000000, $categoryId = null, $order = "name ASC") : array
    {
        $categoryClause = $categoryId !== null ? "WHERE categoryId = :categoryId" : "";
        $sql = "SELECT * FROM $this->tableName $categoryClause ORDER BY $order LIMIT :numRows";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":numRows", $numRows, \PDO::PARAM_INT);
        if ($categoryId !== null) {
            $st->bindValue(":categoryId", $categoryId, \PDO::PARAM_INT);
        }
        $st->execute();
        $list = [];
        while ($row = $st->fetch()) {
            $subcategory = new Subcategory($row);
            $list[] = $subcategory;
        }
        $totalRowsSql = "SELECT COUNT(*) AS totalRows FROM $this->tableName $categoryClause";
        $totalRowsSt = $this->pdo->prepare($totalRowsSql);
        if ($categoryId !== null) {
            $totalRowsSt->bindValue(":categoryId", $categoryId, \PDO::PARAM_INT);
        }
        $totalRowsSt->execute();
        $totalRows = $totalRowsSt->fetch();
        return [
            "results" => $list,
            "totalRows" => $totalRows['totalRows']
        ];
    }
    
    #[\Override]
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
    
    public function getCategIdByName($name){
        $sql = "SELECT id FROM categories WHERE name = :name ";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":name", $name, \PDO::PARAM_STR);
	$st->execute();
	$row = $st->fetch();
	$conn = null;
	if($row){
                    return $row[0];
		}
    }
     public function isSubcategoryExist($name)
    {
        $sql = "SELECT name FROM $this->tableName WHERE name = :name";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":name", $name, \PDO::PARAM_STR);
        $st->execute();
        return $st->fetch() !== false;
    }
    public function insert(){
		// Проверяем есть ли уже у обьекта Subcategory ID ?
		if ( !is_null( $this->id ) ) trigger_error ( "Subcategory::insert(): "
				. "Attempt to insert a Subcategory object that already has its "
				. "ID property set (to $this->id).", E_USER_ERROR );
		$sql = "INSERT INTO $this->tableName(name,description ,categoryId) VALUES(:name,:description ,:categoryId)";
		$st = $this->pdo->prepare($sql);
		$st->bindValue(":name", $this->name, \PDO::PARAM_STR );
                $st->bindValue(":description",$this->description, \PDO::PARAM_STR);
		$st->bindValue(":categoryId", $this->categoryId, \PDO::PARAM_INT );
		$st->execute();
		$this->id = $this->pdo->lastInsertId();
	}
    public function update($subcategory){
		$sql = "UPDATE $this->tableName SET name=:name, description = :description, categoryId=:categoryId WHERE id=:id";
		$st = $this->pdo->prepare($sql);
		$st->bindValue(":name", $subcategory->name, \PDO::PARAM_STR);
                $st->bindValue(":description", $subcategory->description, \PDO::PARAM_STR);
		$st->bindValue(":categoryId", $subcategory->categoryId, \PDO::PARAM_INT);
		$st->bindValue(":id", $subcategory->id, \PDO::PARAM_INT);
		$st->execute();
        }
    public function delete() :void {
		// У объекта Subcategory  есть ID?
      if ( is_null( $this->id ) ) trigger_error ( "Subcategory::delete(): "
			  . "Attempt to delete a Subcategory object that does not have its "
			  . "ID property set.", E_USER_ERROR );
      $st = $this->pdo->prepare ( "DELETE FROM $this->tableName WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, \PDO::PARAM_INT );
      $st->execute();
		
	}
}
