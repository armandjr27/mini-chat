<?php
class Autoload
{
    public static function load($class)
    {
        $file = str_replace('\\', '/', __DIR__ . '\\' . $class . '.class.php');
        
        if (file_exists($file)) 
        {
            require_once $file;
        }
    }

    public static function register()
    {
        spl_autoload_register([__CLASS__, 'load']);
    }
}

Autoload::register();