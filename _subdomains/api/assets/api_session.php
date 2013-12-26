<?php

class api_session
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
    
    public static function destory()
    {
        $_SESSION = array();
        setcookie("PHPSESSID", null, time()-3600);
        self::$json['answer']['set'] = true;
        session_regenerate_id(true);
        self::$json['answer']['new_session_id'] = session_id();
    }
    
    public static function get_id()
    {
        if(isset($_SESSION['login']) && $_SESSION['login'])
        {
            $cpl_login = true;
            $premium = liga::user_premium($_SESSION['uid']);
            $premium = $premium['status'];
        }
        else
        {
            $premium = false;
            $cpl_login = false;
        }
        self::$json['answer']['session_id'] = session_id();
        self::$json['answer']['cpl_login'] = $cpl_login;
        self::$json['answer']['premium_status'] = $premium;
    }
    
    public static function get_my_gems()
    {
        if(isset($_SESSION['login']) && $_SESSION['login'])
        {
            mysql::query("SELECT `gems` FROM `users` WHERE `uid`='".$_SESSION['uid']."'");
            $data = mysql::array_result();
            self::$json['gems'] = $data['gems'];
            $_SESSION['gems'] = $data['gems'];
        }
        else
        {
            
        }
    }
    
    public static function output($type)
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