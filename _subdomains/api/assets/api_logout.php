<?php

class api_logout
{
    private static $json = array();
    
    public static function init($action, $type)
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
        self::output($type);
    }
    
    public static function error()
    {
        header("Status: 404");
    }
    
    public static function check()
    {
        if(isset($_COOKIE['cpl_auth']))
        {
            self::$json['answer']['cpl_auth'] = true;
        }
        else
        {
            self::$json['answer']['cpl_auth'] = false;
        }
    }
    
    public static function output()
    {
        if($type == "json")
        {
            header("Content-type: application/json");
            echo json_encode(self::$json, JSON_PRETTY_PRINT);
        }
        else
        {
            header("Content-type: application/javascript");
            if(isset(self::$json['answer']))
            {
                echo "api_answer = ".json_encode(self::$json['answer']).";";
            }
            else
            {
                echo "api_answer = null;";
            }
        }
    }
}
