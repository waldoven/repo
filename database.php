<?php
class Database
{
    private static $dbName = 'repo' ;
    private static $dbHost = 'localhost' ;
    private static $dbUsername = 'root';
    private static $dbUserPassword = 'root';
    private static $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                     PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                                    );  
    private static $cont  = null;
     
    public function __construct() {
        die('Init function is not allowed');
    }
     
    public static function connect()
    {
       // One connection through whole application
       if ( null == self::$cont )
       {     
        try
        {
          self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword, self::$options); 
        }
        catch(PDOException $e)
        {
          die($e->getMessage()); 
        }
       }
       return self::$cont;
    }
     
    public static function disconnect()
    {
        self::$cont = null;
    }
}
?>