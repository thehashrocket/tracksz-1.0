<?php declare(strict_types = 1);

namespace App\Library;
/**
 *----------------------------------------------------
 * Config Class
 *----------------------------------------------------
 * Holds app settings needed throughout application.
 *
 * Specific configuration files for classes requiring specific settings are
 * loaded by those classes.
 */

class Config
{
    
    /**
     * Stack of items with options.
     * @var array
     */
    private static $_items = array();
    private static $initialized = false;
    
    public static function initialize()
    {
        if (self::$initialized)
            return;
        self::$_items = require __DIR__.'/../../config/settings.php';
        self::$initialized = true;
    }
    
    /**
     * Method to get an option item.
     *
     * @param  string $name Name of item
     * @return mixed        Value of item
     */
    public static function get($name){
        return self::has($name) ? self::$_items[$name] : $name;
    }
    /**
     * Conditional method to confirm the existence of an item.
     *
     * @param  string  $item Name of item
     * @return boolean       If exists or not
     */
    public static function has($item){
        return array_key_exists($item, self::$_items);
    }

}
