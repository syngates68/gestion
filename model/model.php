<?php

class Model
{
	protected static $_db;

	public static function set_db(PDO $db) 
    {
		self::$_db = $db;
	}

	public static function db() 
    {
		return self::$_db;
	}
}