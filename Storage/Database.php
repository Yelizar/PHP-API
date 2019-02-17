<?php
// LazarusRai

namespace Api\Storage;

class Database
{
// specifying database credential
    private static $host = 'localhost';
    private static $db_name = 'tetramap_live';
    private static $username = 'aspire2';
    private static $password = '@rangit0t0@';
    private static $instance = null;

    /**
     * @return Database|\PDO|null
     */
    public static function getInstance(){
        if(!self::$instance)
        {
            self::$instance = new Database();
            try {
                self::$instance = new \PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);
                self::$instance->exec("set names utf8");
            } catch (\PDOException $exception) {
                echo "connection Error: " . $exception->getMessage();
            }
        }

        return self::$instance;
    }
}
?>