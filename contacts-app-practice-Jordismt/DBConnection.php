<?php
class DBConnection {
    private static $connection;

    public static function connect() {
        if (self::$connection == null) {
           
            $dsn = 'mysql:host=localhost;port=3306;dbname=contacts;charset=utf8';
            $username = 'root'; 
            $password = ''; 
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true
            ];

            try {
                self::$connection = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                die("Database connection error: " . $e->getMessage() . " on " . $dsn);
            }
            
        }
        return self::$connection;
    }
}
?>
