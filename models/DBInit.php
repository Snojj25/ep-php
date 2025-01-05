<?php  

class DBInit {  
    private static $host = "localhost:3306";  
    private static $user = "root";  
    private static $password = "password";  
    private static $schema = "ecommerce";  
    private static $instance = null;  

    private function __construct() {}  
    private function __clone() {}  

    /**  
     * Returns PDO instance with database connection  
     * @return PDO Database connection instance  
     */  
    public static function getInstance() {  
        if (!self::$instance) {  
            $config = "mysql:host=" . self::$host . ";dbname=" . self::$schema;  
            
            $options = [  
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  
                PDO::ATTR_PERSISTENT => true,  
                PDO::ATTR_EMULATE_PREPARES => false  
            ];  

            if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {  
                $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8mb4";  
            }  

            try {  
                self::$instance = new PDO($config, self::$user, self::$password, $options);  
            } catch (PDOException $e) {  
                throw new Exception("Database connection failed: " . $e->getMessage());  
            }  
        }  

        return self::$instance;  
    }  
}  