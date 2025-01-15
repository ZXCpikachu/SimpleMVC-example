<?php

namespace application\controllers;
use ItForFree\SimpleMVC\Config;
use application\models\Article;
use application\models\AllUsers;
use application\models\Category;
use application\models\Connection;
use application\models\Subcategory;
class HomepageController extends \ItForFree\SimpleMVC\MVC\Controller
{
    public string $layoutPath = 'main.php';
    public $title = 'CMS на PHP';
    public $articlesData = array();
    public $subcategoriesData = array();
    public $results = array();
    public $Article = null;
    public $Category = null;
    public $Subcategory = null;
    public $Connection = null;
    public $Users = null;
    
    
    /*
     * Инициализация всех сущностей
     */
    protected function initModelObjects(){
        $this->Article = new Article();
        $this->Category = new Category();
        $this->Subcategory = new Subcategory();
        $this->Connection = new Connection();
        $this->Users = new AllUsers();
    }
    public function getArticles() {
    $this->initModelObjects();
    $this->articlesData = $this->Article->getList(Config::get('core.homepageNumArticles'));

    $this->results['articles'] = $this->articlesData['results'];
    $this->results['totalRows'] = $this->articlesData['totalRows'];

    $subcategoriesData = $this->Subcategory->getList();
    $categoriesData = $this->Category->getList();
    

    $this->results['subcategories'] = array();
    $this->results['categories'] = array();
    $this->results['authors'] = array();

    foreach ($categoriesData['results'] as $category) {
        $this->results['categories'][$category->id] = $category;  
    }
    
    // Проходим по подкатегориям и получаем данные
    foreach ($subcategoriesData['results'] as $subcategory) {
        $this->results['subcategories'][$subcategory->id] = $subcategory;
    }
     foreach ($this->articlesData['results'] as $article) {
        $authorsData = $this->Article->getAuthors($article->id); 
        $this->results['authors'][$article->id] = $authorsData['authors']; 
    }
}





    public function indexAction()
    {
        $this->initModelObjects();
        $this->articlesData = $this->Article->getList(Config::get('core.homepageNumArticles'));
        $this->getArticles();
        foreach ($this->results['articles'] as $article)
        {
            $article->content = substr($article->content,0,50) . ' ...';
        }
        $this->view->addVar('title',$this->title);
        $this->view->addVar('results', $this->results);
        $this->view->render('homepage/homepage.php');
    }
    
    public function viewArticleAction(){
        $this->initModelObjects();
        if (!isset($_GET['articleId']) || !is_numeric($_GET['articleId'])) {
            throw new \Exception("Invalid or missing articleId in the request.");
        }
        $this->articlesData['id'] = (int) $_GET['articleId'];
        $SingleArticle = $this->Article->getById($this->articlesData['id']);
        $this->title = $SingleArticle->title . ' | ' . $this->title;
        $this->results['article'] = [
            'id' => $SingleArticle->id,
            'title' => $SingleArticle->title,
            'publicationDate' => $SingleArticle->publicationDate,
            'subcategoryId' => $SingleArticle->subcategoryId,
            'summary' => $SingleArticle->summary,
            'content' => $SingleArticle->content,
            'active' => $SingleArticle->active,
            'subcategory' => $this->Subcategory->getById($SingleArticle->subcategoryId)
        ];
        $connections = $this->Connection->getById($this->results['article']['id']);
        $this->results['authors'] = [];
        
        foreach ($connections as $connection)
        {
            $userId = $connection->user_id;
            $this->results['authors'][] = $this->User->getById($userId)->name;
        }
        $this->view->addVar('article', $SingleArticle);
        $this->view->addVar('results', $this->results);
        $this->view->addVar('title', $this->title);
	$this->view->render('homepage/singleArticle.php');
        
    }
    
    public function archiveAction(){
		$this->initModelObjects();
		$this->articlesData = $this->Article->getList(100000);
		$this->getArticles();
		$this->results['category'] = 0;
		$this->results['subcategory'] = 0;
		$this->results['pageHeading'] = "Article Archive";
		$this->title = $this->results['pageHeading'] . " | Widget News";
		
		$this->view->addVar('title', $this->title);
		$this->view->addVar('results', $this->results);
		/*Передаем также объект категории т.к. его методы унаследованы от 
		 * родительского класса model и не являются статическими*/
		$this->view->addVar('Category', $this->Category);
		$this->view->render('homepage/archive.php');
	}
    public function archiveCatAction() {
        $this->initModelObjects();
        $subcategoryId = ( isset( $_GET['subcategoryId'] ) && 
				$_GET['subcategoryId'] ) ? (int)$_GET['subcategoryId'] : null;
        $this->results['subcategory'] = $this->Subcategory->getById($subcategoryId);
        $this->results['category'] = $this->Category->getById(
                $this->results['subcategory']->categoryId);
        $data = $this->Subcategory->getList(100000,$this->results['subcategory']->categoryId);
        $articleArr = array();
        foreach($data['results'] as $subcategory){
			$articleArr[] = $this->Article->getList(100000, $subcategory->id, true);
	}
        $this->results['articles'] = array();
	$this->results['totalRows'] = 0; 
        for( $i = 0; $i < count($articleArr); $i++){
			$this->results['articles'] = array_merge($this->results['articles'], 
					$articleArr[$i]['results']);
			$this->results['totalRows'] = $this->results['totalRows'] + 
					$articleArr[$i]['totalRows'];
		}
		if($this->results['category']){
			$this->results['pageHeading'] = $this->results['category']->name;
			$this->title = $this->results['category']->name;
		}else{
			$this->results['pageHeading'] = "Article Archive";
		}	
	$this->view->addVar('title', $this->title);
	$this->view->addVar('results', $this->results);
	$this->view->addVar('Category', $this->Category);
	$this->view->render('homepage/archive.php');
    }
    public function singleArticleAction()
{
    $this->initModelObjects();

    if (empty($_GET['articleId'])) {
        throw new InvalidArgumentException('ID статьи обязателен.');
    }

    $articleId = (int) $_GET['articleId'];

    $article = $this->Article->getById($articleId);
    if (!$article) {
        throw new NotFoundException('Статья не найдена.');
    }
    $authorsData = $this->Article->getAuthors($articleId);  
    $authors = $authorsData['authors'] ?? 'Не указано';
    $this->title = $article->title . ' | ' . $this->title;

    $subcategory = $this->Subcategory->getById($article->subcategoryId);
    $subcategoryName = $subcategory ? $subcategory->name : 'Подкатегория не указана';

    $this->results['article'] = [
        'id' => $article->id,
        'title' => $article->title,
        'publicationDate' => $article->publicationDate,
        'subcategoryId' => $article->subcategoryId,
        'summary' => $article->summary,
        'content' => $article->content,
        'active' => $article->active,
        'subcategory' => $subcategoryName,
        'authors' => $authors,
    ];
    $this->view->addVar('results', $this->results);
    $this->view->addVar('title', $this->title);
    $this->view->render('homepage/singleArticle.php');
}

public function viewArticleSubcategoryAction() {
    $this->initModelObjects();
    
    $subcategoryId = isset($_GET['subcategoryId']) ? (int) $_GET['subcategoryId'] : null;

    if (!$subcategoryId) {
        $this->view->addVar('title', 'Subcategory not found');
        $this->view->render('homepage/error.php');
        return;
    }
    $articlesData = $this->Article->getList(100, null, false, $subcategoryId);

    $this->results['articles'] = $articlesData['results'];
    $this->results['totalRows'] = $articlesData['totalRows'];
    $this->results['subcategory'] = $this->Subcategory->getById($subcategoryId);

    $this->view->addVar('title', $this->results['subcategory']->name ?? 'Articles');
    $this->view->addVar('results', $this->results);
    $this->view->addVar('Subcategory', $this->Subcategory);

    $this->view->render('homepage/viewArticleSubcategory.php');
}
public function viewArticleCategoryAction()
{
    $this->initModelObjects();
    $categoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : null;
    $articlesData = $this->Article->getList(100, null, false, $categoryId);
    $this->results['articles'] = $articlesData['results'];
    $this->results['totalRows'] = $articlesData['totalRows'];
    $this->results['category'] = $this->Category->getById($categoryId);
     $this->view->addVar('title', $this->results['category']->name ?? 'Articles');
    $this->view->addVar('results', $this->results);
    $this->view->addVar('Category', $this->Category);
    $this->view->render('homepage/viewArticleCategory.php');
}
public function viewArticleAuthorsAction()
{
    $this->initModelObjects();
    $authorsId = isset($_GET['authorsId']) ? (int)($_GET['authorsId']) : null;
    $articlesData = $this->Article->getList(100,null,false,$authorsId);
    $this->results['articles'] = $articlesData['results'];
    $this->results['totalRows'] = $articlesData['totalRows'];
    $this->results['authors'] = $this->Users->getById($authorsId);
    $this->view->addVar('title', $this->results['authors']->login ?? 'Articles' );
    $this->view->addVar('results', $this->results);
    $this->view->addVar('Users', $this->Category);
    $this->view->render('homepage/viewArticleAuthors.php');
}
  
}
