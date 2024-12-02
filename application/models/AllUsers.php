<?php

namespace application\models;
use ItForFree\SimpleMVC\Config;
class AllUsers extends \ItForFree\SimpleMVC\MVC\Model
{
	/**
     * Имя таблицы пользователей
     */
    public string $tableName = 'users';
	
	/**
     * @var string Критерий сортировки строк таблицы
     */
    public string $orderBy = 'name ASC';
	
	//Свойства
	/**
    * @var int ID пользователя из базы данных
    */
	public ?int $id = null;
	
	/**
    * @var string Имя пользователя
    */
    public $name = null;
	
	/**
    * @var string пароль пользователя
    */
    public $pass = null;
	
	/**
    * @var bool индикатор, показывающий активен пользователь или нет 
    */
    public $active = null;
}