<?php

class api_plugins
{
    private static $json = array();
    
    public static function init($action)
    {
        self::$json['request']['action'] = $action;
        if(is_callable("self::$action"))
        {
            self::$action();
        }
        else
        {
            self::error();
        }
        self::output();
    }
    
    public static function error()
    {
        header("Status: 404");
    }
    
    public static function execute()
    {
        self::$json['first_run'] = false;
        self::$json['second_run'] = false;
        self::$json['update'] = false;
        $plugins = array();
        $plugins[1] = 2002; // CPL Chat on Clanplanet.de Plugin.
        if(isset($_POST['plugin_id']) && isset($_POST['code']) && isset($_POST['version']))
        {
            mysql::query("SELECT * FROM `plugins_execute` WHERE `code`='".mysql::escape($_POST['code'])."'");
            
            if(mysql::num_result())
            {
                $data = mysql::array_result();
                
                if($data['counter'] == 1)
                {
                    self::$json['second_run'] = true;
                }
                
                $counter = $data['counter'];
            }
            else
            {
                mysql::query("INSERT INTO `plugins_execute` (`code`, `counter`, `last_action`) VALUES ('".mysql::escape($_POST['code'])."', '0', '".time()."')");
                $counter = 0;
                self::$json['first_run'] = true;
            }
            
            $counter = $counter+1;
            
            if($plugins[$_POST['plugin_id']] > $_POST['version'])
            {
                self::$json['update'] = true;
            }
            
            mysql::query("UPDATE `plugins_execute` SET `counter`='".$counter."' WHERE `code`='".mysql::escape($_POST['code'])."'");
            mysql::query("UPDATE `plugins_execute` SET `last_action`='".time()."' WHERE `code`='".mysql::escape($_POST['code'])."'");
        }
        else
        {
            self::error();
        }
    }
    
    public static function output()
    {
        header("Content-type: application/json");
        echo json_encode(self::$json, JSON_PRETTY_PRINT);
    }
}
