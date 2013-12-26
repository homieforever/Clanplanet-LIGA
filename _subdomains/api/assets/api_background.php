<?php

class api_background
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
    
    public static function set()
    {
        if(isset($_POST['background_name']))
        {
            liga::background_set($_POST['background_name']);
            $_SESSION['background']['name'] = $_POST['background_name'];
        }
        else
        {
            liga::background_random();
        }
        self::$json['answer']['set'] = true;
    }
    
    
    public static function output()
    {
        header("Content-type: application/json");
        echo json_encode(self::$json, JSON_PRETTY_PRINT);
    }
}