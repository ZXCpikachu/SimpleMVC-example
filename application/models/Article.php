<?php

namespace application\models;
use ItForFree\SimpleMVC\Config as Config;
use ItForFree\SimpleMVC\MVC\Model;
class Article extends \ItForFree\SimpleMVC\MVC\Model
{
    public string $tableName = 'articles';
    
    public $publicationDate = null;
    public $title = null;
    public $subcategoryId = null;
    public $summary = null;
    public $categoryId = null;
    public $content = null;
    public $active = null;
    public $authors = null;
    
    public function storeFormValues ( $params ) {

      // Сохраняем все параметры
      $this->__construct( $params);

      // Разбираем и сохраняем дату публикации
      if ( isset($params['publicationDate']) ) {
        $publicationDate = explode ( '-', $params['publicationDate'] );

        if ( count($publicationDate) == 3 ) {
          list ( $y, $m, $d ) = $publicationDate;
          $this->publicationDate = mktime ( 0, 0, 0, $m, $d, $y );
        }
        if (isset($params['authors']) && is_array($params['authors'])){
            $this->authors = $params['authors'];
        }else {
            $this->authors= array();
        }
      }
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
    public function getList(
    $numRows = 1000000, 
    $categoryId = null, 
    $useActiveValue = false,
    $subcategoryId = null, 
    $authorsId = null, 
    $order = "publicationDate DESC"
): array {
    // Формируем условия WHERE
    $conditions = [];
    if ($useActiveValue !== false) {
        $conditions[] = "a.active = :active";
    }
    if ($categoryId) {
        $conditions[] = "a.categoryId = :categoryId";
    }
    if ($subcategoryId) {
        $conditions[] = "a.subcategoryId = :subcategoryId";
    }
    if ($authorsId) {
        $conditions[] = "t1.user = :authorsId";
    }
    $categoryClause = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

    $sql = "SELECT SQL_CALC_FOUND_ROWS a.*, 
               UNIX_TIMESTAMP(a.publicationDate) AS publicationDate, 
               GROUP_CONCAT(authors.login SEPARATOR ', ') AS authors_login
            FROM articles AS a
            LEFT JOIN users_article AS t1 ON a.id = t1.articles
            LEFT JOIN users AS authors ON authors.id = t1.user
            $categoryClause
            GROUP BY a.id
            ORDER BY $order
            LIMIT :numRows";

    // Подготовка и выполнение запроса
    $st = $this->pdo->prepare($sql);
    if ($useActiveValue !== false) {
        $st->bindValue(":active", $useActiveValue, \PDO::PARAM_INT);
    }
    if ($categoryId) {
        $st->bindValue(":categoryId", $categoryId, \PDO::PARAM_INT);
    }
    if ($subcategoryId) {
        $st->bindValue(":subcategoryId", $subcategoryId, \PDO::PARAM_INT);
    }
    if ($authorsId) {
        $st->bindValue(":authorsId", $authorsId, \PDO::PARAM_INT);
    }
    $st->bindValue(":numRows", $numRows, \PDO::PARAM_INT);
    $st->execute();
    $list = [];
    while ($row = $st->fetch()) {
        $modelClassName = static::class;
        $example = new $modelClassName($row);
        if (isset($row['authors_login']) && !empty($row['authors_login'])) {
            $example->authors = explode(', ', $row['authors_login']);
        } else {
            $example->authors = [];
        }
        $list[] = $example;
    }

    // Получаем общее количество строк
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $this->pdo->query($sql)->fetch();

    // Возвращаем массив с результатами и количеством строк
    return [
        "results" => $list,
        "totalRows" => $totalRows[0]
    ];
}
     public function getAuthors($articleId): array
{
    $sql = "
        SELECT GROUP_CONCAT(users.login SEPARATOR ', ') AS users_name
        FROM users
        JOIN users_article ON users.id = users_article.user
        WHERE users_article.articles = :articleId
    ";
    $st = $this->pdo->prepare($sql);
    $st->bindValue(':articleId', $articleId, \PDO::PARAM_INT);
    $st->execute();
    $result = $st->fetch();
    return ['authors' => $result['users_name'] ?? ''];
}



    public function insert() {

        // Есть уже у объекта Article ID?
        if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );

        // Вставляем статью
        $sql = "INSERT INTO articles ( publicationDate, categoryId,subcategoryId ,title, summary, content, active ) VALUES ( FROM_UNIXTIME(:publicationDate), :categoryId,:subcategoryId ,:title, :summary, :content, :active )";
        $st = $this->pdo->prepare ( $sql );
        $st->bindValue( ":publicationDate", $this->publicationDate, \PDO::PARAM_INT );
        $st->bindValue( ":categoryId", $this->categoryId, \PDO::PARAM_INT );
        $st->bindValue( ":subcategoryId", $this->subcategoryId, \PDO::PARAM_INT );
        $st->bindValue( ":title", $this->title, \PDO::PARAM_STR );
        $st->bindValue( ":summary", $this->summary, \PDO::PARAM_STR );
        $st->bindValue( ":content", $this->content, \PDO::PARAM_STR );
        $st->bindValue( ":active", $this->active, \PDO::PARAM_INT);
        $st->execute();
        $this->id = $this->pdo->lastInsertId();
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute(); 
        foreach ($this->authors as $user) {
            $sql = "INSERT INTO users_article (user, article) VALUES (:user, :id)";
            $st = $this->pdo->prepare($sql);
            $st->bindValue(":user", $user, PDO::PARAM_INT);
            $st->bindValue(":id", $this->id, PDO::PARAM_INT);

            if (!$st->execute()) {
                // Логирование ошибки или вывод сообщения об ошибке
                error_log("Error inserting into users_article: " . implode(", ", $st->errorInfo()));
            }
        }
    }

    /**
    * Обновляем текущий объект статьи в базе данных
    */
    public function update() 
    {        
      // Есть ли у объекта статьи ID?
      if (is_null($this->id)) trigger_error("Article::update(): Attempt to "
              . "update an Article object that does not have its ID property "
              . "set.", E_USER_ERROR);

      // Обновляем статью
      $sql = "UPDATE articles SET publicationDate=FROM_UNIXTIME(:publicationDate),"
              . " categoryId=:categoryId, subcategoryId=:subcategoryId,"
              . " title=:title, summary=:summary, content=:content,"
              . " active=:active WHERE id = :id";
      $st = $this->pdo->prepare($sql);
      $st->bindValue(":publicationDate", $this->publicationDate, PDO::PARAM_INT);
      $st->bindValue(":categoryId", $this->categoryId, PDO::PARAM_INT);
      $st->bindValue(":subcategoryId", $this->subcategoryId, PDO::PARAM_INT);
      $st->bindValue(":title", $this->title, PDO::PARAM_STR);
      $st->bindValue(":summary", $this->summary, PDO::PARAM_STR);
      $st->bindValue(":content", $this->content, PDO::PARAM_STR);
      $st->bindValue(":id", $this->id, PDO::PARAM_INT);
      $st->bindValue(":active", $this->activeArticle, PDO::PARAM_INT);
      $st->execute();
      $sql = "DELETE FROM users_article WHERE articles = :id";
      $st = $this->pdo->prepare($sql);
      $st->bindValue(":id", $this->id, PDO::PARAM_INT);
      $st->execute();
      foreach ($this->authors as $author) {
        $sql = "INSERT INTO users_article (user, articles) VALUES (:user, :id)";
        $st = $conn->prepare($sql);
        $st->bindValue(":user", $author, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
    }
    }


    /**
    * Удаляем текущий объект статьи из базы данных
    */
    public function delete() : void {

      // Есть ли у объекта статьи ID?
      if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем статью
      $st = $this->pdo->prepare ( "DELETE FROM articles WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $st = $this->pdo->prepare("DELETE FROM users_article WHERE article = :id");
      $st->bindValue(":id", $this->id, PDO::PARAM_INT);
      $st->execute();
    }
}