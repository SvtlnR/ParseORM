<?php 
 namespace parser\utils;
use PDO;
// class Db{
// 	static private $instance = null;
// 	private function __construct(){}
// 	public static function getInstance(){
// 		if(!self::$instance){
// 			try {
// 				$host = 'localhost';
// 				$dbname = 'parseSites';
// 				$dbuser = 'root';
// 				$dbpassword =''; 
// 				$dsn='mysql:host='.$host.';dbname='.$dbname;
// 				self::$instance=new PDO($dsn, $dbuser,$dbpassword);	
// 				} catch (PDOException $e) {
// 					//ErrorOutput::err_out($e->getMessage());
// 					$instance=null;	
// 				}
// 			}	
// 			return self::$instance;
// 		}

// 	}
class Db
{
public static $connection;

/**
 * Db constructor.
 */
private function __construct()
{
}

private function __wakeup()
{
}

private function __clone()
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