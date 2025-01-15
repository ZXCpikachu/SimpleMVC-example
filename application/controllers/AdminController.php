<?php
namespace application\controllers;
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Url;
use application\models\Article;
use application\models\AllUsers;
use application\models\Category;
use application\models\Subcategory;
use application\models\Connection;
class AdminController extends \ItForFree\SimpleMVC\MVC\Controller
{
    public $articlesData = array();
    public $results = array();
    public $title = 'CMS на PHP';
    public $Article = null;
    public $Category = null;
    public $Subcategory = null;
    public $Users = null;
    public $Connection = null;
    protected function initModelObjects(){
		$this->Article = new Article;
		$this->Category = new Category;
		$this->Subcategory = new Subcategory;
		$this->Users = new AllUsers;
		$this->Connection = new Connection;
	}
    protected function getArticles(){
        $this->articlesData = $this->Article->getList();
        $this->results['articles'] = $this->articlesData['results'];
        $this->results['totalRows'] = $this->articlesData['totalRows'];
        
        $categoriesData = $this->Category->getList();
        foreach ($categoriesData['results'] as $category){
            $this->results['category'][$category->id] = $category->name;
        }
       
        $subcategoryData = $this->Subcategory->getList();
        foreach($subcategoryData['results'] as $subcategory){
            $this->results['subcategory'][$subcategory->id] = $subcategory->name;
            $this->results['categories']['$subcategory->id'] = $this->Category->getById($subcategory->categoryId);
        }
        
    }
    public function indexAction(){
        $this->initModelObjects();
        $this->getArticles();
        $this->view->addVar('title', $this->title);
        $this->view->addVar('results', $this->results);
        $this->view->render('admin/admin.php');
    }
    public function viewArticleAction(){
		$this->articlesData['id'] = $_GET['articleId'];
		$Article = new Article();
		$SingleArticle = $Article->getById($this->articlesData['id']);
                $this->title = $SingleArticle->title . ' | ' . $this->title;
		$this->results['article']['id'] = $SingleArticle->id;
		$this->results['article']['title'] = $SingleArticle->title;
		$this->results['article']['publicationDate'] = $SingleArticle->publicationDate;
		$this->results['article']['subcategoryId'] = $SingleArticle->subcategoryId;
		$this->results['article']['summary'] = $SingleArticle->summary;
		$this->results['article']['content'] = $SingleArticle->content;
		$this->results['article']['active'] = $SingleArticle->active;
		$this->view->addVar('results', $this->results);
                $this->view->addVar('title', $this->title);
		$this->view->render('singleArticle/singleArticle.php');
		
	}
    public function editArticleAction (){
        $this->initModelObjects();
        $this->results['pageTitle'] = "Edit article";
        $articleId = isset($_GET['articleId']) ? (int)($_GET['articleId']) : null;
        $article = $this->Article->getById($articleId);
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $title = $_POST['title'];
            $summary = $_POST['summary'];
            $content = $_POST['content'];
            $categoryId = $_POST['categoryId'];
            $subcategoryId = $_POST['subcategoryId'];
            $publicationDate = $_POST['publicationDate'];
            $active = isset($_POST['active']) ? 1 : 0;
            $authors = $_POST['$authors'];

            $article->title = $title;
            $article->summary = $summary;
            $article->content = $content;
            $article->categoryId = $categoryId;
            $article->subcategoryId = $subcategoryId;
            $article->publicationDate = $publicationDate;
            $article->active = $active;
            $article->authors = $authors ; 
            $this->Article->update($article);
        }
        $this->results['article'] = $article;
        $this->results['categories'] = $this->Category->getList()['results'];
        $this->results['subcategories'] = $this->Subcategory->getList()['results'];
        $this->view->addVar('results', $this->results);
        $this->view->render('admin/edit/article.php');
    }
    public function editCategoryAction (){
        $this->initModelObjects();
        $this->results['pageTitle'] = "Edit category";
        $categoryId = isset($_GET['categoryId']) ? (int)($_GET['categoryId']) : null;
        $category = $this->Category->getById($categoryId);
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $name = $_POST['name'];
            $description = $_POST['description'];
            
            $category->name = $name;
            $category->description = $description;
            $this->Category->update($category);
        }
        $this->results['categories'] = $this->Category->getById($categoryId);
        $this->results['formAction'] = 'editCategory&categoryId=' . $categoryId;
        $this->view->addVar('results',$this->results);
        $this->view->render('admin/edit/category.php');
    }
    public function editSubcategoryAction(){
        $this->initModelObjects();
        $this->results['pageTitle'] = "Edit subcategory";
        $subcategoryId = isset($_GET['subcategoryId']) ? (int)($_GET['subcategoryId']) : null;
        $subcategory = $this->Subcategory->getById($subcategoryId);
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $name = $_POST['name'];
            $description = $_POST['description'];
            $categoryId = $_POST['categoryId'];
            
            $subcategory->name = $name;
            $subcategory->description = $description;
            $subcategory->categoryId = $categoryId;
            $this->Subcategory->update($subcategory);
        }
        $this->results['subcategories'] = $this->Subcategory->getById($subcategoryId);
        $this->results['categories'] = $this->Category->getList() ['results'];
        $this->results['formAction'] = 'editSubcategory&subcategoryId=' . $subcategoryId;
        $this->view->addVar('results',$this->results);
        $this->view->render('admin/edit/subcategory.php');
    }
    public function editUserAction(){
        $this->initModelObjects();
        $this->results['pageTitle'] = "Edit User";
        $userId = isset($_GET['userId']) ? (int)($_GET['userId']) : null;
        $user = $this->Users->getById($userId);
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $login = $_POST['login'];
            $password = $_POST['password'];
            $active = $_POST['active'];
            
            $user->login = $login;
            $user->password = $password;
            $user->active = $active;
            $this->Users->update($user);
        }
        $this->results['users'] = $this->Users->getById($userId);
        $this->results['formAction'] = "editUser&userId=" . $userId;
        $this->view->addVar('results',$this->results);
        $this->view->render('admin/edit/user.php');
    }
    
    public function listCategoriesAction(){
        $this->initModelObjects();
        $data = $this->Category->getList();
        $this->results['categories'] = $data['results'];
        $this->results['totalRows'] = $data['totalRows'];
        $this->results['pageTitle'] = "List of categories";
        $this->title = $this->results['pageTitle'];
        $this->view->addVar('title', $this->title);
        if (isset($_GET['error'])) {
			if ($_GET['error'] == "categoryNotFound")
				$this->results['errorMessage'] = "Error: Category not found.";
			if ($_GET['error'] == "categoryContainsArticles")
				$this->results['errorMessage'] = "Error: Category contains subcategories. "
					. "Delete the subcategories, or assign them to another category, "
					. "before deleting this category.";
		}

		if (isset($_GET['status'])) {
			if ($_GET['status'] == "changesSaved")
				$this->results['statusMessage'] = "Your changes have been saved.";
			if ($_GET['status'] == "categoryDeleted")
				$this->results['statusMessage'] = "Category deleted.";
		}
        $this->view->addVar('results',$this->results);
        $this->view->render('admin/listCategories.php');
    }
    public function listSubcategoriesAction(){
        $this->initModelObjects();
        $data = $this->Subcategory->getList();
        $this->results['subcategories'] = $data['results'];
        $this->results['totalRows'] = $data['totalRows'];
        $this->results['pageTitle'] = "List of subcategories";
        $this->title = $this->results['pageTitle'];
        $this->view->addVar('title',$this->title);
        if (isset($_GET['error'])){
            if ($_GET['error'] == "subcategoryNotFound")
                $this->results['errorMessage'] = "Error: Subcategory not found.";
            if ($_GET['error'] == "subcategoryContainsArticles")
                $this->results['errorMEssage'] = "Error: Subcategory contains articles";
        }
        if (isset($_GET['status'])){
            if ($_GET['status'] == "changesSaved")
                $this->results['statusMessage'] = "Your changes have been saved.";
            if ($_GET['status'] == "subcategoryDeleted")
                $this->results['statusMessage'] = "Subcategory deleted";
        }
        $this->view->addVar('results', $this->results);
        $this->view->render('admin/listSubcategories.php');
    }
    public function listUsersAction(){
        $this->initModelObjects();
        $data = $this->Users->getList();
        $this->results['users'] = $data['results'];
        $this->results['totalRows'] = $data['totalRows'];
        $this->results['pageTitle'] = "User list";
        $this-> title = $this->results['pageTitle'];
        $this->view->addVar('title',$this->title);
        if (isset($_GET['error'])){
            if ($_GET['error'] == "userNotFound")
                $this->results['errorMessage'] = "Error: User not found.";
            if ($_GET['error'] == "userExist")
                $this->results['errorMessage'] = "Error: User with such name is alredy exist.";
        }
        if (isset($_GET['status'])){
            if ($_GET['status'] == "changesSaved")
                $this->results['statusMessage'] = "Your changes have been saved.";
            if ($_GET['status'] == "subcategoryDeleted")
                $this->results['statusMessage'] = "Subcategory deleted";
        }
        $this->view->addVar('results', $this->results);
	$this->view->render('admin/listUsers.php');
    }
}


