<?php 
 namespace parser\utils;
use PDO;
class Db
{
	public static $connection;
	private function __construct()
	{
	}
	/**
	 * @param $params
	 * @return \Illuminate\Database\Capsule\Manager
	 */
	public static function initConnection($params=[])
	{
	    if (self::$connection === null) {
	        $capsule = new \Illuminate\Database\Capsule\Manager;
	        $capsule->addConnection($params);
	        $capsule->setAsGlobal();
	        $capsule->bootEloquent();
	        self::$connection = $capsule;
	    }
	    return self::$connection;
	}
	/**
	 * @return mixed
	 */
	public static function getConnection()
	{
	    return self::$connection;
	}
}