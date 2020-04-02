<?php


namespace Ruby\Kernel;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Framework\Kernel\Router;
use \PDOException;

class Database 
{
    public static $config;
    public function __construct () 
    {
        Database::$config = [
            'driver'    => getenv('DB_DRIVER'),
            'host'      => getenv('DB_HOST'),
            'database'  => getenv('DB_NAME'),
            'username'  => getenv('DB_USER'),
            'password'  => getenv('DB_PASS'),
            'charset'   => getenv('DB_CHARSET'),
            'collation' => getenv('DB_COLLATION'),
            'prefix'    => getenv('DB_PREFIX')
        ];
    }

    public static function load () 
    {
        try 
        {
            $db = new Manager;
            $db->addConnection(Database::$config, 'default');

            $db->setEventDispatcher(new Dispatcher(new Container));
            $db->setAsGlobal();
            $db->bootEloquent();

        } 
        catch (PDOException $e) 
        {
            print($e->getMessage());
            exit;
        }
    }
}